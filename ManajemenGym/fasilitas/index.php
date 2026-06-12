<?php
require_once '../config/koneksi.php';

$pesan = '';
if (isset($_GET['hapus'])) {
    $id  = intval($_GET['hapus']);
    $del = $conn->query("DELETE FROM fasilitas WHERE id_fasilitas=$id");
    $pesan = $del
        ? '<p style="color:green">Data berhasil dihapus!</p>'
        : '<p style="color:red">Gagal menghapus: ' . $conn->error . '</p>';
}

$semua = $conn->query("SELECT * FROM fasilitas ORDER BY id_fasilitas ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Data Fasilitas</title></head>
<body>

<a href="../index.php">&larr; Dashboard</a>
<h1>Data Fasilitas</h1>
<a href="tambah.php">+ Tambah Fasilitas</a>
<hr>

<?= $pesan ?>

<table border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr>
      <th>No</th>
      <th>ID</th>
      <th>Nama Fasilitas</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($semua->num_rows === 0): ?>
      <tr><td colspan="4" align="center">Belum ada data</td></tr>
    <?php else: $no = 1; while ($row = $semua->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['id_fasilitas'] ?></td>
      <td><?= htmlspecialchars($row['nama_fasilitas']) ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id_fasilitas'] ?>">Edit</a> |
        <a href="index.php?hapus=<?= $row['id_fasilitas'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; endif; ?>
  </tbody>
</table>

</body>
</html>
