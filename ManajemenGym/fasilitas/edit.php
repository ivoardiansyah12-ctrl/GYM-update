<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) { header('Location: index.php'); exit; }

$id     = intval($_GET['id']);
$result = $conn->query("SELECT * FROM fasilitas WHERE id_fasilitas=$id");
$data   = $result->fetch_assoc();

if (!$data) { header('Location: index.php'); exit; }

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama_fasilitas']));
    if ($nama === '') {
        $pesan = '<p style="color:red">Nama fasilitas tidak boleh kosong!</p>';
    } else {
        $conn->query("UPDATE fasilitas SET nama_fasilitas='$nama' WHERE id_fasilitas=$id");
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Edit Fasilitas</title></head>
<body>

<a href="index.php">&larr; Kembali</a>
<h1>Edit Fasilitas</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="edit.php?id=<?= $id ?>">
  <label>Nama Fasilitas:</label><br>
  <input type="text" name="nama_fasilitas" value="<?= htmlspecialchars($data['nama_fasilitas']) ?>" required><br><br>
  <button type="submit">Update</button>
  <a href="index.php">Batal</a>
</form>

</body>
</html>
