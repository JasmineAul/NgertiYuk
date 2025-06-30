<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Halaman Materi</title>
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

    .content {
      padding: 30px 20px;
      text-align: center;
    }

    .materi-title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .materi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* Maksimal 4 kolom */
        gap: 20px;
        padding: 20px;
        max-height: 70vh; /* Atur tinggi maksimal */
        overflow-y: auto; /* Scroll vertikal jika konten terlalu banyak */
    }

    .materi-card {
        background-color: #8AAEE0;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        height: 150px; /* Tetapkan tinggi tetap */
    }

    .materi-card .icon {
      font-size: 60px;
      margin-bottom: 10px;
      margin-top: 10px;
      font-weight: bold;
    }

    .materi-card button {
      background-color: #ffffff;
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      font-weight: bold;
      cursor: pointer;
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

  <div class="content">
    <h2 class="materi-title">MATERI</h2>
    <div class="materi-grid">
      <div class="materi-card">
        <div class="icon">📄</div>
        <button onclick="location.href='klasifikasi_pdf.php'">PDF</button>
      </div>
      <div class="materi-card">
        <div class="icon">🎥<br></div>
        <button onclick="location.href='klasifikasi_mp4.php'">Video</button>
      </div>
      <div class="materi-card">
        <div class="icon">📽️</div>
        <button onclick="location.href='klasifikasi_pptx.php'">PPT</button>
      </div>
      <div class="materi-card">
        <div class="icon">🎵<br></div>
        <button onclick="location.href='klasifikasi_mp3.php'">MP3</button>
      </div>
    </div>
  </div>
</body>
</html>