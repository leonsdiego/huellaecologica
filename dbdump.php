<?php
// One-time DB dump script — delete after use
$secret = 'dump-huellaec-2026';
if (($_GET['token'] ?? '') !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

$host = 'localhost';
$db   = 'huellaec_www';
$user = 'huellaec_www';
$pass = 'P=6%{JCX$XEr';

$filename = 'huellaec_www_full_' . date('Ymd_His') . '.sql.gz';

header('Content-Type: application/gzip');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$cmd = sprintf(
    'mysqldump --host=%s --user=%s --password=%s --single-transaction --no-tablespaces %s | gzip',
    escapeshellarg($host),
    escapeshellarg($user),
    escapeshellarg($pass),
    escapeshellarg($db)
);

passthru($cmd);
