<?php
session_start();
include 'config.php';

// 🔒 Keamanan
if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: data_alat.php");
    exit();
}

$id_user_login = $_SESSION['id_user'];
$id_alat = mysqli_real_escape_string($conn, $_GET['id']);

// 🔍 Ambil nama alat
$ambil_alat = mysqli_query($conn, "SELECT nama_alat FROM alat WHERE id_alat = '$id_alat'");
$data_alat  = mysqli_fetch_assoc($ambil_alat);
$nama_alat  = $data_alat['nama_alat'] ?? 'Tidak diketahui';

// 🔴 CEK: masih ada peminjaman yang belum selesai
$cek = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM peminjaman 
    WHERE id_alat='$id_alat' 
    AND status != 'Selesai'
");

$data_cek = mysqli_fetch_assoc($cek);

if($data_cek['total'] > 0){
    echo "<script>
        alert('❌ Gagal! Alat masih dipinjam / belum selesai.');
        window.location.href = 'data_alat.php';
    </script>";
    exit();
}

// ✅ SOFT DELETE (bukan hapus)
$query = mysqli_query($conn, "
    UPDATE alat 
    SET status = 'Dihapus' 
    WHERE id_alat='$id_alat'
");

// ✅ HASIL
if ($query) {

    // 📝 Simpan log aktivitas
    $aksi = "Menghapus (soft delete) alat: " . $nama_alat;
    mysqli_query($conn, "
        INSERT INTO log_aktivitas (id_user, aksi) 
        VALUES ('$id_user_login', '$aksi')
    ");

    echo "<script>
        alert('✅ Alat berhasil dinonaktifkan');
        window.location.href = 'data_alat.php';
    </script>";

} else {

    echo "<script>
        alert('❌ Terjadi kesalahan saat memproses!');
        window.location.href = 'data_alat.php';
    </script>";
}
?>