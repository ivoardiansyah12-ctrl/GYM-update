<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) { header('Location: index.php'); exit; }

$id_get = $conn->real_escape_string($_GET['id']);
$result = $conn->query("SELECT * FROM paket_membership WHERE id_paket='$id_get'");
$data   = $result->fetch_assoc();

if (!$data) { header('Location: index.php'); exit; }

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paket = intval($data['id_paket']);
    $nama     = $conn->real_escape_string(trim($_POST['nama_paket']));
    $batas    = $conn->real_escape_string(trim($_POST['batas_waktu']));
    $harga    = intval($_POST['harga']);

    if (!$nama || !$harga) {
        $pesan = '<p style="color:red">Semua field wajib diisi!</p>';
    } else {
        $conn->query("UPDATE paket_membership SET
                      nama_paket='$nama', batas_waktu='$batas', harga=$harga
                      WHERE id_paket=$id_paket");
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Edit Paket Membership</title></head>
<body>

<a href="index.php">&larr; Kembali</a>
<h1>Edit Paket Membership</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="edit.php?id=<?= urlencode($data['id_paket']) ?>">

  <label>Nama Paket:</label><br>
  <input type="text" name="nama_paket" value="<?= htmlspecialchars($data['nama_paket']) ?>" required><br><br>

  <label>Batas Waktu:</label><br>
  <input type="text" name="batas_waktu" value="<?= htmlspecialchars($data['batas_waktu']) ?>"><br><br>

  <label>Harga (Rp):</label><br>
  <input type="number" name="harga" value="<?= $data['harga'] ?>" min="0" required><br><br>

  <button type="submit">Update</button>
  <a href="index.php">Batal</a>
</form>

</body>
</html>