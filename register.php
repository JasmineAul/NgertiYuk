<?php
session_start();
include 'koneksi_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Tentukan role berdasarkan username dan email
    $role = ($username === 'admin' && $email === 'admin@gmail.com') ? 'operator' : 'pengguna';

    $stmt = $mysql->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect sesuai role
        if ($role === 'operator') {
            header("Location: login.php");
        } else {
            header("Location: login.php");
        }
        exit();
    } else {
        $error = "Gagal mendaftar: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            background-color: #F4F4F4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .form-container {
            background: #8AAEE0;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 350px;
        }
        .form-container h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 24px;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .form-container button {
            padding: 10px 14px;
            background: #395886;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            display: block;
            margin: 0 auto;
        }
        .form-container button:hover {
            background: #28426a;
            cursor: pointer;
        }
        .form-container p {
            text-align: center;
            margin-top: 16px;
        }
        .form-container a {
            color: #1d6ec4;
            text-decoration: none;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>