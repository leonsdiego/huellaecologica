<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo '<pre>';

// Try to replicate what index.php does and catch the real error
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
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo '</pre>';
