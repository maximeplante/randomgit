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
    /* You need to register an application at
     * https://github.com/settings/applications/new
     * to get an OAuth client id and secret.
     */
    'githubAPI' => array(
        'OAuth' => array(
            'id' => '',
            'secret' => ''
        )
    )
);