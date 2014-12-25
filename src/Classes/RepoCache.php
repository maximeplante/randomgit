<?php

class DatabaseException extends RuntimeException { }

class DatabaseConnectionException extends DatabaseException { }

class DatabaseQueryException extends DatabaseException { }

class RepoCache
{
    private $host;
    private $user;
    private $pass;
    private $dbName;
    
    private $db;
    
    private $cachedPreparedStatement = null;
    
    function __construct($host, $user, $pass, $dbName)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbName = $dbName;
        
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
            $this->db = new PDO($dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage() . ':' . $e->getCode(), 0);
        }
    }
    
    public function randomRepo()
    {
        $randomRank = mt_rand(1, $this->count());
        $query = $this->prepareStatement('SELECT * FROM repo_list WHERE rank=?');
        if (!$query->execute(array($randomRank))) {
            throw new DatabaseQueryException('Unable to get a random repository from the cache', 0);
        }
        if (!$repoAssocArray = $query->fetch()) {
            throw new DatabaseQueryException('Failed to fetch the repository data', 0);
        }
        return new Repo($repoAssocArray['id'], $repoAssocArray['url']);
    }
    
    public function storeRepo(Repo $repo)
    {
        $query = $this->prepareStatement('INSERT INTO repo_list (id, url) VALUES (?, ?)');
        if (!$query->execute(array($repo->getId(), $repo->getUrl()))) {
            throw new DatabaseQueryException('Failed to save a repository to the cache', 0);
        }
    }
    
    public function clear()
    {
        $query = $this->prepareStatement('TRUNCATE TABLE repo_list');
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to truncate the table repo_list');
        }
    }
    
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
    
    public function count()
    {
        $query = $this->prepareStatement('SELECT * FROM repo_list');
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to select every element from repo_list.');
        }
        return $query->rowCount();
    }
    
    public function giveRanks()
    {
        $query = $this->prepareStatement('SET @i = 0; UPDATE repo_list SET rank=(@i:=@i+1);');
        if (!$query->execute()) {
            throw new DatabaseQueryException('Failed to set a rank to the element of repo_list.');
        }
    }
    
    private function prepareStatement($stmt)
    {
        if ($this->cachedPreparedStatement !== null && $stmt == $this->cachedPreparedStatement->queryString) {
            return $this->cachedPreparedStatement;
        } else {
            return $this->db->prepare($stmt);
        }
    }
}