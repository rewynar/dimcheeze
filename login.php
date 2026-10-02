<?php
session_start();

// Jika admin sudah login, langsung arahkan ke admin.php
if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === true) {
    header("Location: admin.php");
    exit();
}

// Proses ketika tombol login ditekan
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // GANTI USERNAME DAN PASSWORD DI BAWAH INI SESUAI KEINGINANMU
    $username_benar = "Deeznut";
    $password_benar = "Dimz@11";

    if ($username === $username_benar && $password === $password_benar) {
        $_SESSION['admin_login'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Dimcheese</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #F4F7F6; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: 'Poppins', sans-serif; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .login-card h2 { margin-bottom: 20px; color: #FFC107; }
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #CCC; border-radius: 5px; box-sizing: border-box; }
        .btn-login { width: 100%; background: #FFC107; color: #333; border: none; padding: 10px; font-weight: bold; border-radius: 5px; cursor: pointer; }
        .error-msg { color: red; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>🔒 Login Admin</h2>
        <?php if($error != "") echo "<div class='error-msg'>$error</div>"; ?>
        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
    </div>
</body>
</html>