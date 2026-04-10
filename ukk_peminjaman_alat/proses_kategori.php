<?php
include 'config.php';
if(isset($_POST['simpan'])) {
    $nk = $_POST['nama_kategori'];
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nk')");
    header("Location: data_kategori.php");
}
?>