<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Battis\DataUtilities;
use Battis\BootstrapSmarty\BootstrapSmarty;
use Battis\GitHubPresenter\TokenPest;

$secrets = simplexml_load_string(file_get_contents('secrets.xml'));

$smarty = BootstrapSmarty::getSmarty(__DIR__ . '/templates');
$smarty->assign('title', DataUtilities::titleCase(basename(__DIR__), ['spaceEquivalents' => ['-']]));

$github = new TokenPest("https://api.github.com", (string) $secrets->github->token);
$github->addHeader('User-Agent', 'github-presenter');
