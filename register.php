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
        .form-container select {
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
        .message {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form id="registerForm">
            <input type="text" id="username" placeholder="Username" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <select id="role" required>
                <option value="">Pilih Role</option>
                <option value="pengguna">Pengguna</option>
                <option value="operator">Operator</option>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <div id="message" class="message"></div>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

    <script>
    document.getElementById("registerForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const username = document.getElementById("username").value;
        const email    = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const role = document.getElementById("role").value;

        fetch("http://localhost/NgertiYuk/api/auth/register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ username, email, password, role })
        })
        .then(res => res.json())
        .then(data => {
            const msg = document.getElementById("message");
            msg.innerText = data.message;
            msg.className = "message " + (data.status === "success" ? "success" : "error");
            if (data.status === "success") {
                setTimeout(() => window.location.href = "login.php", 1500);
            }
        });
    });
    </script>
</body>
</html>
