<?php
require_once("../../koneksi_db.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$role = $data['role'] ?? 'pengguna';

// Validasi input wajib
if (!$username || !$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Validasi panjang password minimal 8 karakter
if (strlen($password) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'Password minimal 8 karakter']);
    exit;
}

// Validasi email sederhana
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid']);
    exit;
}

// Cek apakah username/email sudah ada
$stmt = $mysql->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username atau Email sudah digunakan']);
    exit;
}

// Hash password dan simpan
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$stmt = $mysql->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registrasi gagal']);
}
