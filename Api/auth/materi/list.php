<?php
require_once("../../koneksi_db.php");
require_once("../auth/middleware.php"); // Middleware token
header('Content-Type: application/json');

// Ambil semua data materi
$query = "SELECT id, title, kategori, filename FROM materi";
$result = $mysql->query($query);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "kategori" => $row['kategori'],
            "file_url" => "http://localhost/ngerti_yuk/uploads/" . $row['filename']
        ];
    }

    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}
