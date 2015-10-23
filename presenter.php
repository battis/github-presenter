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
	
		try {
			$commit = json_decode(
				$github->get($_REQUEST['commit']),
				true
			);
		} catch (Exception $e) {
			$smarty->addMessage(
				'Problem Retrieving Commit Information',
				'There was a problem retrieving the information about your commit. Please make sure that you have given the GitHub user <code>' . $secrets->github->user . '</code> access to your repository.',
				NotificationMessage::ERROR
			);
			$step = STEP_COMMIT;
		}
		
		if ($step == STEP_DISPLAY) {
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
		}
		
		/* flow into STEP_FILES */

	case STEP_FILES:
	
		if (empty($_REQUEST['commit_url'])) {
			$smarty->addMessage(
				'Missing Commit URL',
				'You must provide the URL of a specific commit to your repository on GitHub for this to work.',
				NotificationMessage::ERROR
			);
			$step = STEP_COMMIT;
		}
	
		if ($step == STEP_FILES) {
			/* get the commit information */
			try {
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
			} catch (Exception $e) {
				$smarty->addMessage(
					'Commit URL Problem',
					'The URL that your provided for your commit (<a href="' . $_REQUEST['commit_url'] . '">' . $_REQUEST['commit_url'] . '</a>) could not be found on GitHub. Please check to make sure that you are, in fact, providing the URL of a specific commit in your repository (rather than, say, the URL of a particular source file or directory) and that you have given the GitHub user <code>' . $secrets->github->user . '</code> access to your repository.',
					NotificationMessage::ERROR
				);
				$step = STEP_COMMIT;
			}
	
			if ($step == STEP_FILES) {
				/* get the commit tree */
				try {
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
				} catch (Exception $e) {
					$smarty->addMessage(
						'Problem Retrieving File List',
						'There was a problem retrieving the list of files changed in <a href="">your commit</a>. Please make sure that you have given the GitHub user <code>' . $secrets->github->user . '</code> access to your repository.',
						NotificationMessage::EROR
					);
					$step = STEP_COMMIT;
				}
				
				if ($step == STEP_FILES) {
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
				}
			}
		}
		
		/* flow into STEP_COMMIT */

	case STEP_COMMIT:
	default;

		$smarty->assign('formTemplate', 'commit.tpl');
		$smarty->assign('formHidden', array('step' => STEP_FILES));
		$smarty->display('picker.tpl');
}

?>