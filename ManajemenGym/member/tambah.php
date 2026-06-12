<?php
require_once '../config/koneksi.php';

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $tgl  = $conn->real_escape_string($_POST['tgl_lahir']);
    $jk   = $conn->real_escape_string($_POST['jenis_kelamin']);

    if ($nama === '') {
        $pesan = '<p style="color:red">Nama tidak boleh kosong!</p>';
    } else {
        $tgl_val = $tgl ? "'$tgl'" : "NULL";
        $jk_val  = $jk  ? "'$jk'"  : "NULL";
        $conn->query("INSERT INTO member (nama, tgl_lahir, jenis_kelamin) VALUES ('$nama', $tgl_val, $jk_val)");
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Member</title></head>
<body>

<a href="index.php">&larr; Kembali</a>
<h1>Tambah Member</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="tambah.php">
  <label>Nama:</label><br>
  <input type="text" name="nama" required><br><br>

  <label>Tanggal Lahir:</label><br>
  <input type="date" name="tgl_lahir"><br><br>

  <label>Jenis Kelamin:</label><br>
  <select name="jenis_kelamin">
    <option value="">-- Pilih --</option>
    <option value="L">Laki-laki</option>
    <option value="P">Perempuan</option>
  </select><br><br>

  <button type="submit">Simpan</button>
  <a href="index.php">Batal</a>
</form>

</body>
</html>
