<?php
session_start();

// Cek apakah user sudah login dan berperan sebagai operator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header("Location: login.php");
    exit();
}

$jumlahMateri = 0;
$token = $_SESSION['token'] ?? '';

// Ambil data materi dari API
if ($token) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost/ngertiyuk/api/materi/list.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-Access-Token: $token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($data && isset($data['status']) && $data['status'] === 'success') {
        $jumlahMateri = count($data['data']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dasbor Operator</title>
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

        .card-container {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #8AAEE0;
            padding: 50px 50px;
            border-radius: 12px;
            width: 160px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(0,0,0,0.15);
            cursor: pointer;
        }

        .card img {
            width: 50px;
            height: 50px;
            margin-bottom: 12px;
        }

        .card p {
            font-size: 15px;
            font-weight: 600;
            color: #000;
            margin: 0;
        }

        .bottom-link {
            margin-top: 50px;
            margin-right: 20px;
            text-align: right;
            font-size: 14px;
        }

        .bottom-link a {
            text-decoration: none;
            color: #3f6199;
            transition: color 0.3s;
        }

        .bottom-link a:hover {
            color: #2e4c77;
            
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

    <h2>Selamat Datang, Operator!</h2>

    <div class="card-container">
        <div class="card" onclick="location.href='form_tambah_materi.php'">
            <img src="https://img.icons8.com/ios-filled/50/000000/add-file.png" alt="Tambah Materi">
            <p>Tambah Materi</p>
        </div>
        <div class="card" onclick="location.href='kategori_materi_operator.php'">
            <img src="https://img.icons8.com/ios-filled/50/000000/books.png" alt="Daftar Materi">
            <p>Daftar Materi</p>
        </div>
    </div>

    <div class="bottom-link">
        <a href="login.php">View as Student</a>
    </div>

</body>
</html>
