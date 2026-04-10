<?php
session_start();
include 'config.php';

// 🔒 Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// 🔒 Cek parameter ID
if (!isset($_GET['id'])) {
    header("Location: riwayat_pinjam.php");
    exit();
}

$id = intval($_GET['id']); // amankan input

// 🔍 Ambil data peminjaman
$query = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id'");
$data = mysqli_fetch_assoc($query);

// ❌ Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='riwayat_pinjam.php';</script>";
    exit();
}

// 🔒 VALIDASI KEPEMILIKAN
if ($data['id_user'] != $id_user) {
    echo "<script>alert('Akses ditolak!'); window.location='riwayat_pinjam.php';</script>";
    exit();
}

// 🔒 VALIDASI STATUS (hanya boleh hapus jika MENUNGGU)
if ($data['status'] != 'Menunggu') {
    echo "<script>alert('Data tidak bisa dibatalkan!'); window.location='riwayat_pinjam.php';</script>";
    exit();
}

// ✅ PROSES HAPUS
$delete = mysqli_query($conn, "DELETE FROM peminjaman WHERE id_peminjaman = '$id'");

if ($delete) {
    echo "<script>alert('Pengajuan berhasil dibatalkan'); window.location='riwayat_pinjam.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data'); window.location='riwayat_pinjam.php';</script>";
}
?>