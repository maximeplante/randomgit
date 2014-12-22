<?php

class ConnectionException extends RuntimeException { }

class GitHubAPIException extends RuntimeException { }

class GitHubAPIRateLimitException extends GitHubAPIException { }

class GitHub
{
    private $userAgent = 'RandomGit';
    
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
    
    public function getRandomRepoList()
    {
        return GitHub::searchRepo(Helper::randomAlphaNumString(2));
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
            $repo = new Repo($rawRepo->id, $rawRepo->html_url);
            array_push($repoList, $repo);
        }
        
        return $repoList;
    }
}