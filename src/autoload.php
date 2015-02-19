<?php
function autoloader($class)
{
    $includePaths = array(
        'lib',
        'vendor'
    );
    
    $class = str_replace('_', '/', $class);
    
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

// Checks if config.php exists.
if (!file_exists(dirname(__FILE__) . '/config.php')) {
    exit('Cannot find config.php');
}

// The configuration file is accessible from everywhere
$config = include(dirname(__FILE__) . '/config.php');

// Support of the debug mode
if ($config['debug']) {
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

// Sets the exception handler
set_exception_handler(function($exception) use (&$config) {
    http_response_code(500);
    
    /* If the website is in debug mode, rethrow the exception to print it on the
     * webpage.
     */
    if ($config['debug']) {
        throw $exception;
    } else {
        // Logs error only in release mode
        error_log($exception);
        echo 'Something wrong happened, try refreshing the page.';
    }
});