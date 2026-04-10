<?php
session_start();
include 'config.php';

// Hanya admin
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'Admin') {
    header("Location: dashboard.php");
    exit();
}

$id_user = $_GET['id'];

// Tidak boleh nonaktifkan diri sendiri
if ($id_user == $_SESSION['id_user']) {
    echo "<script>alert('Tidak bisa menonaktifkan akun sendiri'); window.location='data_user.php';</script>";
    exit();
}

// Nonaktifkan user
$query = mysqli_query($conn, "
    UPDATE users 
    SET status='Nonaktif' 
    WHERE id_user='$id_user'
");

if ($query) {
    echo "<script>alert('User berhasil dinonaktifkan'); window.location='data_user.php';</script>";
} else {
    echo "<script>alert('Gagal'); window.location='data_user.php';</script>";
}
?>