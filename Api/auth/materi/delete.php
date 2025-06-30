<?php
require_once("../../koneksi_db.php");
require_once("../auth/middleware.php");
header('Content-Type: application/json');

// Ambil data dari input DELETE
parse_str(file_get_contents("php://input"), $data);
$id = $data['id'] ?? '';

if (!$id) {
    echo json_encode(["status" => "error", "message" => "ID wajib diisi"]);
    exit;
}

$stmt = $mysql->prepare("DELETE FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Materi berhasil dihapus"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menghapus materi"]);
}
