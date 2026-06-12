<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) { header('Location: ../index.php'); exit; }

$id     = intval($_GET['id']);
$result = $conn->query("SELECT * FROM membership WHERE id_membership=$id");
$data   = $result->fetch_assoc();

if (!$data) { header('Location: ../index.php'); exit; }

$list_member = $conn->query("SELECT * FROM member ORDER BY id_member ASC");
$list_paket  = $conn->query("SELECT * FROM paket_membership ORDER BY id_paket ASC");

// Siapkan data durasi paket untuk JavaScript
$paket_data = [];
$list_paket->data_seek(0);
while ($p = $list_paket->fetch_assoc()) {
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
        $conn->query("UPDATE membership SET id_member=$id_member, id_paket='$id_paket',
                      tgl_mulai='$mulai', tgl_berakhir='$akhir' WHERE id_membership=$id");
        header('Location: ../index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Edit Membership</title></head>
<body>

<a href="../index.php">&larr; Kembali</a>
<h1>Edit Membership</h1>
<hr>

<?= $pesan ?>

<form method="POST" action="edit.php?id=<?= $id ?>">
  <label>Member:</label><br>
  <select name="id_member" required>
    <option value="">-- Pilih Member --</option>
    <?php $list_member->data_seek(0); while ($m = $list_member->fetch_assoc()): ?>
      <option value="<?= $m['id_member'] ?>" <?= $m['id_member'] == $data['id_member'] ? 'selected' : '' ?>>
        <?= $m['id_member'] ?> - <?= htmlspecialchars($m['nama']) ?>
      </option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Paket:</label><br>
  <select name="id_paket" id="select_paket" required>
    <option value="">-- Pilih Paket --</option>
    <?php $list_paket->data_seek(0); while ($p = $list_paket->fetch_assoc()): ?>
      <option value="<?= $p['id_paket'] ?>" <?= $p['id_paket'] == $data['id_paket'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($p['nama_paket']) ?> (<?= htmlspecialchars($p['batas_waktu']) ?>)
      </option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Tanggal Mulai:</label><br>
  <input type="date" name="tgl_mulai" id="tgl_mulai" value="<?= $data['tgl_mulai'] ?>" required><br><br>

  <label>Tanggal Berakhir:</label><br>
  <input type="date" name="tgl_berakhir" id="tgl_berakhir" value="<?= $data['tgl_berakhir'] ?>" required readonly
         style="background:#f5f5f5; cursor:not-allowed;">
  <small style="color:#888; margin-left:8px;">Otomatis dihitung dari paket &amp; tanggal mulai</small><br><br>

  <button type="submit">Update</button>
  <a href="../index.php">Batal</a>
</form>

<script>
  const paketDurasi = <?= json_encode($paket_data) ?>;

  function hitungTglBerakhir() {
    const idPaket  = document.getElementById('select_paket').value;
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