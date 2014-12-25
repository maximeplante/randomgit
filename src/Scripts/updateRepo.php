<?php

include(dirname(__FILE__) . '/../Libs/Requests.php');
include(dirname(__FILE__) . '/../Classes/Helper.php');
include(dirname(__FILE__) . '/../Classes/Repo.php');
include(dirname(__FILE__) . '/../Classes/GitHub.php');
include(dirname(__FILE__) . '/../Classes/RepoCache.php');
$config = include(dirname(__FILE__) . '/../config.php');

ini_set('display_errors', 'Off');
error_reporting(E_ALL | E_STRICT);

$github = new GitHub();

$repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);

try {
    while (true) {
        $randomRepoList = $github->getRandomRepoList(true);
        
        foreach ($randomRepoList as $repo) {
            if (!$repoCache->isCached($repo)) {
                $repoCache->storeRepo($repo);
            }
        }
    }
} catch (GitHubAPIRateLimitException $e) {
    // Randomly removes repositories from the cache to keep their count under 100000
    $repoOverflowCount = $repoCache->count() - 100000;
    if ($repoOverflowCount > 0) {
        $repoCache->randomRemove($repoOverflowCount);
    }
    // Assigns a rank to every repository (used in RepoCache::randomRepo())
    $repoCache->giveRanks();
    exit('Success');
}