<?php

class ConnectionException extends RuntimeException { }

class GitHubAPIException extends RuntimeException { }

class GitHubAPIRateLimitException extends GitHubAPIException { }

class GitHub
{
    // The GitHub API requires a user-agent
    private $userAgent = 'RandomGit/1.0 (maxime_dev@outlook.com)';
    
    // Repositories with a number of stars below minStars are considered to be "not interesting"
    private $minStars = 5;
    
    function __construct()
    {
        Requests::register_autoloader();
    }
    
    public function getRandomRepo()
    {
        $randomRepoList = GitHub::getRandomRepoList();
        $randomIndex = rand(0, count($randomRepoList));
        
        return $randomRepoList[$randomIndex];
    }
    
    /* If $interestingOnly is set to true, every repository with a number of
     * stars less than $minStars will be omitted.
     * The default value of $interestingOnly is false.
     */
    public function getRandomRepoList($interestingOnly = false)
    {
        $query = Helper::randomAlphaNumString(2);
        // When interestingOnly = true, only search for random repositories with more stars than minStars (reduces the number of uninteresting repositories)
        if ($interestingOnly) {
            $query .= ' stars:>=' . $this->minStars;
        }
        return GitHub::searchRepo($query);
    }
    
    // Returns an array of repositories matching the search query
    public function searchRepo($query)
    {
        $headers = array('User-Agent' => $this->userAgent);
        
        try {
            $response = Requests::get('https://api.github.com/search/repositories?q=' . urlencode($query), $headers);
        } catch (Requests_Exception $e) {
            throw new ConnectionException('Unable to reach the GitHub API', 0);
        }
        
        if ($response->status_code == 403) {
            throw new GitHubAPIRateLimitException('Rate limit fo the GitHub API is exceeded', 0);
        } else if(!$response->success) {
            throw new GitHubAPIException('The GitHub API encountered an error. Raw response body : ' . $response->body, 0);
        }
        
        $rawRepoList = json_decode($response->body);
        
        $repoList = array();
        
        foreach ($rawRepoList->items as $rawRepo) {
            $repo = new Repo($rawRepo->id, $rawRepo->html_url, $rawRepo->language);
            array_push($repoList, $repo);
        }
        
        return $repoList;
    }
}