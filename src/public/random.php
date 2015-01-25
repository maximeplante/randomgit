<?php
include(dirname(__FILE__) . '/../lib/Repo.php');
include(dirname(__FILE__) . '/../lib/RepoCache.php');
$config = include(dirname(__FILE__) . '/../config.php');

if ($config['debug']) {
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

$language = null;
// Checking if a language filter is specified
if (isset($_GET['lang'])) {
    $language = $_GET['lang'];
}

try {
    $repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);
    
    $randomRepo = $repoCache->randomRepo($language);
    
    header('location: ' . $randomRepo->getUrl());
    
} catch (Exception $e) {
    http_response_code(500);
    throw $e;
}