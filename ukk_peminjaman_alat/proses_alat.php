<?php
include 'config.php';

// =======================
// TAMBAH DATA
// =======================
if(isset($_POST['simpan'])) {

    $nama     = $_POST['nama_alat'];
    $kat      = $_POST['id_kategori'];
    $stok     = $_POST['stok'];
    $spek     = $_POST['spek'];
    $kondisi  = $_POST['kondisi'];

    // 🔥 Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    $folder = "assets/img/alat/";
    $nama_file = time() . "_" . $gambar;
    $path = $folder . $nama_file;

    move_uploaded_file($tmp, $path);

    // 🔥 Insert ke database
    $query = mysqli_query($conn, "
        INSERT INTO alat 
        (id_kategori, nama_alat, spesifikasi, stok_tersedia, kondisi, gambar, status) 
        VALUES 
        ('$kat', '$nama', '$spek', '$stok', '$kondisi', '$path', 'Tersedia')
    ");

    if($query){
        header("Location: data_alat.php?status=tambah_sukses");
    } else {
        echo "Gagal tambah: " . mysqli_error($conn);
    }
}

// =======================
// UPDATE DATA
// =======================
if(isset($_POST['update'])) {

    $id      = $_POST['id_alat'];
    $nama    = $_POST['nama_alat'];
    $stok    = $_POST['stok'];
    $spek    = $_POST['spek'];
    $kondisi = $_POST['kondisi'];

    // 🔍 Cek apakah upload gambar baru
    if(!empty($_FILES['gambar']['name'])) {

        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];

        $folder = "assets/img/alat/";
        $nama_file = time() . "_" . $gambar;
        $path = $folder . $nama_file;

        move_uploaded_file($tmp, $path);

        // 🔥 Update dengan gambar baru
        $query = mysqli_query($conn, "
            UPDATE alat SET 
                nama_alat = '$nama',
                stok_tersedia = '$stok',
                spesifikasi = '$spek',
                kondisi = '$kondisi',
                gambar = '$path'
            WHERE id_alat = '$id'
        ");

    } else {

        // 🔥 Update tanpa ganti gambar
        $query = mysqli_query($conn, "
            UPDATE alat SET 
                nama_alat = '$nama',
                stok_tersedia = '$stok',
                spesifikasi = '$spek',
                kondisi = '$kondisi'
            WHERE id_alat = '$id'
        ");
    }

    if($query){
        header("Location: data_alat.php?status=update_sukses");
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>