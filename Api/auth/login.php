<?php
require_once("../../koneksi_db.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? $_POST['username'] ?? '';
$password = $data['password'] ?? $_POST['password'] ?? '';


if (!$username || !$password) {
    echo json_encode(["status" => "error", "message" => "Username dan password wajib diisi"]);
    exit;
}

$stmt = $mysql->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $token = bin2hex(random_bytes(32));

        // Simpan token ke database
        $updateStmt = $mysql->prepare("UPDATE users SET token = ? WHERE id = ?");
        $updateStmt->bind_param("si", $token, $user['id']);
        $updateStmt->execute();

        echo json_encode([
            "status" => "success",
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "role" => $user['role']
            ]
        ]);

    } else {
        echo json_encode(["status" => "error", "message" => "Password salah"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
}
