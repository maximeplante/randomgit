<?php

class DatabaseException extends RuntimeException { }

class DatabaseConnectionException extends DatabaseException { }

class DatabaseQueryException extends DatabaseException { }

// The repository cache is stored in the table 'repo_list' (see setup/schema.sql)
class RepoCache
{
    // Maximum number of repositories in the cache
    const MAX_REPOCACHE_SIZE = 100000;
    
    private $db;
    
    // Contains the last PDOStatement used (see RepoCache::prepareStatement())
    private $cachedPreparedStatement = null;
    
    function __construct($host, $user, $pass, $dbName)
    {
        try {
            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbName;
            $this->db = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage() . ':' . $e->getCode(), 0);
        }
    }
    
    /* Returns a random repository.
     * If $language (string) is specified, it will only return repositories having this language as main programming language¸
     */
    public function randomRepo($language = null)
    {
        // If no language specified, use the optimized randomization
        if ($language === null) {
            $randomRank = mt_rand(1, $this->count());
            
            $query = $this->prepareStatement('SELECT * FROM repo_list WHERE rank=?');
            if (!$query->execute(array($randomRank))) {
                throw new DatabaseQueryException('Unable to get a random repository from the cache', 0);
            }
        // If a language is specified, use 'ORDER BY RAND()' since the list of repositories is smaller
        } else {
            
            $query = $this->prepareStatement('SELECT * FROM repo_list WHERE lang=? ORDER BY RAND() LIMIT 1');
            if (!$query->execute(array($language))) {
                throw new DatabaseQueryException('Unable to get a random repository from the cache using a language filter.', 0);
            }
            
        }
        
        if (!$repoAssocArray = $query->fetch()) {
            throw new DatabaseQueryException('Failed to fetch the data of the repository.', 0);
        }
        return new Repo($repoAssocArray['id'], $repoAssocArray['name'], $repoAssocArray['user'], $repoAssocArray['lang'], $repoAssocArray['readme_html']);
    }
    
    public function storeRepo(Repo $repo)
    {
        $query = $this->prepareStatement('INSERT INTO repo_list (id, name, user, lang, readme_html) VALUES (?, ?, ?, ?, ?)');
        if (!$query->execute(array($repo->getId(), $repo->getName(), $repo->getUser(), $repo->getLang(), $repo->getReadmeHTML()))) {
            throw new DatabaseQueryException('Failed to save a repository to the cache', 0);
        }
    }
    
    // Empties the respository cache
    public function clear()
    {
        $query = $this->prepareStatement('TRUNCATE TABLE repo_list');
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to truncate the table repo_list');
        }
    }
    
    // Removes randomly $count repos from the RepoCache
    public function randomRemove($count)
    {
        // Parameters can't be used with LIMIT because they are considered as text, using intval() as a filter for $count
        $query = $this->prepareStatement('DELETE FROM repo_list ORDER BY RAND() LIMIT ' . intval($count));
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to remove random repos from repo_list.');
        }
    }
    
    // Checks if a repository is already in the cache
    public function isCached(Repo $repo)
    {
        $query = $this->prepareStatement('SELECT * FROM repo_list WHERE id=?');
        if (!$query->execute(array($repo->getId()))) {
            throw new DatabaseQueryException('Failed to query the cache for a specific repository', 0);
        }
        if ($query->fetch()) {
            return true;
        }
        return false;
    }
    
    // Returns the number of repositories in the cache
    public function count()
    {
        $query = $this->prepareStatement('SELECT COUNT(*) AS total FROM repo_list');
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to count the number of entries in repo_list.');
        }
        if (!$result = $query->fetch()) {
            throw new DatabaseQueryException('Failed fetch the number of entries in repo_list.');
        }
        return $result['total'];
    }
    
    /* Give a rank number (continuous) to every repository in the cache.
     * This rank is used in RepoCache::randomRepo() to optimize the randomization.
     */
    public function giveRanks()
    {
        $query = $this->prepareStatement('SET @i = 0; UPDATE repo_list SET rank=(@i:=@i+1);');
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to set a rank to the element of repo_list.');
        }
    }
    
    /* Returns the list string[] of every single programming language used in the repositories of the cache
     * Do not count repositories without any language set.
     * It removes every language with less than $minOccurences repositories using it as their main programming language (default value = 0)
     */
    public function langList($minOccurences = 0)
    {
        $query = $this->prepareStatement('SELECT lang FROM repo_list GROUP BY lang HAVING COUNT(lang) >= ?;');
        if (!$query->execute(array($minOccurences))) {
            throw new DatabaseQueryException('Failed to find every programming language in repo_list.');
        }
        
        $langList = array();
        while ($result = $query->fetch()) {
            array_push($langList, $result['lang']);
        }
        
        /* If there's at least one repository without any language defined,
         * the first element of the array will be null.
         * Removing the null element from the list.
         */
        if ($langList[0] === null) {
            array_shift($langList);
        }
        
        return $langList;
    }
    
    // Locks repo_list (read/write) until this RepoCache instance is destroyed
    public function lock()
    {
        $query = $this->prepareStatement("LOCK TABLES repo_list WRITE;");
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to lock repo_list.');
        }
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