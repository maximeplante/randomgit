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
    
    $repoList = array();
    
    // Fills the array with the data of the repositories
    for ($i = 0; $i < $limit; $i++) {
        
        $randomRepo = $repoCache->randomRepo($language);
        
        $repo = array(
            'name' => $randomRepo->getName(),
            'user' => $randomRepo->getUser(),
            'description' => $randomRepo->getDescription(),
            'url' => $randomRepo->getUrl(),
            'lang' => $randomRepo->getLang(),
            'readme_html' => $randomRepo->getReadmeHTML()
        );
        
        array_push($repoList, $repo);
        
    }
    
    http_response_code(200);
    
    header('Content-Type: application/json');
    
    // Sends the json data to the client
    echo json_encode($repoList);
    
} catch (Exception $e) {
    http_response_code(500);
    throw $e;
}