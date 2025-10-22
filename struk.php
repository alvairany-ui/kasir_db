<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: tambah_produk.php");
    exit();
}

// Ambil semua produk dari database
$produk = mysqli_query($koneksi, "SELECT * FROM produk");

$pesan = "";
$hasil = [];

// Jika form dikirim
if (isset($_POST['simpan'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $produk_dipilih = $_POST['produk'];
    $jumlah_produk = $_POST['jumlah'];

    if (empty($nama_pelanggan) || empty($produk_dipilih) || empty($jumlah_produk)) {
        $pesan = "❌ Data tidak lengkap!";
    } else {
        // Simpan pelanggan baru
        mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, alamat, no_tlp) 
                                VALUES ('$nama_pelanggan', '$alamat', '$no_tlp')");
        $id_pelanggan = mysqli_insert_id($koneksi);

        $tgl = date("Y-m-d H:i:s");
        $total_harga = 0;

        // Simpan transaksi penjualan baru
        mysqli_query($koneksi, "INSERT INTO penjualan (tgl_penjualan, total_harga, id_pelanggan) 
                                VALUES ('$tgl', 0, '$id_pelanggan')");
        $id_penjualan = mysqli_insert_id($koneksi);

        // Ambil data produk
        $query_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$produk_dipilih'");
        $data_produk = mysqli_fetch_assoc($query_produk);
        $harga = $data_produk['harga'];
        $stok = $data_produk['stok'];

        if ($jumlah_produk > $stok) {
            $pesan = "❌ Stok produk tidak cukup!";
        } else {
            $subtotal = $harga * $jumlah_produk;
            $total_harga = $subtotal;

            // Simpan detail penjualan
            mysqli_query($koneksi, "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah_produk, subtotal)
                                    VALUES ('$id_penjualan', '$produk_dipilih', '$jumlah_produk', '$subtotal')");

            // Update stok produk
            $stok_baru = $stok - $jumlah_produk;
            mysqli_query($koneksi, "UPDATE produk SET stok='$stok_baru' WHERE id_produk='$produk_dipilih'");

            // Update total harga di tabel penjualan
            mysqli_query($koneksi, "UPDATE penjualan SET total_harga='$total_harga' WHERE id_penjualan='$id_penjualan'");

            // Ambil hasil transaksi untuk ditampilkan
            $q = mysqli_query($koneksi, "
                SELECT penjualan.id_penjualan, penjualan.tgl_penjualan, penjualan.total_harga,
                       pelanggan.nama_pelanggan, pelanggan.alamat, pelanggan.no_tlp,
                       produk.nama_produk, produk.harga, detail_penjualan.jumlah_produk, detail_penjualan.subtotal
                FROM penjualan
                INNER JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
                INNER JOIN detail_penjualan ON penjualan.id_penjualan = detail_penjualan.id_penjualan
                INNER JOIN produk ON detail_penjualan.id_produk = produk.id_produk
                WHERE penjualan.id_penjualan='$id_penjualan'
            ");
            while ($r = mysqli_fetch_assoc($q)) {
                $hasil[] = $r;
            }

            $pesan = "✅ Transaksi berhasil disimpan!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaksi Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form, table { max-width: 600px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td, th { padding: 8px; border: 1px solid #ccc; text-align: center; }
        select, input[type=number], input[type=text] { width: 100%; padding: 5px; }
        button { margin-top: 10px; padding: 8px 12px; cursor: pointer; }
        .success { background-color: #d4edda; padding: 10px; border-radius: 5px; color: #155724; margin-bottom: 15px; text-align: center; }
        .error { background-color: #f8d7da; padding: 10px; border-radius: 5px; color: #721c24; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

    <h2 align="center">🧾 Transaksi Penjualan</h2>

    <?php if ($pesan != ""): ?>
        <div class="<?php echo str_contains($pesan, '✅') ? 'success' : 'error'; ?>">
            <?php echo $pesan; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <h3>Data Pelanggan</h3>
        <label>Nama Pelanggan:</label>
        <input type="text" name="nama_pelanggan" required>

        <label>Alamat:</label>
        <input type="text" name="alamat">

        <label>No. Telepon:</label>
        <input type="text" name="no_tlp">

        <h3>Data Produk</h3>
        <label>Pilih Produk:</label>
        <select name="produk" required>
            <option value="">-- Pilih Produk --</option>
            <?php while ($r = mysqli_fetch_assoc($produk)) { ?>
                <option value="<?php echo $r['id_produk']; ?>">
                    <?php echo $r['nama_produk']; ?> - Rp <?php echo number_format($r['harga']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Jumlah:</label>
        <input type="number" name="jumlah" min="1" required>

        <button type="submit" name="simpan">💾 Simpan Transaksi</button>
    </form>

    <?php if (!empty($hasil)) { ?>
        <hr>
        <h3 align="center">🧾 Detail Transaksi</h3>
        <table>
            <tr>
                <th>No. Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Total</th>
            </tr>
            <?php foreach ($hasil as $row): ?>
            <tr>
                <td><?php echo $row['id_penjualan']; ?></td>
                <td><?php echo $row['tgl_penjualan']; ?></td>
                <td><?php echo $row['nama_pelanggan']; ?></td>
                <td><?php echo $row['nama_produk']; ?></td>
                <td>Rp <?php echo number_format($row['harga']); ?></td>
                <td><?php echo $row['jumlah_produk']; ?></td>
                <td>Rp <?php echo number_format($row['subtotal']); ?></td>
                <td><strong>Rp <?php echo number_format($row['total_harga']); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php } ?>

</body>
</html>
