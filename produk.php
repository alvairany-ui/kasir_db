<?php
include 'config.php';

$query = mysqli_query($koneksi, "SELECT * FROM produk");
while ($data = mysqli_fetch_array($query)) {
    echo $data['nama_produk'] . " - Rp " . $data['harga'] . "<br>";
}
?>
