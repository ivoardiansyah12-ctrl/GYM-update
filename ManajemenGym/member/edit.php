<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) { header('Location: index.php'); exit; }

$id     = intval($_GET['id']);
$result = $conn->query("SELECT * FROM member WHERE id_member=$id");
$data   = $result->fetch_assoc();

if (!$data) { header('Location: index.php'); exit; }

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
        $conn->query("UPDATE member SET nama='$nama', tgl_lahir=$tgl_val, jenis_kelamin=$jk_val WHERE id_member=$id");
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Edit Member</title></head>
<body>

<a href="index.php">&larr; Kembali</a>
<h1>Edit Member</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="edit.php?id=<?= $id ?>">
  <label>Nama:</label><br>
  <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br><br>

  <label>Tanggal Lahir:</label><br>
  <input type="date" name="tgl_lahir" value="<?= $data['tgl_lahir'] ?? '' ?>"><br><br>

  <label>Jenis Kelamin:</label><br>
  <select name="jenis_kelamin">
    <option value="">-- Pilih --</option>
    <option value="L" <?= $data['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
    <option value="P" <?= $data['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
  </select><br><br>

  <button type="submit">Update</button>
  <a href="index.php">Batal</a>
</form>

</body>
</html>
