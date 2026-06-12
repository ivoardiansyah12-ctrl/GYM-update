<?php
require_once '../config/koneksi.php';

$pesan = '';
if (isset($_GET['hapus'])) {
    $id  = intval($_GET['hapus']);
    $del = $conn->query("DELETE FROM member WHERE id_member=$id");
    $pesan = $del
        ? '<p style="color:green">Data berhasil dihapus!</p>'
        : '<p style="color:red">Gagal menghapus: ' . $conn->error . '</p>';
}

$semua = $conn->query("SELECT * FROM member ORDER BY id_member ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Data Member</title></head>
<body>

<a href="../index.php">&larr; Dashboard</a>
<h1>Data Member</h1>
<a href="tambah.php">+ Tambah Member</a>
<hr>

<?= $pesan ?>

<table border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr>
      <th>No</th>
      <th>ID</th>
      <th>Nama</th>
      <th>Tanggal Lahir</th>
      <th>Jenis Kelamin</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($semua->num_rows === 0): ?>
      <tr><td colspan="6" align="center">Belum ada data</td></tr>
    <?php else: $no = 1; while ($row = $semua->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['id_member'] ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= $row['tgl_lahir'] ?? '-' ?></td>
      <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($row['jenis_kelamin'] === 'P' ? 'Perempuan' : '-') ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id_member'] ?>">Edit</a> |
        <a href="index.php?hapus=<?= $row['id_member'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; endif; ?>
  </tbody>
</table>

</body>
</html>
