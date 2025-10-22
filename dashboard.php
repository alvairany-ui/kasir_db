<?php
include 'config.php';
session_start()
// if (!isset($_SESSION['login'])) {
//     header("Location: login.php");
//     exit;
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
</head>
<body>
<h2>Selamat datang <?php echo $_SESSION['username'];?>!</h2>
<a href="tambah_produk.php">Tambah Produk</a> |
<a href="logout.php">Logout</a>

<hr>
<h3>Daftar Produk</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

    <?php
    $produk = mysqli_query($koneksi, "SELECT * FROM produk");
    while ($p = mysqli_fetch_assoc($produk)) {
        echo "
        <tr>
            <td>{$p['id_produk']}</td>
            <td>{$p['nama_produk']}</td>
            <td>Rp " . number_format($p['harga']) . "</td>
            <td>{$p['stok']}</td>
            <td>
                <a href='hapus_produk.php?id={$p['id_produk']}' onclick='return confirm(\"Yakin ingin menghapus produk ini?\")'>Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
