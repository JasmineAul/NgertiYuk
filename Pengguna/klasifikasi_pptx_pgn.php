<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pengguna') {
    header("Location: login.php");
    exit();
}

require '../koneksi_db.php';

$sql = "SELECT * FROM materi WHERE kategori = 'pptx' ORDER BY created_at DESC";
$result = $mysql->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Materi - PPTX</title>
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
            margin-top: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Maksimal 4 kolom */
            gap: 20px;
            padding: 20px;
            max-height: 70vh; /* Atur tinggi maksimal */
            overflow-y: auto; /* Scroll vertikal jika konten terlalu banyak */
        }

        .card {
            position: relative;
            background-color: #8AAEE0;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            height: 150px; /* Tetapkan tinggi tetap */
        }

        .card-icon {
            font-size: 60px;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #FFFFFF;
            color: black;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .back {
            display: block;
            margin: 30px;
            text-align: left;
            text-decoration: none;
            font-size: 16px;
            color: #333;
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
<h2>Materi - PPTX</h2>
<div class="grid">
    <?php if ($result && $result->num_rows > 0): ?>
        
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-icon">📽️</div>
                <?php if (!empty($row['filename'])): ?>
                    <a href="../preview_materi.php?id=<?php echo $row['id']; ?>">
                        <button><?php echo htmlspecialchars($row['title']) . '.' .($row['kategori']); ?></button>
                    </a>
                <?php else: ?>
            <button disabled style="background-color: gray;">File tidak tersedia</button>
        <?php endif; ?>

            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<a class="back" href="kategori_materi_pgn.php">← Kembali</a>

</body>
</html>