<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE id='$id'");

    if ($hapus) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk!'); window.location='dashboard.php';</script>";
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
