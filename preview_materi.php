<?php
session_start();
require 'koneksi_db.php';

// Cek apakah user sudah login dan memiliki role
if (!isset($_SESSION['token']) || !isset($_SESSION['role'])) {
    echo "Akses ditolak. Silakan login terlebih dahulu.";
    exit();
}

// Tetapkan role dari session
define("AUTH_USER_ROLE", $_SESSION['role']);

// Hanya izinkan role tertentu
if (AUTH_USER_ROLE !== 'operator' && AUTH_USER_ROLE !== 'pengguna') {
    echo "Akses ditolak. Role tidak diizinkan.";
    exit();
}

// Validasi ID materi
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID materi tidak ditemukan.";
    exit();
}

$id = intval($_GET['id']);
$stmt = $mysql->prepare("SELECT * FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$materi = $result->fetch_assoc();

if (!$materi) {
    echo "Materi tidak ditemukan.";
    exit();
}

$file_url = "uploads/" . htmlspecialchars($materi['filename']);
$kategori = strtolower($materi['kategori']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preview Materi - <?php echo htmlspecialchars($materi['title']); ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
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
            max-width:900px; 
            margin:30px auto; 
            background:#8AAEE0; 
            padding:20px; 
            border-radius:12px; 
            box-shadow:0 6px 20px rgba(0,0,0,0.1); 
        }
        h2 { text-align:center; margin-bottom:20px; }
        .preview-container { text-align:center; }
        iframe, video, audio { width:100%; max-height:600px; border:none; border-radius:8px; background:#f0f0f0; }
        audio { max-height:50px; }
        .back-button { margin-top:20px; display:inline-block; background:#395886; color:#fff; padding:10px 20px; text-decoration:none; border-radius:8px; font-weight:bold; }
        .back-button:hover { background:#2c4369; }
    </style>
</head>
<body>
<div class="navbar">
        <div class="logo">
            <img src="Assets/logoo.png" alt="Logo" style="height: 40px;">
        </div>
        <div class="menu">
            <a href="<?= (AUTH_USER_ROLE === 'operator') ? 'dashboard_operator.php' : 'pengguna/dashboard.php' ?>">Home</a>
            <a href="<?= (AUTH_USER_ROLE === 'operator') ? 'kategori_materi_operator.php' : 'pengguna/kategori_materi_pgn.php' ?>">Materi</a>
            <div class="profile-icon">
                <a href="profile.php">
                <img src="assets/profile.jpg" alt="profile" style="height: 32px; border-radius: 50%;">
                </a>
            </div>
        </div>
</div>

<div class="content">
    <h2>Materi: <?php echo htmlspecialchars($materi['title']); ?></h2>
    <div class="preview-container">
        <?php if ($kategori === 'pdf'): ?>
            <iframe src="<?php echo $file_url; ?>" style="height:600px;"></iframe>
        <?php elseif ($kategori === 'mp4'): ?>
            <video controls>
                <source src="<?php echo $file_url; ?>" type="video/mp4">
                Browser Anda tidak mendukung video tag.
            </video>
        <?php elseif ($kategori === 'mp3'): ?>
            <audio controls>
                <source src="<?php echo $file_url; ?>" type="audio/mpeg">
                Browser Anda tidak mendukung audio tag.
            </audio>
        <?php elseif ($kategori === 'pptx'): ?>
            <p>Preview PPTX tidak didukung secara langsung di browser.</p>
            <p>Silakan <a href="<?php echo $file_url; ?>" download>download file PPTX ini</a> untuk membuka di aplikasi PowerPoint Anda.</p>
        <?php else: ?>
            <p>Preview untuk tipe file ini tidak tersedia.</p>
        <?php endif; ?>
    </div>
    <?php $back_url = (AUTH_USER_ROLE === 'operator') ? 'kategori_materi_operator.php' : 'pengguna/kategori_materi_pgn.php';?>
    <a href="<?= $back_url ?>" class="back-button">Back</a>
</div>
</body>
</html>
