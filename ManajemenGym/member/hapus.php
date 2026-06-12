<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id  = intval($_GET['id']);
$del = $conn->query("DELETE FROM member WHERE id_member=$id");

if ($del) {
    header('Location: index.php?pesan=hapus_berhasil');
} else {
    header('Location: index.php?pesan=hapus_gagal');
}
exit;
?>
