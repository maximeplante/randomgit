<?php

include(dirname(__FILE__) . '/../Libs/Requests.php');
include(dirname(__FILE__) . '/../Classes/Helper.php');
include(dirname(__FILE__) . '/../Classes/Repo.php');
include(dirname(__FILE__) . '/../Classes/GitHub.php');
include(dirname(__FILE__) . '/../Classes/RepoCache.php');
$config = include(dirname(__FILE__) . '/../config.php');

if ($config['debug']) {
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

$github = new GitHub();

$repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);

// Locks the database to prevent random.php from accessing the repository cache while the ranks are not assigned yet
$repoCache->lock();

try {
    // Fetching new GitHub repositories until it reaches the rate limit of the GitHub API
    while (true) {
        $randomRepoList = $github->getRandomRepoList(true);
        
        foreach ($randomRepoList as $repo) {
            // Do not add duplicates
            if (!$repoCache->isCached($repo)) {
                $repoCache->storeRepo($repo);
            }
        }
    }
} catch (GitHubAPIRateLimitException $e) {
    // Randomly removes repositories from the cache to keep their count under RepoCache::MAX_REPOCACHE_SIZE
    $repoOverflowCount = $repoCache->count() - RepoCache::MAX_REPOCACHE_SIZE;
    if ($repoOverflowCount > 0) {
        // Deletes more than just the overflow to prevent repositories from staying for ever in the cache
        $repoCache->randomRemove($repoOverflowCount + ceil(RepoCache::MAX_REPOCACHE_SIZE / 4));
    }
    // Assigns a rank to every repository (used in RepoCache::randomRepo())
    $repoCache->giveRanks();
    exit('Success');
}