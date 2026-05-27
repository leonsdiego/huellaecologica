<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
echo '<pre>';
echo 'PHP version: ' . PHP_VERSION . "\n";
echo 'APPLICATION_PATH: ' . realpath(dirname(__FILE__) . '/www_zend/application') . "\n";
echo 'Library path: ' . realpath(dirname(__FILE__) . '/www_zend/library') . "\n";
$zend = realpath(dirname(__FILE__) . '/www_zend/library/Zend/Application.php');
echo 'Zend/Application.php exists: ' . ($zend ? 'YES' : 'NO') . "\n";
$htaccess = file_get_contents('.htaccess');
echo '.htaccess contents: ' . $htaccess . "\n";
echo 'APPLICATION_ENV from server: ' . (getenv('APPLICATION_ENV') ?: 'not set') . "\n";
echo '</pre>';
