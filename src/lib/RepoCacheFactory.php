<?php

class RepoCacheFactory
{
    static function create($host, $user, $pass, $dbName)
    {
        try {
            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbName;
            $db = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException('Unable to connect to the database. Please check if config.php is properly set. Error : ' . $e->getMessage() . '(' . $e->getCode() . ')', 0);
        }
        
        return new RepoCache($db);
    }
}