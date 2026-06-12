<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "gym";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("<p style='color:red'>Koneksi gagal: " . $conn->connect_error . "</p>");
}

$conn->set_charset("utf8mb4");
?>
