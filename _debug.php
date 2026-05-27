<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Catch trigger_error(E_USER_ERROR) and die() calls
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "\n[ERROR HANDLER] #$errno: $errstr\n  in $errfile:$errline\n";
    echo debug_string_backtrace();
    return true; // prevent default handler
});

function debug_string_backtrace() {
    ob_start();
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    return ob_get_clean();
}

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "\n[FATAL SHUTDOWN] " . $error['message'] . "\n";
        echo "  in " . $error['file'] . ":" . $error['line'] . "\n";
    } else {
        echo "\n[SHUTDOWN] Script ended " . ($error ? "with: " . print_r($error, true) : "cleanly") . "\n";
    }
});

echo '<pre>';

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/www_zend/application'));
define('APPLICATION_ENV', 'production');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

try {
    require_once 'Zend/Loader/Autoloader.php';
    echo "Autoloader loaded OK\n";
    Zend_Loader_Autoloader::getInstance()->setFallbackAutoLoader(true);

    require_once 'Zend/Application.php';
    echo "Zend_Application loaded OK\n";

    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );
    echo "Application created OK\n";

    $application->bootstrap();
    echo "Bootstrap OK\n";

} catch (\Throwable $e) {
    echo "\n[CAUGHT] " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "  in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo '</pre>';
