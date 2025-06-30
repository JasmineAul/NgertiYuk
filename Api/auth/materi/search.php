<?php
require_once("../../koneksi_db.php");
require_once("../auth/middleware.php");
header('Content-Type: application/json');

$keyword = $_GET['keyword'] ?? '';
if (!$keyword) {
    echo json_encode(["status" => "error", "message" => "Kata kunci tidak boleh kosong"]);
    exit;
}

$stmt = $mysql->prepare("SELECT * FROM materi WHERE title LIKE ?");
$param = "%$keyword%";
$stmt->bind_param("s", $param);
$stmt->execute();
$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $row['file_url'] = "http://localhost/ngerti_yuk/uploads/" . $row['file'];
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
