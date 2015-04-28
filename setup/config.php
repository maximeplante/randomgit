<?php
// Example of the structure of config.php needed (src/config.php)

return array(
    'db' => array(
        'host' => '',
        'user' => '',
        'pass' => '',
        'dbName' => ''
    ),
    
    // Tells if the website is in debug mode
    'debug' => true,
    
    /*
     * You need to register an application at
     * https://github.com/settings/applications/new
     * to get an OAuth client id and secret.
     * 
     * For development purposes, this can be left empty.
     * It will instead use the public github API. The only
     * downside is that less repos will be fetched during
     * one query of job/updateCache.php.
     */
    'githubAPI' => array(
        'OAuth' => array(
            'id' => '',
            'secret' => ''
        )
    ),
    
    'repoCache' => array(
        // Maximum number of repositories in the cache
        'maxSize' => 10000,
        /* Does not list languages with less than a [minLang] number of
         * repositories using this language as their main language.
         */
        'minLang' => 50,
    ),
);