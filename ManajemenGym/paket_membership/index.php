<?php
require_once '../config/koneksi.php';

$pesan = '';
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] === 'hapus_sukses') $pesan = '<p style="color:green">Data berhasil dihapus!</p>';
    if ($_GET['pesan'] === 'hapus_gagal')  $pesan = '<p style="color:red">Gagal menghapus data!</p>';
    if ($_GET['pesan'] === 'notfound')     $pesan = '<p style="color:red">Data tidak ditemukan!</p>';
}

$semua = $conn->query("SELECT * FROM paket_membership ORDER BY id_paket ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Data Paket Membership</title></head>
<body>

<a href="../index.php">&larr; Dashboard</a>
<h1>Data Paket Membership</h1>
<a href="tambah.php">+ Tambah Paket</a>
<hr>

<?= $pesan ?>

<table border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr>
      <th>No</th>
      <th>ID Paket</th>
      <th>Nama Paket</th>
      <th>Batas Waktu</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($semua->num_rows === 0): ?>
      <tr><td colspan="6" align="center">Belum ada data</td></tr>
    <?php else: $no = 1; while ($row = $semua->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['id_paket']) ?></td>
      <td><?= htmlspecialchars($row['nama_paket']) ?></td>
      <td><?= htmlspecialchars($row['batas_waktu']) ?></td>
      <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
      <td>
        <a href="edit.php?id=<?= urlencode($row['id_paket']) ?>">Edit</a> |
        <a href="hapus.php?id=<?= urlencode($row['id_paket']) ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; endif; ?>
  </tbody>
</table>

</body>
</html>