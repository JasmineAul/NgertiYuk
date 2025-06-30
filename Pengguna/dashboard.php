<?php
require '../koneksi_db.php';
session_start();


// Ambil input pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$kategori = mysqli_real_escape_string($mysql, $kategori);
$search = mysqli_real_escape_string($mysql, $search);

// Query dasar
$result = null; // default null, hanya eksekusi jika pencarian dilakukan
if (!empty($search) || !empty($kategori)) {
    $query = "SELECT * FROM materi WHERE 1";

    if (!empty($kategori)) {
        $query .= " AND kategori = '$kategori'";
    }

    if (!empty($search)) {
        $query .= " AND title LIKE '%$search%'";
    }

    $result = mysqli_query($mysql, $query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .navbar {
            background-color: #2f4f80; 
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
        .search-container {
            text-align: center;
            margin: 30px auto;
        }

        .search-container h2 {
            font-size: 28px;
            font-weight: bold;
        }

        .search-container p {
            font-weight: 600;
        }

        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .search-form input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 250px;
        }

        .search-form button {
            padding: 10px 15px;
            font-size: 18px;
            background-color: #3d5a97;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #2c4373;
        }

        .search-form select {
            padding: 10px;
            width: 200px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .material-list {
            margin-top: 30px;
            text-align: center;
        }

        .btn-action {
            text-decoration: none;
            background-color: white;
            color: black;
            font-weight: bold;
            padding: 6px 15px;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s;
            display: inline-block;
            text-align: center;
        }

        .btn-action:hover {
            background-color:#e6e8ee;
        }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <img src="../Assets/logoo.png" alt="Logo" style="height: 40px;">
        </div>
        <div class="menu">
            <a href="dashboard.php">Home</a>
            <a href="kategori_materi_pgn.php">Materi</a>
            <div class="profile-icon">
                <a href="../profile.php">
                <img src="../assets/profile.jpg" alt="profile" style="height: 32px; border-radius: 50%;">
                </a>
            </div>
        </div>
    </div>

    <div class="search-container">
    <h2>Search</h2>
    <p>Enter the Keyword for the materials your are looking for!</p>

    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Keywords" value="<?= htmlspecialchars($search) ?>">
        <select name="kategori">
            <option value="">Filter</option>
            <option value="PDF" <?= $kategori == "PDF" ? 'selected' : '' ?>>PDF</option>
            <option value="PPTX" <?= $kategori == "PPTX" ? 'selected' : '' ?>>PPT</option>
            <option value="MP3" <?= $kategori == "MP3" ? 'selected' : '' ?>>MP3</option>
            <option value="MP4" <?= $kategori == "MP4" ? 'selected' : '' ?>>MP4</option>
        </select>
        <button type="submit">Cari</button>
    </form>
</div>
      
    <?php
function getIconByCategory($category) {
    switch (strtolower($category)) {
        case 'mp3':
            return '🎵';
        case 'mp4':
            return '🎥';
        case 'pdf':
            return '📄';
        case 'pptx':
            return '📽️';
    }
}
?>

<div class="material-list">
<?php if ($result && mysqli_num_rows($result) > 0): ?>
    <h3 style="text-align: center; margin-bottom: 20px;">Hasil Pencarian:</h3>
    <ul style="list-style: none; padding: 0;">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <?php $icon = getIconByCategory($row['kategori']); ?>
            <li style="display: flex; align-items: flex-start; justify-content: space-between; margin: 15px auto; background-color: #92b4ec; padding: 20px; border-radius: 10px; width: 80%; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                <!-- Ikon kategori -->
                <div style="font-size: 70px; margin-right: 20px;">
                    <?= $icon ?>
                </div>

                <!-- Informasi materi -->
<div style="flex-grow: 1; text-align: left;">
    <strong style="font-size: 20px;"><?= htmlspecialchars($row['title']) ?></strong>
    <table style="margin-top: 8px; font-size: 14px;">
        <tr>
            <td style="font-weight: bold; padding-right: 10px;">Tipe</td>
            <td>: <?= htmlspecialchars($row['kategori']) ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Deskripsi</td>
            <td>: <?= htmlspecialchars($row['description']) ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal dibuat</td>
            <td>: <?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal diperbarui</td>
            <td>: <?= htmlspecialchars($row['updated_at']) ?></td>
        </tr>
    </table>
</div>


                <div style="display: flex; flex-direction: column; gap: 10px; margin: 20px">
                    <a href="download.php?id=<?= urlencode($row['id']) ?>" class="btn-action">Download</a>
                    <a href="../preview_materi.php?id=<?= urlencode($row['id']) ?>" class="btn-action">View</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
<?php elseif ($result): ?>
    <p style="text-align: center;">Tidak ada materi yang ditemukan.</p>
<?php endif; ?>
</div>


</div>
</body>
</html>
