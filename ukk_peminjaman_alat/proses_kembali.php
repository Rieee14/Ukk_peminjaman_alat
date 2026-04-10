// proses_kembali.php

<?php
session_start();
include 'config.php';

// 🔒 Proteksi
if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: dashboard.php");
    exit();
}

// 🔒 Validasi ID
if (!isset($_GET['id'])) {
    header("Location: pengembalian.php");
    exit();
}

$id = intval($_GET['id']);

// 🔍 Ambil data peminjaman
$query = mysqli_query($conn, "
    SELECT * FROM peminjaman 
    WHERE id_peminjaman = '$id'
");
$data = mysqli_fetch_assoc($query);

// ❌ Jika tidak ada
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pengembalian.php';</script>";
    exit();
}

// 🔒 Validasi status
if ($data['status'] != 'Disetujui') {
    echo "<script>alert('Data sudah diproses!'); window.location='pengembalian.php';</script>";
    exit();
}

// 🔄 Cek apakah sudah pernah dikembalikan (hindari double insert)
$cek = mysqli_query($conn, "
    SELECT * FROM pengembalian 
    WHERE id_peminjaman = '$id'
");

if (mysqli_num_rows($cek) > 0) {

    // Sudah pernah dikembalikan → pastikan statusnya selesai
    $update = mysqli_query($conn, "
        UPDATE peminjaman 
        SET status = 'Selesai'
        WHERE id_peminjaman = '$id'
    ");

    echo "<script>
        alert('Data sudah ada, status diperbaiki menjadi Selesai!');
        window.location='pengembalian.php';
    </script>";
    exit();
}

// 🔄 Hitung denda (opsional)
$today = date('Y-m-d');
$denda = 0;

if ($today > $data['tgl_kembali_rencana']) {
    $selisih = (strtotime($today) - strtotime($data['tgl_kembali_rencana'])) / (60*60*24);
    $denda = $selisih * 1000; // contoh: 1000 per hari
}

// 🔄 INSERT ke tabel pengembalian
$insert = mysqli_query($conn, "
    INSERT INTO pengembalian (id_peminjaman, tgl_kembali_asli, denda)
    VALUES ('$id', NOW(), '$denda')
");

// 🔄 Update status peminjaman
$update = mysqli_query($conn, "
    UPDATE peminjaman 
    SET status = 'Selesai'
    WHERE id_peminjaman = '$id'
");

// 🔄 Kembalikan stok alat
$id_alat = $data['id_alat'];

$stok = mysqli_query($conn, "
    UPDATE alat 
    SET stok_tersedia = stok_tersedia + 1
    WHERE id_alat = '$id_alat'
");

// ✅ HASIL
if ($insert && $update && $stok) {
    echo "<script>
        alert('Pengembalian berhasil! Denda: Rp $denda');
        window.location='pengembalian.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal memproses!');
        window.location='pengembalian.php';
    </script>";
}
?>