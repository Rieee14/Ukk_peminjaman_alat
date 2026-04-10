<?php
include 'config.php';

$nama = $_POST['nama_kategori'];

mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");

echo "<script>window.location='data_kategori.php';</script>";