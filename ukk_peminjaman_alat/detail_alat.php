```php
<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

$level = $_SESSION['level'] ?? '';

if ($level == 'Admin' || $level == 'Petugas') {
    $kembali = "data_alat.php";
} else {
    $kembali = "peminjaman.php";
}

$query = mysqli_query($conn, "
SELECT alat.*, kategori.nama_kategori 
FROM alat 
LEFT JOIN kategori ON alat.id_kategori = kategori.id_kategori
WHERE id_alat = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='$kembali';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detail Alat</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
font-family:'Inter',sans-serif;
background:#0f172a;
color:white;
overflow-x:hidden;
}

/* MAIN */

.main-content{
margin-left:260px;
padding:40px;
min-height:100vh;
background:linear-gradient(135deg,#0f172a,#1e293b);
}

@media(max-width:992px){
.main-content{
margin-left:0;
}
}

/* CARD */

.detail-card{
background:#1e293b;
border-radius:18px;
border:1px solid rgba(255,255,255,0.05);
padding:30px;
}

/* IMAGE */

.alat-img{
width:100%;
height:320px;
object-fit:cover;
border-radius:14px;
}

/* TITLE */

.alat-title{
font-size:30px;
font-weight:700;
margin-bottom:10px;
}

.alat-desc{
color:#94a3b8;
margin-bottom:20px;
}

/* INFO BOX */

.info-box{
background:#0f172a;
border-radius:12px;
padding:15px;
margin-bottom:10px;
border:1px solid rgba(255,255,255,0.05);
}

/* BADGE */

.badge-modern{
padding:6px 12px;
border-radius:20px;
font-size:12px;
font-weight:600;
}

/* BUTTON */

.btn-back{
background:#334155;
border:none;
border-radius:8px;
padding:8px 16px;
color:white;
}

.btn-back:hover{
background:#475569;
}

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="detail-card">

<div class="row g-4">

<!-- GAMBAR -->

<div class="col-lg-5">

<img src="<?= $data['gambar'] ? $data['gambar'] : 'https://via.placeholder.com/500x300'; ?>"
class="alat-img">

</div>

<!-- DETAIL -->

<div class="col-lg-7">

<div class="alat-title">
<?= $data['nama_alat']; ?>
</div>

<div class="alat-desc">
<?= $data['spesifikasi']; ?>
</div>

<div class="info-box">
<b>Kategori</b><br>
<span class="text-info"><?= $data['nama_kategori']; ?></span>
</div>

<div class="info-box">
<b>Kondisi</b><br>

<?php
$warna="secondary";
if($data['kondisi']=="Baik") $warna="success";
if($data['kondisi']=="Rusak Ringan") $warna="warning";
if($data['kondisi']=="Rusak Berat") $warna="danger";
?>

<span class="badge bg-<?= $warna ?> badge-modern">
<?= $data['kondisi']; ?>
</span>

</div>

<div class="info-box">
<b>Stok Tersedia</b><br>
<span class="badge bg-success badge-modern">
<?= $data['stok_tersedia']; ?> Unit
</span>
</div>

<div class="info-box">
<b>Status</b><br>
<span class="text-secondary"><?= $data['status']; ?></span>
</div>

<a href="<?= $kembali; ?>" class="btn-back mt-3">
⬅ Kembali
</a>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```
