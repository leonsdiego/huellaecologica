<?php

exec('tar -czf www_zend.tar.gz www_zend && rm -rf www_zend && cp -r dev_zend www_zend && cp -r www/* . && cp -r www/.* .', $output);
echo '<pre>Output: ' . printf($output) . '</pre>';
file_put_contents('index.php',
    str_replace('../application', 'www_zend/application',
        file_get_contents('index.php')));

echo 'Migración exitosa';