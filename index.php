<?php

// Define path to application directory
// Constante utilizada para saber dónde se encuetran las configuraciones, controladores, etc.
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/www_zend/application'));
// Define application environment

if($_SERVER['HTTP_HOST'] == 'www.huellaecologica.com.ve' || $_SERVER['HTTP_HOST'] == 'huellaecologica.com.ve' ){
    define('APPLICATION_ENV', 'production');
}
else{
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