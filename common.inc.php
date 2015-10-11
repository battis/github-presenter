<?php
	
require_once(__DIR__ . '/vendor/autoload.php');

function html_var_dump($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

$secrets = simplexml_load_string(file_get_contents('secrets.xml'));

$smarty = StMarksSmarty::getSmarty(true, __DIR__ . '/templates');

$github = new TokenPest("https://api.github.com", (string) $secrets->github->token);
$github->addHeader('User-Agent', 'github-presenter');

?>