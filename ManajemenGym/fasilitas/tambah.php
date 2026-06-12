<?php
require_once '../config/koneksi.php';

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama_fasilitas']));
    if ($nama === '') {
        $pesan = '<p style="color:red">Nama fasilitas tidak boleh kosong!</p>';
    } else {
        $conn->query("INSERT INTO fasilitas (nama_fasilitas) VALUES ('$nama')");
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Fasilitas</title></head>
<body>

<a href="index.php">&larr; Kembali</a>
<h1>Tambah Fasilitas</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="tambah.php">
  <label>Nama Fasilitas:</label><br>
  <input type="text" name="nama_fasilitas" required><br><br>
  <button type="submit">Simpan</button>
  <a href="index.php">Batal</a>
</form>

</body>
</html>
