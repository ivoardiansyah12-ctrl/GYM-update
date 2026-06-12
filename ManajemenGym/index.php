<?php
require_once 'config/koneksi.php';

$pesan = '';
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] === 'hapus_sukses') $pesan = '<p style="color:green">Data berhasil dihapus!</p>';
    if ($_GET['pesan'] === 'hapus_gagal')  $pesan = '<p style="color:red">Gagal menghapus data!</p>';
    if ($_GET['pesan'] === 'notfound')     $pesan = '<p style="color:red">Data tidak ditemukan!</p>';
}

// AMBIL NILAI FILTER
$f_member = $conn->real_escape_string(trim($_GET['f_member'] ?? ''));
$f_paket  = $conn->real_escape_string(trim($_GET['f_paket']  ?? ''));

// DATA DROPDOWN FILTER
$opt_member = $conn->query("SELECT id_member, nama FROM member ORDER BY nama ASC");
$opt_paket  = $conn->query("SELECT id_paket, nama_paket FROM paket_membership ORDER BY id_paket ASC");

// BANGUN WHERE CLAUSE
$where = [];
if ($f_member !== '') $where[] = "m.id_member = '$f_member'";
if ($f_paket  !== '') $where[] = "p.id_paket = '$f_paket'";

$sql_where = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$semua = $conn->query("
    SELECT
        ms.id_membership,
        m.nama        AS nama_member,
        p.nama_paket,
        p.harga,
        ms.tgl_mulai,
        ms.tgl_berakhir
    FROM membership ms
    JOIN member m           ON ms.id_member = m.id_member
    JOIN paket_membership p ON ms.id_paket  = p.id_paket
    $sql_where
    ORDER BY ms.id_membership ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sistem Manajemen Gym</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px 40px;
    }
    h1, h2 {
      text-align: center;
    }
    nav ul {
      display: flex;
      justify-content: center;
      list-style: none;
      padding: 0;
      gap: 20px;
    }
    nav ul li a {
      text-decoration: none;
      color: #333;
    }
    nav ul li a:hover {
      text-decoration: underline;
    }
    .nav-settings {
      text-align: right;
      margin-bottom: 5px;
    }
    .nav-settings a {
      font-size: 13px;
      color: #666;
      text-decoration: none;
    }
    .nav-settings a:hover {
      text-decoration: underline;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px 12px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

<div class="nav-settings">
  <a href="gym_info/edit.php">⚙ Settings</a>
</div>

<h1>Sistem Manajemen Gym</h1>
<hr>

<h2>Menu Tabel</h2>
<nav>
  <ul>
    <li><a href="fasilitas/index.php">Fasilitas</a></li>
    <li><a href="member/index.php">Member</a></li>
    <li><a href="paket_membership/index.php">Paket Membership</a></li>
  </ul>
</nav>

<hr>

<h2>Data Membership</h2>
<a href="membership/tambah.php">+ Tambah Membership</a>
<br><br>

<?= $pesan ?>

<!-- FORM FILTER -->
<form method="GET" action="index.php">

  <label>Filter:</label>
  <select name="f_member">
    <option value="">-- Semua Member --</option>
    <?php while ($row = $opt_member->fetch_assoc()): ?>
      <option value="<?= $row['id_member'] ?>" <?= $f_member == $row['id_member'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($row['nama']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  &nbsp;

  <select name="f_paket">
    <option value="">-- Semua Paket --</option>
    <?php while ($row = $opt_paket->fetch_assoc()): ?>
      <option value="<?= $row['id_paket'] ?>" <?= $f_paket == $row['id_paket'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($row['nama_paket']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  &nbsp;
  <button type="submit">Cari</button>
  <a href="index.php">Reset Filter</a>
</form>

<hr>

<?php $ada_filter = ($f_member || $f_paket); ?>
<?php if ($ada_filter): ?>
  <p><strong>Menampilkan hasil filter</strong> — <?= $semua->num_rows ?> data ditemukan.</p>
<?php endif; ?>

<!-- TABEL DATA -->
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>ID</th>
      <th>Member</th>
      <th>Paket</th>
      <th>Harga</th>
      <th>Tgl Mulai</th>
      <th>Tgl Berakhir</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($semua->num_rows === 0): ?>
      <tr><td colspan="8" align="center">Belum ada data</td></tr>
    <?php else: $no = 1; while ($row = $semua->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['id_membership'] ?></td>
      <td><?= htmlspecialchars($row['nama_member']) ?></td>
      <td><?= htmlspecialchars($row['nama_paket']) ?></td>
      <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
      <td><?= $row['tgl_mulai'] ?></td>
      <td><?= $row['tgl_berakhir'] ?></td>
      <td>
        <a href="membership/edit.php?id=<?= $row['id_membership'] ?>">Edit</a> |
        <a href="membership/hapus.php?id=<?= $row['id_membership'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; endif; ?>
  </tbody>
</table>

</body>
</html>