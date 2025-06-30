<?php
require_once(__DIR__ . '/../../koneksi_db.php');
header('Content-Type: application/json');

$headers = getallheaders();
$token = $headers['X-Access-Token'] ?? '';

if (!$token) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Token tidak diberikan']);
    exit;
}

$stmt = $mysql->prepare("SELECT id, role FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid']);
    exit;
}

$user = $result->fetch_assoc();
define('AUTH_USER_ID', $user['id']);
define('AUTH_USER_ROLE', $user['role']);
