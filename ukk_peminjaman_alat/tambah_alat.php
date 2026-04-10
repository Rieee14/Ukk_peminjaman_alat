<?php
include 'config.php';

if(isset($_POST['simpan'])) {
    $nama = $_POST['nama_alat'];
    $spek = $_POST['spesifikasi'];
    $stok = $_POST['stok'];
    $kat  = $_POST['id_kategori'];

    $sql = "INSERT INTO alat (nama_alat, spesifikasi, stok_tersedia, id_kategori) 
            VALUES ('$nama', '$spek', '$stok', '$kat')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Alat Berhasil Ditambahkan!'); window.location='dashboard.php';</script>";
    }
}
?>