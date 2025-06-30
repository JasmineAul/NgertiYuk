<?php
require_once("../../koneksi_db.php");
require_once("../auth/middleware.php"); // Validasi token
header('Content-Type: application/json');

// Hanya method PUT atau POST diizinkan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
    exit;
}

// Validasi role
if (AUTH_USER_ROLE !== 'operator') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Hanya operator yang diizinkan mengedit materi']);
    exit;
}

// Ambil data dari form (form-data atau x-www-form-urlencoded)
$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$description = $_POST['description'] ?? '';

if (!$id || !$title || !$kategori || !$description) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Update data
$stmt = $mysql->prepare("UPDATE materi SET title = ?, kategori = ?, description = ? WHERE id = ?");
$stmt->bind_param("sssi", $title, $kategori, $description, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Materi berhasil diperbarui']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui materi', 'error' => $stmt->error]);
}
