<?php
include(dirname(__FILE__) . '/../autoload.php');

$github = new GitHub($config['githubAPI']['OAuth']['id'], $config['githubAPI']['OAuth']['secret']);

$repoCache = RepoCacheFactory::create($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);

try {
    // Fetching new GitHub repositories until it reaches the rate limit of the GitHub API
    $randomRepoList = array();
    
    // Does multiple fetches to get more repositories
    for ($i = 0;  $i < 5; $i++) {
        $randomRepoList = array_merge($randomRepoList, $github->getRandomRepoList(true));
    }
} catch (GitHub_RateLimitException $e) {
    /* Handles gracefully the case when the script goes over the rate limit of
     * the GitHub API. Nothing to do here since it is expected.
     */
}

// Locks the database to prevent random.php from accessing the repository cache while the ranks are not assigned yet
$repoCache->lock();

/* Does not check for duplicates since there's almost no chance
 * that a repository is randomly selected more than one time
 */
$repoCache->storeRepoList($randomRepoList);

// Freeing memory
unset($randomRepoList);

// Randomly removes repositories from the cache to keep their count under RepoCache::MAX_REPOCACHE_SIZE
$repoOverflowCount = $repoCache->count() - RepoCache::MAX_REPOCACHE_SIZE;
if ($repoOverflowCount > 0) {
    // Deletes more than just the overflow to prevent repositories from staying for ever in the cache
    $repoCache->randomRemove($repoOverflowCount + ceil(RepoCache::MAX_REPOCACHE_SIZE / 4));
    
    // Assigns a rank to every repository (used in RepoCache::randomRepo())
    $repoCache->giveRanks();
}

exit('Success');