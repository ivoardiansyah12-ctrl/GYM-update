<?php
require_once '../config/koneksi.php';

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = $conn->real_escape_string(trim($_POST['nama_paket']));
    $batas = $conn->real_escape_string(trim($_POST['batas_waktu']));
    $harga = intval($_POST['harga']);

    if (!$nama || !$harga) {
        $pesan = '<p style="color:red">Semua field wajib diisi!</p>';
    } else {
        $conn->query("INSERT INTO paket_membership (nama_paket, batas_waktu, harga)
                      VALUES ('$nama', '$batas', $harga)");
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Paket Membership</title></head>
<body>

<a href="index.php">&larr; Kembali</a>
<h1>Tambah Paket Membership</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="tambah.php">


  <label>Nama Paket:</label><br>
  <input type="text" name="nama_paket" required><br><br>

  <label>Batas Waktu:</label><br>
  <input type="text" name="batas_waktu" placeholder="contoh: 30 hari"><br><br>

  <label>Harga (Rp):</label><br>
  <input type="number" name="harga" min="0" required><br><br>

  <button type="submit">Simpan</button>
  <a href="index.php">Batal</a>
</form>

</body>
</html>