<?php
require_once '../config/koneksi.php';

$list_member = $conn->query("SELECT * FROM member ORDER BY id_member ASC");
$list_paket  = $conn->query("SELECT * FROM paket_membership ORDER BY id_paket ASC");

// Siapkan data durasi paket untuk JavaScript
$paket_data = [];
$list_paket->data_seek(0);
while ($p = $list_paket->fetch_assoc()) {
    // Ambil angka dari batas_waktu, misal "30 hari" -> 30
    preg_match('/(\d+)/', $p['batas_waktu'], $matches);
    $hari = isset($matches[1]) ? intval($matches[1]) : 0;
    $paket_data[$p['id_paket']] = $hari;
}
$list_paket->data_seek(0);

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_member = intval($_POST['id_member']);
    $id_paket  = $conn->real_escape_string($_POST['id_paket']);
    $mulai     = $conn->real_escape_string($_POST['tgl_mulai']);
    $akhir     = $conn->real_escape_string($_POST['tgl_berakhir']);

    if (!$id_member || !$id_paket || !$mulai || !$akhir) {
        $pesan = '<p style="color:red">Semua field wajib diisi!</p>';
    } elseif ($akhir < $mulai) {
        $pesan = '<p style="color:red">Tanggal berakhir tidak boleh sebelum tanggal mulai!</p>';
    } else {
        $conn->query("INSERT INTO membership (id_member, id_paket, tgl_mulai, tgl_berakhir)
                      VALUES ($id_member, '$id_paket', '$mulai', '$akhir')");
        header('Location: ../index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Membership</title></head>
<body>

<a href="../index.php">&larr; Kembali</a>
<h1>Tambah Membership</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="tambah.php">
  <label>Member:</label><br>
  <select name="id_member" required>
    <option value="">-- Pilih Member --</option>
    <?php while ($m = $list_member->fetch_assoc()): ?>
      <option value="<?= $m['id_member'] ?>">
        <?= $m['id_member'] ?> - <?= htmlspecialchars($m['nama']) ?>
      </option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Paket:</label><br>
  <select name="id_paket" id="select_paket" required>
    <option value="">-- Pilih Paket --</option>
    <?php while ($p = $list_paket->fetch_assoc()): ?>
      <option value="<?= $p['id_paket'] ?>">
        <?= htmlspecialchars($p['nama_paket']) ?> (<?= htmlspecialchars($p['batas_waktu']) ?>)
      </option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Tanggal Mulai:</label><br>
  <input type="date" name="tgl_mulai" id="tgl_mulai" required><br><br>

  <label>Tanggal Berakhir:</label><br>
  <input type="date" name="tgl_berakhir" id="tgl_berakhir" required readonly
         style="background:#f5f5f5; cursor:not-allowed;">
  <small style="color:#888; margin-left:8px;">Otomatis dihitung dari paket &amp; tanggal mulai</small><br><br>

  <button type="submit">Simpan</button>
  <a href="../index.php">Batal</a>
</form>

<script>
  // Data durasi paket dari PHP (dalam hari)
  const paketDurasi = <?= json_encode($paket_data) ?>;

  function hitungTglBerakhir() {
    const idPaket = document.getElementById('select_paket').value;
    const tglMulai = document.getElementById('tgl_mulai').value;
    const inputAkhir = document.getElementById('tgl_berakhir');

    if (!idPaket || !tglMulai) {
      inputAkhir.value = '';
      return;
    }

    const hari = paketDurasi[idPaket] || 0;
    if (hari === 0) {
      inputAkhir.value = '';
      return;
    }

    const mulai = new Date(tglMulai);
    mulai.setDate(mulai.getDate() + hari);

    // Format ke YYYY-MM-DD
    const yyyy = mulai.getFullYear();
    const mm   = String(mulai.getMonth() + 1).padStart(2, '0');
    const dd   = String(mulai.getDate()).padStart(2, '0');
    inputAkhir.value = `${yyyy}-${mm}-${dd}`;
  }

  document.getElementById('select_paket').addEventListener('change', hitungTglBerakhir);
  document.getElementById('tgl_mulai').addEventListener('change', hitungTglBerakhir);
</script>

</body>
</html>