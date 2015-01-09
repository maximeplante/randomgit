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
     * For developpement purposes, this can be left empty.
     * It will instead use the public github API. The only
     * donwside is that less repos will be fetched at a time.
     */
    'githubAPI' => array(
        'OAuth' => array(
            'id' => '',
            'secret' => ''
        )
    )
);