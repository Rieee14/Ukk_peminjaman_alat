// proses_konfirmasi.php

<?php
session_start();
include 'config.php';

// Proteksi: hanya admin & petugas
if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: dashboard.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil parameter
$id_peminjaman = mysqli_real_escape_string($conn, $_GET['id']);
$status        = mysqli_real_escape_string($conn, $_GET['status']);

// Ambil data peminjaman + alat
$query = mysqli_query($conn, "
    SELECT p.*, a.nama_alat, a.stok_tersedia, a.id_alat
    FROM peminjaman p
    JOIN alat a ON p.id_alat = a.id_alat
    WHERE p.id_peminjaman = '$id_peminjaman'
");

$data = mysqli_fetch_assoc($query);

// 🚫 CEK: jangan proses kalau sudah pernah diproses
if ($data['status'] != 'Menunggu') {
    echo "<script>
        alert('Data sudah pernah diproses!');
        window.location='konfirmasi.php';
    </script>";
    exit();
}

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='konfirmasi.php';</script>";
    exit();
}

$id_alat   = $data['id_alat'];
$nama_alat = $data['nama_alat'];
$stok      = $data['stok_tersedia'];

// =======================
// JIKA DISETUJUI
// =======================
if ($status == 'Disetujui') {

    // Cek stok
    if ($stok <= 0) {
        echo "<script>
            alert('❌ Stok alat habis, tidak bisa disetujui!');
            window.location='konfirmasi.php';
        </script>";
        exit();
    }

    // Kurangi stok
    mysqli_query($conn, "
        UPDATE alat 
        SET stok_tersedia = stok_tersedia - 1 
        WHERE id_alat = '$id_alat'
    ");

    // Update status peminjaman
    mysqli_query($conn, "
        UPDATE peminjaman 
        SET status = 'Disetujui' 
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    // Simpan log
    $aksi = "Menyetujui peminjaman alat: " . $nama_alat;
    mysqli_query($conn, "
        INSERT INTO log_aktivitas (id_user, aksi) 
        VALUES ('$id_user', '$aksi')
    ");

    echo "<script>
        alert('✅ Peminjaman disetujui!');
        window.location='konfirmasi.php';
    </script>";
}

// =======================
// JIKA DITOLAK
// =======================
elseif ($status == 'Ditolak') {

    mysqli_query($conn, "
        UPDATE peminjaman 
        SET status = 'Ditolak' 
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    // Simpan log
    $aksi = "Menolak peminjaman alat: " . $nama_alat;
    mysqli_query($conn, "
        INSERT INTO log_aktivitas (id_user, aksi) 
        VALUES ('$id_user', '$aksi')
    ");

    echo "<script>
        alert('❌ Peminjaman ditolak!');
        window.location='konfirmasi.php';
    </script>";
}

// =======================
// STATUS TIDAK VALID
// =======================
else {
    echo "<script>
        alert('Status tidak valid!');
        window.location='konfirmasi.php';
    </script>";
}
?>