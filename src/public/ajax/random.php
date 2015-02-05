<?php
include(dirname(__FILE__) . '/../../autoload.php');

$language = null;
// Checking if a language filter is specified
if (isset($_GET['lang'])) {
    $language = $_GET['lang'];
}

$limit = 1;
// Checking if the client wants more than one repository (max 10)
if (isset($_GET['limit']) && intval($_GET['limit']) > 0) {
    if (intval($_GET['limit']) > 10) {
        $limit = 10;
    } else {
        $limit = intval($_GET['limit']);
    }
}

try {
    $repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);
    
    $repoList = $repoCache->randomRepo($limit, $language);
    
    http_response_code(200);
    
    header('Content-Type: application/json');
    
    // Sends the json data to the client
    echo json_encode(API::convertRepoArray($repoList));
    
} catch (Exception $e) {
    http_response_code(500);
    throw $e;
}