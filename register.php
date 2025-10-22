<?php
include 'config.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($query) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Gagal registrasi!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Kasir</title>
</head>
<body>
<h2>Register Kasir</h2>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit" name="register">Register</button>
</form>
<p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>
