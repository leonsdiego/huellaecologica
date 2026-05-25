<?php

exec('tail -n 30 /usr/local/apache/logs/error_log', $output);
echo '<pre>' . print_r($output, true) . '</pre> Migración exitosa';