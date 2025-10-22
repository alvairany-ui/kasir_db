<?php
session_start();
include 'config.php';

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    // Pastikan password di database sudah di-hash dengan password_hash()
    if ($row && $password == $row['password']) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Kasir</title>
</head>
<body>
<h2>Login Kasir</h2>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>
<p>Belum punya akun? <a href="register.php">Register di sini</a></p>
</body>
</html>
