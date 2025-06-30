<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header("Location: login.php");
    exit();
}

require 'koneksi_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $uploaded_by = $_SESSION['user_id'];

    $file = $_FILES['materi_file'];
    $file_name = basename($file['name']);
    $target_dir = "uploads/";

    $unique_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $file_name);
    $target_file = $target_dir . $unique_name;

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Validasi ekstensi berdasarkan kategori
    $allowed_extensions = [
        'pdf' => ['pdf'],
        'pptx' => ['pptx'],
        'mp4' => ['mp4'],
        'mp3' => ['mp3']
    ];

    if (!in_array($file_ext, $allowed_extensions[$kategori])) {
        $error = "Tipe file <strong>$file_ext</strong> tidak sesuai dengan kategori <strong>$kategori</strong>.";
    }


    if (!isset($error) && move_uploaded_file($file['tmp_name'], $target_file)) {
        $stmt = $mysql->prepare("INSERT INTO materi (title, filename, description, kategori, uploaded_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi",  $judul, $unique_name, $deskripsi, $kategori, $uploaded_by);
        $stmt->execute();
        $stmt->close();

        switch ($kategori) {
            case 'pdf':
                header("Location: klasifikasi_pdf.php");
                break;
            case 'mp4':
                header("Location: klasifikasi_mp4.php");
                break;
            case 'pptx':
                header("Location: klasifikasi_pptx.php");
                break;
            case 'mp3':
                header("Location: klasifikasi_mp3.php");
                break;
            default:
                header("Location: kategori_materi_operator.php");
        }
        exit();
    } else {
        $error = "Gagal mengupload file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Materi</title>
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

        h2 {
            text-align: center;
            margin: 50px 0 30px;
            color: #333;
            font-size: 24px;
        }

        .container { 
            background-color: #8AAEE0;
            max-width: 600px;
            margin: 20px auto 40px;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
            color: #000;
        }
        input, 
        select, 
        textarea,
        input[type="file"] { 
            width: 100%; 
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: white;
            font-size: 14px; 
        }
        
        #kategori {
            width: 104%;
        }

        textarea {
            resize: vertical;
        }

        button { 
            background-color: #395886; 
            color: white; 
            padding: 10px 30px; 
            margin-top: 20px;
            align-self: flex-end;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover { 
            background-color: #2c4369; 
        }

        .error { 
            color: red;
            margin-bottom: 10px; 
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
    <h2>Upload Materi Baru</h2>   
    <div class="container">
    <form method="POST" enctype="multipart/form-data">
        <label for="judul">Judul Materi:</label>
        <input type="text" name="judul" id="judul" required>

        <label for="kategori">Kategori:</label>
        <select name="kategori" id="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="pdf">PDF</option>
            <option value="pptx">PPTX</option>
            <option value="mp4">MP4</option>
            <option value="mp3">MP3</option>
        </select>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" rows="4"></textarea>

        <label for="materi_file">Pilih File:</label>
        <input type="file" name="materi_file" id="materi_file" required>
        
        <?php if (isset($error)) echo "<p class='error'>{$error}</p>"; ?>

        <button type="submit">Upload</button>
    </form>
</div>
</body>
</html>