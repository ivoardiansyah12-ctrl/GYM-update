<?php
require_once '../config/koneksi.php';

$result = $conn->query("SELECT * FROM gym_info WHERE id = 1");
$data   = $result->fetch_assoc();

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama_gym']));
    $lok  = $conn->real_escape_string(trim($_POST['lokasi']));
    $jam  = $conn->real_escape_string(trim($_POST['jam_operasional']));
    $telp = $conn->real_escape_string(trim($_POST['no_telepon']));

    if ($nama === '') {
        $pesan = '<p style="color:red">Nama gym tidak boleh kosong!</p>';
    } else {
        $conn->query("UPDATE gym_info SET
                      nama_gym='$nama',
                      lokasi='$lok',
                      jam_operasional='$jam',
                      no_telepon='$telp'
                      WHERE id=1");
        $pesan = '<p style="color:green">Data berhasil diperbarui!</p>';

        // Refresh data setelah update
        $result = $conn->query("SELECT * FROM gym_info WHERE id = 1");
        $data   = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Settings - Info Gym</title></head>
<body>

<a href="../index.php">&larr; Dashboard</a>
<h1>Settings</h1>
<h2>Informasi Gym</h2>
<hr>

<?= $pesan ?>

<form method="POST" action="edit.php">
  <label>Nama Gym:</label><br>
  <input type="text" name="nama_gym" value="<?= htmlspecialchars($data['nama_gym'] ?? '') ?>" required><br><br>

  <label>Lokasi:</label><br>
  <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi'] ?? '') ?>"><br><br>

  <label>Jam Operasional:</label><br>
  <input type="text" name="jam_operasional" value="<?= htmlspecialchars($data['jam_operasional'] ?? '') ?>" placeholder="contoh: 06:00 - 22:00"><br><br>

  <label>No. Telepon:</label><br>
  <input type="text" name="no_telepon" value="<?= htmlspecialchars($data['no_telepon'] ?? '') ?>" placeholder="contoh: 021-12345678"><br><br>

  <button type="submit">Simpan</button>
  <a href="../index.php">Batal</a>
</form>

</body>
</html>