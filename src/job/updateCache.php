<?php

include(dirname(__FILE__) . '/../vendor/Requests.php');
include(dirname(__FILE__) . '/../lib/Helper.php');
include(dirname(__FILE__) . '/../lib/Repo.php');
include(dirname(__FILE__) . '/../lib/GitHub.php');
include(dirname(__FILE__) . '/../lib/RepoCache.php');
$config = include(dirname(__FILE__) . '/../config.php');

if ($config['debug']){
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

$github = new GitHub($config['githubAPI']['OAuth']['id'], $config['githubAPI']['OAuth']['secret']);

$repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);

try {
    // Fetching new GitHub repositories until it reaches the rate limit of the GitHub API
    $randomRepoList = array();
    // Does multiple fetches to get more repositories
    for ($i = 0;  $i < 5; $i++) {
        $randomRepoList = array_merge($randomRepoList, $github->getRandomRepoList(true));
    }
} catch (GitHubAPIRateLimitException $e) {
    /* Handles gracefully the case when the script goes over the rate limit of
     * the GitHub API. Nothing to do here since it is expected.
     */
}

// Locks the database to prevent random.php from accessing the repository cache while the ranks are not assigned yet
$repoCache->lock();

// Stores the repositories in the cache
foreach ($randomRepoList as $repo) {
    // Do not add duplicates
    if (!$repoCache->isCached($repo)) {
        $repoCache->storeRepo($repo);
    }
}

// Randomly removes repositories from the cache to keep their count under RepoCache::MAX_REPOCACHE_SIZE
$repoOverflowCount = $repoCache->count() - RepoCache::MAX_REPOCACHE_SIZE;
if ($repoOverflowCount > 0) {
    // Deletes more than just the overflow to prevent repositories from staying for ever in the cache
    $repoCache->randomRemove($repoOverflowCount + ceil(RepoCache::MAX_REPOCACHE_SIZE / 4));
}
// Assigns a rank to every repository (used in RepoCache::randomRepo())
$repoCache->giveRanks();

exit('Success');