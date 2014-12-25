<?php

include(dirname(__FILE__) . '/../Classes/Repo.php');
include(dirname(__FILE__) . '/../Classes/RepoCache.php');
$config = include(dirname(__FILE__) . '/../config.php');

ini_set('display_errors', 'Off');
error_reporting(E_ALL | E_STRICT);

try {
    $repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);
    
    $randomRepo = $repoCache->randomRepo();
    header('location: ' . $randomRepo->getUrl());
} catch (Exception $e) {
    echo 'Something wrong happened';
    throw $e;
}