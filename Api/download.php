<?php
$filename = basename($_GET['file'] ?? '');

$path = "../uploads/$filename";
if (!file_exists($path)) {
    http_response_code(404);
    echo "File tidak ditemukan.";
    exit;
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
readfile($path);
exit;
