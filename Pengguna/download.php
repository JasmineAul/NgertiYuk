<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pengguna') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID tidak disediakan.";
    exit();
}

require '../koneksi_db.php';

$id = intval($_GET['id']);
$sql = "SELECT filename FROM materi WHERE id = $id";
$result = $mysql->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $file = basename($row['filename']);
    $filepath = __DIR__ . '/../uploads/' . $file;

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush();
        readfile($filepath);
        exit;
    } else {
        echo "File tidak ditemukan di server.";
    }
} else {
    echo "Data materi tidak ditemukan.";
}