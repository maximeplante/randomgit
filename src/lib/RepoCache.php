<?php

// The repository cache is stored in the table 'repo_list' (see setup/schema.sql)
class RepoCache
{
    // Maximum number of repositories in the cache
    const MAX_REPOCACHE_SIZE = 4000;
    
    private $db;
    
    // Contains the last PDOStatement used (see RepoCache::prepareStatement())
    private $cachedPreparedStatement = null;
    
    function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /* Returns an array of random repositories.
     * Set $count to the number of repositories to return in the array
     * If $language (string) is specified, it will only return repositories having this language as main programming language¸
     */
    public function randomRepo($count = 1, $language = null)
    {
        $repoList = array();
        
        // If no language specified, use the optimized randomization
        if ($language === null) {
            
            $randomRank = mt_rand(1, $this->count());
            
            $query = $this->executeQuery('SELECT * FROM repo_list WHERE rank >= ? AND rank < (?+?)',
             array($randomRank, $randomRank, $count),
             'Unable to get a random repository from the cache'
            );
            
        // If a language is specified, use 'ORDER BY RAND()' since the list of repositories is smaller
        } else {
            
            // Check if the language exists in the database
            $query = $query = $this->executeQuery('SELECT COUNT(*) FROM repo_list WHERE lang=?',
             array($language),
             'Unable to count the number of occurrences of a language.'
            );
            
            // If the language does not exist, throw an exception
            if ($query->fetch()[0] < 1) {
                throw new RuntimeException('The language specified (' . $language . ') does not exist in the database.');
            }
            
            $query = $this->executeQuery('SELECT * FROM repo_list WHERE lang=? ORDER BY RAND() LIMIT ' . intval($count),
             array($language),
             'Unable to get a random repository from the cache using a language filter.'
            );
        }
        
        while ($repoAssocArray = $query->fetch()) {
            array_push($repoList,
                new Repo($repoAssocArray['id'], $repoAssocArray['name'], $repoAssocArray['description'], $repoAssocArray['user'], $repoAssocArray['lang'], $repoAssocArray['readme_html'])
            );
        }
        
        return $repoList;
    }
    
    public function storeRepo(Repo $repo)
    {
        $this->executeQuery('INSERT INTO repo_list (id, name, description, user, lang, readme_html) VALUES (?, ?, ?, ?, ?, ?)',
         array(
             $repo->getId(),
             $repo->getName(),
             $repo->getDescription(),
             $repo->getUser(),
             $repo->getLang(),
             $repo->getReadmeHTML()
         ),
         'Failed to save a repository to the cache'
        );
    }
    
    // Empties the respository cache
    public function clear()
    {
        $this->executeQuery('TRUNCATE TABLE repo_list',
         array(),
         'Failed to truncate the table repo_list'
        );
    }
    
    // Removes randomly $count repos from the RepoCache
    public function randomRemove($count)
    {
        // Parameters can't be used with LIMIT because they are considered as text, using intval() as a filter for $count
        $this->executeQuery('DELETE FROM repo_list ORDER BY RAND() LIMIT ' . intval($count),
         array(),
         'Failed to remove random repos from repo_list.'
        );
    }
    
    // Checks if a repository is already in the cache
    public function isCached(Repo $repo)
    {
        $query = $this->executeQuery('SELECT * FROM repo_list WHERE id=?',
         array($repo->getId()),
         'Failed to query the cache for a specific repository'
        );
        if ($query->fetch()) {
            return true;
        }
        return false;
    }
    
    // Returns the number of repositories in the cache
    public function count()
    {
        $query = $this->executeQuery('SELECT COUNT(*) AS total FROM repo_list',
         array(),
         'Failed to count the number of entries in repo_list.'
        );
        if (!$result = $query->fetch()) {
            throw new RuntimeException('Failed fetch the number of entries in repo_list.');
        }
        return $result['total'];
    }
    
    /* Give a rank number (continuous) to every repository in the cache.
     * This rank is used in RepoCache::randomRepo() to optimize the randomization.
     */
    public function giveRanks()
    {
        $this->executeQuery('ALTER TABLE repo_list DROP PRIMARY KEY;',
         array(),
         'Failed to remove the primary key'
        );
        
        $this->executeQuery('SET @i = 0; UPDATE repo_list SET rank=(@i:=@i+1);',
         array(),
         'Failed to set a rank to the element of repo_list.'
        );
        
        $this->executeQuery('ALTER TABLE repo_list ADD PRIMARY KEY (rank)',
         array(),
         'Failed to remove the primary key'
        );
    }
    
    /* Returns the list string[] of every single programming language used in the repositories of the cache
     * Do not count repositories without any language set.
     * It removes every language with less than $minOccurences repositories using it as their main programming language (default value = 0)
     */
    public function langList($minOccurences = 0)
    {
        $query = $this->executeQuery('SELECT lang FROM repo_list GROUP BY lang HAVING COUNT(lang) >= ?;',
         array($minOccurences),
         'Failed to find every programming language in repo_list.'
        );
        
        $langList = array();
        while ($result = $query->fetch()) {
            array_push($langList, $result['lang']);
        }
        
        /* If there's at least one repository without any language defined,
         * the first element of the array will be null.
         * Removing the null element from the list.
         */
        if (isset($langList[0]) && $langList[0] === null) {
            array_shift($langList);
        }
        
        return $langList;
    }
    
    // Locks repo_list (read/write) until this RepoCache instance is destroyed
    public function lock()
    {
        $this->executeQuery('LOCK TABLES repo_list WRITE;',
         array(),
         'Failed to lock repo_list.'
        );
    }
    
    /* Executes a MYSQL query.
     * string $queryString : the query string
     * array $parameters : the input parameters of the query
     * string $exceptionMsg : the message used if an exception is thrown
     * Returns the PDOStatement used for the query
     */
    private function executeQuery($queryString, $parameters, $exceptionMsg)
    {
        $query = $this->prepareStatement($queryString);
        
        if ($query->execute($parameters) === false) {
            throw new RuntimeException($exceptionMsg, 0);
        }
        
        return $query;
    }
    
    /* If the query string is the same as the last one, it uses the same PDOStatement instance.
     * It's more efficient than resending the same query string.
     */
    private function prepareStatement($stmt)
    {
        if ($this->cachedPreparedStatement !== null && $stmt == $this->cachedPreparedStatement->queryString) {
            return $this->cachedPreparedStatement;
        } else {
            return $this->db->prepare($stmt);
        }
    }
}