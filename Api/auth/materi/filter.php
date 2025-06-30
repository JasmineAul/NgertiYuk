<?php
require_once("../../koneksi_db.php");
require_once("../auth/middleware.php");
header('Content-Type: application/json');

$kategori = $_GET['kategori'] ?? '';
if (!$kategori) {
    echo json_encode(["status" => "error", "message" => "Kategori harus diisi"]);
    exit;
}

$stmt = $mysql->prepare("SELECT * FROM materi WHERE kategori = ?");
$stmt->bind_param("s", $kategori);
$stmt->execute();
$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $row['file_url'] = "http://localhost/ngerti_yuk/uploads/" . $row['file'];
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
