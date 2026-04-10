<?php
session_start();
include 'config.php';

// Ambil data dari form
$nama     = mysqli_real_escape_string($conn, $_POST['nama']);
$username = mysqli_real_escape_string($conn, $_POST['user']);
$password = $_POST['pass'];
$level    = $_POST['level'];

// 🔒 HASH PASSWORD
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Cek username sudah ada
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Username sudah digunakan!'); window.location='data_user.php';</script>";
    exit();
}

// Insert ke database
$query = mysqli_query($conn, "INSERT INTO users (nama_lengkap, username, password, level, status)
VALUES ('$nama', '$username', '$password_hash', '$level', 'Aktif')");

if($query){
    echo "<script>alert('User berhasil ditambahkan!'); window.location='data_user.php';</script>";
} else {
    echo "<script>alert('Gagal menambahkan user!'); window.location='data_user.php';</script>";
}
?>