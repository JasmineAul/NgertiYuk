<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NgertiYuk! - Home</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: #2f4f80;
      color: #000;
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

    .hero {
      position: relative;
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
    }

    .hero-left {
      position: relative;
      width: 55%;
      height: 100%;
      background-color: white;
      clip-path: polygon(0 0, 80% 0, 100% 50%, 80% 100%, 0 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
      text-align: center;
    }

    .hero-left h1 {
      color: #2f4f80;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .hero-left p {
      color: #000;
      font-size: 14px;
      line-height: 1.5;
      margin-bottom: 30px;
    }

    .hero-left .btn-login {
      background-color: #2d4373;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 14px;
      text-decoration: none;
    }

    .hero-right {
      width: 45%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .hero-right img {
      width: 80%;
      max-width: 400px;
      border-radius: 25px;
      box-shadow: 5px 5px 15px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        height: auto;
      }
      .hero-left, .hero-right {
        width: 100%;
        clip-path: none;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="navbar">
        <div class="logo">
            <img src="Assets/logoo.png" alt="Logo" style="height: 40px;">
        </div>
        <div class="menu">
            <a href="login.php">Home</a>
            <a href="login.php">Materi</a>
            <div class="profile-icon">
                <a href="login.php">
                <img src="assets/profile.jpg" alt="profile" style="height: 32px; border-radius: 50%;">
                </a>
            </div>
        </div>
    </div>

  <section class="hero">
    <div class="hero-left">
      <h1>Belajar Biologi Jadi Lebih Mudah di<br>NgertiYuk!</h1>
      <p>
        Temukan materi biologi yang cocok dengan gaya belajarmu. Belajar sesuai caramu dan pahami lebih cepat!<br>
        Login sekarang untuk mengakses materinya!
      </p>
      <a href="login.php" class="btn-login">Login</a>
    </div>
    <div class="hero-right">
      <img src="Assets/gambar_bio.jpg" alt="Belajar Biologi">
    </div>
  </section>
</body>
</html>