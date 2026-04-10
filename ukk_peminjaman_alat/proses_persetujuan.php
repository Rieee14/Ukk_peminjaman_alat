<?php
session_start();
include 'config.php';

// Pastikan parameter tersedia
if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    header("Location: konfirmasi.php");
    exit();
}

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'setuju') {
    $status = 'Disetujui';
    $msg = 'Peminjaman Telah Disetujui! Stok alat otomatis berkurang.';
    
    // --- PENGGANTI TRIGGER: Ambil ID Alat dan Kurangi Stok ---
    $get_alat = mysqli_query($conn, "SELECT id_alat FROM peminjaman WHERE id_peminjaman = '$id'");
    $data_alat = mysqli_fetch_assoc($get_alat);
    $id_alat = $data_alat['id_alat'];

    // Update Stok Alat (Kurangi 1)
    mysqli_query($conn, "UPDATE alat SET stok_tersedia = stok_tersedia - 1 WHERE id_alat = '$id_alat'");

} else {
    $status = 'Ditolak';
    $msg = 'Peminjaman Telah Ditolak.';
}

// Update status peminjaman
$update = mysqli_query($conn, "UPDATE peminjaman SET status = '$status' WHERE id_peminjaman = '$id'");

if ($update) {
    // Catat ke log aktivitas
    $id_user = $_SESSION['id_user'];
    $log_msg = "Petugas telah $status peminjaman ID: $id";
    mysqli_query($conn, "INSERT INTO log_aktivitas (id_user, aksi) VALUES ('$id_user', '$log_msg')");

    echo "<script>
        alert('$msg');
        window.location.href = 'konfirmasi.php';
    </script>";
} else {
    echo "Gagal memproses data: " . mysqli_error($conn);
}
?>