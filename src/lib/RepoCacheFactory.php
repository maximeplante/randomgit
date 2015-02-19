<?php

class RepoCacheFactory
{
    static function create($host, $user, $pass, $dbName)
    {
        try {
            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbName;
            $db = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new RuntimeException('Unable to connect to the database. Please check if config.php is properly set.', 0, $e);
        }
        
        return new RepoCache($db);
    }
}