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