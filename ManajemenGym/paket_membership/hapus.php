<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id  = $conn->real_escape_string($_GET['id']);
$cek = $conn->query("SELECT * FROM paket_membership WHERE id_paket='$id'");

if ($cek->num_rows === 0) {
    header('Location: index.php?pesan=notfound');
    exit;
}

$del = $conn->query("DELETE FROM paket_membership WHERE id_paket='$id'");

if ($del) {
    header('Location: index.php?pesan=hapus_sukses');
} else {
    header('Location: index.php?pesan=hapus_gagal');
}
exit;
?>
