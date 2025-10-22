<?php
include 'config.php';

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = mysqli_query($koneksi, "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama', '$harga', '$stok')");
    if ($query) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan produk!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
</head>
<body>
<h2>Tambah Produk Baru</h2>
<form method="post">
    Nama Produk: <input type="text" name="nama" required><br><br>
    Harga: <input type="number" name="harga" required><br><br>
    Stok: <input type="number" name="stok" required><br><br>
    <button type="submit" name="tambah">Tambah</button>
</form>
<p><a href="dashboard.php">Kembali</a></p>
</body>
</html>
