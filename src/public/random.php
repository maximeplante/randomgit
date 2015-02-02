<?php
include(dirname(__FILE__) . '/../autoload.php');

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