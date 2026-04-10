<?php
session_start();
include 'config.php';

// Proteksi (yang tidak boleh akses hanya peminjam)
if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: dashboard.php");
    exit();
}

// Ambil ID
$id = $_GET['id'];

// Cek relasi ke tabel alat
$cek = mysqli_query($conn, "SELECT * FROM alat WHERE id_kategori='$id'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('❌ Kategori tidak bisa dihapus karena masih digunakan!');
        window.location='data_kategori.php';
    </script>";
    exit();
}

// Hapus
$hapus = mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");

if ($hapus) {
    echo "<script>
        alert('✅ Kategori berhasil dihapus!');
        window.location='data_kategori.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Gagal menghapus kategori!');
        window.location='data_kategori.php';
    </script>";
}
?>