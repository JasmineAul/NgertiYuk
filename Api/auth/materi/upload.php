<?php
require_once("../../koneksi_db.php");
require_once("../auth/middleware.php");
header('Content-Type: application/json');

// Cek metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
    exit;
}

// Hanya operator yang boleh upload
if (AUTH_USER_ROLE !== 'operator') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Hanya operator yang diizinkan mengunggah']);
    exit;
}

// Ambil inputan
$title = $_POST['title'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$description = $_POST['description'] ?? '';
$uploadedFile = $_FILES['filename'] ?? null;

// Validasi input
if (!$title || !$kategori || !$description || !$uploadedFile) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Upload file ke folder uploads/
$uploadDir = "../../uploads/";
$fileName = time() . "_" . basename($uploadedFile['name']);
$targetFile = $uploadDir . $fileName;

if (!move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file']);
    exit;
}

// Simpan ke database
$stmt = $mysql->prepare("INSERT INTO materi (title, kategori, description, filename, uploaded_by) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $title, $kategori, $description, $fileName, $userId);

$userId = AUTH_USER_ID; // harus setelah bind_param

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'File dan data materi berhasil diunggah']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan ke database',
        'error' => $stmt->error
    ]);
}
