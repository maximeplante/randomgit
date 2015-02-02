<?php
include(dirname(__FILE__) . '/../../autoload.php');

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