<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kirim ke API login
    $apiURL = "http://localhost/ngertiyuk/api/auth/login.php";
    
    $ch = curl_init($apiURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'username' => $username,
        'password' => $password
    ]));
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['status']) && $result['status'] === 'success') {
        $_SESSION['token'] = $result['token'];
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['username'] = $result['user']['username'];
        $_SESSION['role'] = $result['user']['role'];

        if ($result['user']['role'] === 'operator') {
            header("Location: dashboard_operator.php");
        } else {
            header("Location: pengguna/dashboard.php");
        }
        exit;
    } else {
        $error = $result['message'] ?? "Login gagal";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { background-color: #F4F4F4; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .form-container { background: #8AAEE0; padding: 30px 40px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); width: 350px; }
        .form-container h2 { text-align: center; color: #000000; margin-bottom: 24px; }
        .form-container input { width: 100%; padding: 10px; margin-bottom: 16px;  border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;}
        .form-container button { width: 100px; padding: 10px; margin: 0 auto; display: block; background: #395886; color: white; border: none; border-radius: 8px; font-weight: bold;}
        .form-container button:hover { background:#28426a; cursor: pointer; }
        .form-container p { text-align: center; margin-top: 16px; }
        .form-container a { color:#1d6ec4; text-decoration: none; }
        .form-container a:hover { text-decoration: underline; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
         <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
