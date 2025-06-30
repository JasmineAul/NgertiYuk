<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['operator', 'pengguna'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi_db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $mysql->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #F4F4F4;
        }

        .navbar {
            background-color: #2f4f80; /* Warna biru */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            color: white;
        }

        .navbar .logo img {
            height: 40px;
        }

        .navbar .menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar .menu a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .navbar .menu a:hover {
            text-decoration: underline;
        }

        .navbar .profile-icon img {
            height: 30px;
            width: 30px;
            border-radius: 50%;
            object-fit: cover;
            font-size: 24px;
            cursor: pointer;
        }

        h2 {
            text-align: center;
            margin: 50px 0 30px;
            color: #333;
            font-size: 24px;
        }

        .profile-container {
            background: #8AAEE0;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 350px;
            margin: 60px auto;
        }

        .profile-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #000;
        }

        .profile-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #eee;
            box-sizing: border-box;
        }

        .logout-btn {
            padding: 10px 30px;
            background: #395886;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            display: block;
            margin: 0 auto;
        }

        .logout-btn:hover {
            background: #28426a;
            cursor: pointer;
        }

        .header img {
            width: 30px;
            border-radius: 50%;
        }

        .header strong {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="Assets/logoo.png" alt="Logo" style="height: 40px;">
        </div>
        <div class="menu">
            <a href="dashboard_operator.php">Home</a>
            <a href="kategori_materi_operator.php">Materi</a>
            <div class="profile-icon">
                <a href="profile.php">
                <img src="assets/profile.jpg" alt="profile" style="height: 32px; border-radius: 50%;">
                </a>
            </div>
        </div>
    </div>

    <h2>Profil Pengguna</h2>

    <div class="profile-container">
        <label>Name:</label>
        <input type="text" value="<?= htmlspecialchars($username) ?>" disabled>

        <label>Account:</label>
        <input type="text" value="<?= htmlspecialchars($email) ?>" disabled>

        <form action="logout.php" method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</body>
</html>