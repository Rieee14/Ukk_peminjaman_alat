<?php
include 'config.php';

$id = $_POST['id_kategori'];
$nama = $_POST['nama_kategori'];

mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori='$id'");

header("Location: data_kategori.php");