<?php
session_start();
include 'config.php';

// Hanya admin
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'Admin') {
    header("Location: dashboard.php");
    exit();
}

$id_user = $_GET['id'];

// Aktifkan user
$query = mysqli_query($conn, "
    UPDATE users 
    SET status='Aktif' 
    WHERE id_user='$id_user'
");

if ($query) {
    echo "<script>alert('User berhasil diaktifkan'); window.location='data_user.php';</script>";
} else {
    echo "<script>alert('Gagal'); window.location='data_user.php';</script>";
}
?>