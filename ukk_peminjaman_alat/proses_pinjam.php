<?php
session_start();
include 'config.php';

// 🔒 Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// 📥 Ambil data dari form
$id_user   = $_SESSION['id_user'];
$id_alat   = mysqli_real_escape_string($conn, $_POST['id_alat']);
$tgl_pinjam = $_POST['tgl_pinjam'];
$tgl_kembali = $_POST['tgl_kembali_rencana'];

// 🔍 Validasi kosong
if (empty($id_alat) || empty($tgl_pinjam) || empty($tgl_kembali)) {
    echo "<script>alert('Semua field wajib diisi!'); window.location='peminjaman.php';</script>";
    exit();
}

// 📅 Validasi tanggal
if ($tgl_kembali < $tgl_pinjam) {
    echo "<script>alert('Tanggal kembali tidak boleh sebelum tanggal pinjam!'); window.location='peminjaman.php';</script>";
    exit();
}

// 🔍 Ambil data alat
$query_alat = mysqli_query($conn, "SELECT * FROM alat WHERE id_alat = '$id_alat'");
$data_alat  = mysqli_fetch_assoc($query_alat);

// ❌ Jika alat tidak ada
if (!$data_alat) {
    echo "<script>alert('Alat tidak ditemukan!'); window.location='peminjaman.php';</script>";
    exit();
}

// 🚫 VALIDASI STATUS (INI YANG DITAMBAHKAN)
if ($data_alat['status'] == 'Dihapus') {
    echo "<script>
        alert('❌ Alat sudah tidak tersedia / dinonaktifkan!');
        window.location='peminjaman.php';
    </script>";
    exit();
}

// 📦 Cek stok
if ($data_alat['stok_tersedia'] <= 0) {
    echo "<script>alert('Stok alat habis!'); window.location='peminjaman.php';</script>";
    exit();
}

// 💾 Simpan ke tabel peminjaman
$query = mysqli_query($conn, "
    INSERT INTO peminjaman 
    (id_user, id_alat, tgl_pinjam, tgl_kembali_rencana, status) 
    VALUES 
    ('$id_user', '$id_alat', '$tgl_pinjam', '$tgl_kembali', 'Menunggu')
");

// ✅ Jika berhasil
if ($query) {

    // 📝 Log aktivitas
    $nama_alat = $data_alat['nama_alat'];
    $aksi = "Mengajukan peminjaman alat: " . $nama_alat;

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (id_user, aksi) 
        VALUES ('$id_user', '$aksi')
    ");

    echo "<script>
        alert('✅ Permohonan berhasil dikirim!');
        window.location='riwayat_pinjam.php';
    </script>";

} else {

    echo "<script>
        alert('❌ Gagal mengajukan peminjaman!');
        window.location='peminjaman.php';
    </script>";
}
?>