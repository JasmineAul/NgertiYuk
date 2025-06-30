<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header("Location: login.php");
    exit();
}

require 'koneksi_db.php';

if (!isset($_GET['id'])) {
    echo "ID materi tidak ditemukan.";
    exit();
}

$id = intval($_GET['id']);

// Cek apakah materi ada
$cek = $mysql->query("SELECT filename FROM materi WHERE id = $id");
if ($cek->num_rows === 0) {
    echo "Materi tidak ditemukan.";
    exit();
}

$data = $cek->fetch_assoc();
$filename = $data['filename'];

// Hapus file fisik jika ada
if (!empty($filename) && file_exists('uploads/' . $filename)) {
    unlink('uploads/' . $filename);
}

// Hapus dari database
$stmt = $mysql->prepare("DELETE FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: kategori_materi_operator.php");
exit();