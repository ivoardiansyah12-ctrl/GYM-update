<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$id  = intval($_GET['id']);
$cek = $conn->query("SELECT * FROM membership WHERE id_membership=$id");

if ($cek->num_rows === 0) {
    header('Location: ../index.php?pesan=notfound');
    exit;
}

$del = $conn->query("DELETE FROM membership WHERE id_membership=$id");

if ($del) {
    header('Location: ../index.php?pesan=hapus_sukses');
} else {
    header('Location: ../index.php?pesan=hapus_gagal');
}
exit;
?>
