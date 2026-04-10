<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_peminjaman_alat"; // Pastikan nama database sesuai

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>