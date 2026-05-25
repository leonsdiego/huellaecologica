<?php

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

// Define path to application directory
// Constante utilizada para saber d�nde se encuetran las configuraciones, controladores, etc.
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/www_zend/application'));
// Define application environment

if ($_SERVER['HTTP_HOST'] == 'www.huellaecologica.com.ve' || $_SERVER['HTTP_HOST'] == 'huellaecologica.com.ve') {
    define('APPLICATION_ENV', 'production');
} elseif ($_SERVER['HTTP_HOST'] == 'dev.huellaecologica.com.ve') {
    define('APPLICATION_ENV', 'staging');
} else {
    define('APPLICATION_ENV', 'development');
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(), //
)));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_autoloader::getInstance()->setFallbackAutoLoader(true);
/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();