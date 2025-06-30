<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header("Location: login.php");
    exit();
}

require 'koneksi_db.php';

if (!isset($_GET['id'])) {
    echo "ID materi tidak ditemukan.";
    exit();
}

$id = intval($_GET['id']);
$result = $mysql->query("SELECT * FROM materi WHERE id = $id");
$materi = $result->fetch_assoc();

if (!$materi) {
    echo "Data materi tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $file_updated = false;

    // Cek jika ada file baru diunggah
    if (isset($_FILES['materi_file']) && $_FILES['materi_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['materi_file'];
        $file_name = basename($file['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = [
            'pdf' => ['pdf'],
            'pptx' => ['pptx'],
            'mp4' => ['mp4'],
            'mp3' => ['mp3']
        ];

        if (in_array($file_ext, $allowed_extensions[$kategori])) {
            $target_dir = "uploads/";
            $unique_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $file_name);
            $target_file = $target_dir . $unique_name;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Hapus file lama
                if (!empty($materi['filename']) && file_exists("uploads/" . $materi['filename'])) {
                    unlink("uploads/" . $materi['filename']);
                }

                $materi['filename'] = $unique_name;
                $file_updated = true;
            }
        }
    }

    // Update database
    if ($file_updated) {
        $stmt = $mysql->prepare("UPDATE materi SET title=?, kategori=?, description=?, filename=? WHERE id=?");
        $stmt->bind_param("ssssi", $judul, $kategori, $deskripsi, $materi['filename'], $id);
    } else {
        $stmt = $mysql->prepare("UPDATE materi SET title=?, kategori=?, description=? WHERE id=?");
        $stmt->bind_param("sssi", $judul, $kategori, $deskripsi, $id);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: kategori_materi_operator.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Materi</title>
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

    <h2>Edit Materi</h2>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <label for="judul">Judul Materi:</label>
            <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($materi['title']) ?>" required>

            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
                <option value="pdf" <?= $materi['kategori'] === 'pdf' ? 'selected' : '' ?>>PDF</option>
                <option value="pptx" <?= $materi['kategori'] === 'pptx' ? 'selected' : '' ?>>PPTX</option>
                <option value="mp4" <?= $materi['kategori'] === 'mp4' ? 'selected' : '' ?>>MP4</option>
                <option value="mp3" <?= $materi['kategori'] === 'mp3' ? 'selected' : '' ?>>MP3</option>
            </select>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" rows="4"><?= htmlspecialchars($materi['description']) ?></textarea>

            <label for="materi_file">File</label>
            <input type="file" name="materi_file" id="materi_file">

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>