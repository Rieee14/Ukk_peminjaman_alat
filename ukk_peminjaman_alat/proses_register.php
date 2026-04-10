<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    die("Akses tidak valid!");
}

if(!$conn){
    die("Koneksi database gagal!");
}

$nama     = $_POST['nama_lengkap'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Cek kosong
if(empty($nama) || empty($username) || empty($password)){
    die("Data tidak boleh kosong!");
}

// Cek username
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if(!$cek){
    die("Query error: " . mysqli_error($conn));
}

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Username sudah dipakai!'); window.location='register.php';</script>";
    exit();
}

// Insert
$query = mysqli_query($conn, "INSERT INTO users (nama_lengkap, username, password, level, status)
VALUES ('$nama', '$username', '$password', 'Peminjam', 'Aktif')");

if(!$query){
    die("Insert error: " . mysqli_error($conn));
}

echo "<script>alert('Registrasi berhasil!'); window.location='index.php';</script>";
?>