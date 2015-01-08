<?php
include(dirname(__FILE__) . '/../../Classes/Repo.php');
include(dirname(__FILE__) . '/../../Classes/RepoCache.php');
$config = include(dirname(__FILE__) . '/../../config.php');

if ($config['debug']) {
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

try {
    $repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);
    
    /* For the language filter.
     * Removes every programming language with less than 25 repositories using it as their main language
     * to prevent random.php from always returning the same repositories.
     */
    $langList = $repoCache->langList(25);
    
    http_response_code(200);
    
    header('Content-Type: application/json');
    
    echo json_encode($langList);
    
} catch (Exception $e) {
    http_response_code(500);
    throw $e;
}