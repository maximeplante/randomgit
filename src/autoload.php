<?php
function autoloader($class)
{
    $includePaths = array(
        'lib',
        'vendor'
    );
    
    foreach ($includePaths as $path) {
        $filePath = dirname(__FILE__) . '/' . $path . '/' . $class . '.php';
        
        if (file_exists($filePath)) {
            require_once($filePath);
            return true;
        }
    }
    
    return false;
}

spl_autoload_register('autoloader');

// The configuration file is accessible from everywhere
$config = include(dirname(__FILE__) . '/config.php');

// Support of the debug mode
if ($config['debug']) {
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

set_exception_handler(function($exception) {
    http_response_code(500);
    error_log($exception);
    
    /* If the website is in debug mode, rethrow the exception to print it on the
     * webpage.
     */
    if ($config['debug']) {
        throw $e;
    }
});