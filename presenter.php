<?php
	
require_once('common.inc.php');

define('STEP_COMMIT', 1);
define('STEP_FILES', 2);
define('STEP_DISPLAY', 3);

function isInvisible($path) {
	return empty($_REQUEST['ignore_invisible']) || ($_REQUEST['ignore_invisible'] && !preg_match('%^(\..*)|(.*\/\..*)%', $path));
}

function isFiltered($filename) {
	return empty($_REQUEST['enable_filter']) || ($_REQUEST['enable_filter'] && preg_match("%{$_REQUEST['regex_filter']}%", $filename));
}

$step = (empty($_REQUEST['step']) ? STEP_COMMIT : $_REQUEST['step']);
$extensions = array(
	'tpl' => 'smarty'
);

switch($step) {
	
	case STEP_DISPLAY:
	
		$commit = json_decode(
			$github->get($_REQUEST['commit']),
			true
		);
		$commit['message'] = \Michelf\Markdown::defaultTransform('# ' . $commit['message']);
		$smarty->assign('commit', $commit);
	
		$files = array();
		foreach ($_REQUEST['files'] as $path => $url) {
			$blob = json_decode($github->get($url), true);
			$language = preg_replace('/^.*\.(\w+)$/', '$1', $path);
			if (array_key_exists($language, $extensions)) {
				$language = $extensions[$language];
			}
 			$geshi = new GeSHi(base64_decode($blob['content']), $language);
			$geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);

			$files[$path] = array(
				'filename' => basename($path),
				'path' => dirname($path),
				'content' => $geshi->parse_code()
			);
		}
		
		$smarty->assign('files', $files);
		if (empty($_REQUEST['pdf']) || !$_REQUEST['pdf']) {
			$smarty->display('display.tpl');
		} else {
			$dompdf = new \Dompdf\Dompdf();
			$dompdf->loadHtml($smarty->fetch('display.tpl'));
			$dompdf->render();
			$dompdf->stream();
			exit;
		}
	
		break;

	case STEP_FILES:
	
		/* get the commit information */
		$commit = json_decode(
			$github->get(
				preg_replace(
					'%^https://github.com/(.*)/commit/(.*)$%',
					'repos/$1/commits/$2',
					$_REQUEST['commit_url']
				)
			),
			true
		);

		/* get the commit tree */
		$commitTree = json_decode(
			$github->get(
				preg_replace(
					'%^https://github.com/(.*)/commit/(.*)$%',
					'repos/$1/git/trees/$2',
					$_REQUEST['commit_url']
				),
				array(
					'recursive' => true
				)
			),
			true
		);
		
		/* make a list of changed subdirectories of the root (i.e. Eclipse projects) */
		$changes = array();
		foreach ($commit['files'] as $file) {
			if (isInvisible($file['filename'])) {
				if(isFiltered(basename($file['filename']))) {
					$parts = explode('/', $file['filename']);
					if (!in_array($parts[0], $changes)) {
						$changes[] = $parts[0];
					}
				}
			}
		}

		/* make a list of viewable files in the commit tree */		
		$tree = array();
		$currentDir = '@';
		foreach ($commitTree['tree'] as $leaf) {
			if (isInvisible($leaf['path'])) {
				if(isFiltered(basename($leaf['path']))) {
					$parts = explode('/', dirname($leaf['path']));
					$leaf['changed'] = in_array($parts[0], $changes);
					$leaf['filename'] = basename($leaf['path']);
					$leaf['path_parts'] = $parts;
					$tree[$leaf['path']] = $leaf;
				}
			}
		}
		
		$commit['commit']['message'] = \Michelf\Markdown::defaultTransform('### ' . $commit['commit']['message']);
		$smarty->assign('commit', $commit['commit']);
		$smarty->assign('tree', $tree);
		$smarty->assign('formTemplate', 'files.tpl');
		$smarty->assign('formHidden', array('step' => STEP_DISPLAY));
		$smarty->display('picker.tpl');
		break;

	case STEP_COMMIT:
	default;

		$smarty->assign('formTemplate', 'commit.tpl');
		$smarty->assign('formHidden', array('step' => STEP_FILES));
		$smarty->display('picker.tpl');
}

?>