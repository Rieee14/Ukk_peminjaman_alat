<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$nama = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Riwayat Peminjaman</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

:root{
--primary:#106EBE;
--mint:#0FFCBE;
--bg1:#0f2027;
--bg2:#203a43;
--bg3:#2c5364;
}

body{
background:linear-gradient(135deg,var(--bg1),var(--bg2),var(--bg3));
font-family:'Inter',sans-serif;
color:white;
min-height:100vh;
}

/* HEADER */

.page-title{
font-size:26px;
font-weight:700;
}

/* CARD GLASS */

.glass-card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(10px);
border-radius:18px;
padding:20px;
border:1px solid rgba(255,255,255,0.15);
}

/* PINJAM CARD */

.pinjam-card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(10px);
border-radius:16px;
padding:20px;
border:1px solid rgba(255,255,255,0.15);
transition:.3s;
height:100%;
}

.pinjam-card:hover{
transform:translateY(-5px);
}

/* BADGE STATUS */

.status-badge{
padding:6px 14px;
border-radius:30px;
font-size:12px;
font-weight:600;
}

.status-menunggu{
background:#ffc107;
color:black;
}

.status-setuju{
background:var(--primary);
color:white;
}

.status-selesai{
background:#28a745;
color:white;
}

.status-ditolak{
background:#dc3545;
color:white;
}

/* BUTTON */

.btn-soft{
background:rgba(255,255,255,0.1);
color:white;
border:none;
}

.btn-soft:hover{
background:rgba(255,255,255,0.2);
}

/* EMPTY */

.empty{
text-align:center;
opacity:.7;
padding:40px;
}

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<div class="page-title">📜 Riwayat Peminjaman</div>
<p class="text-light opacity-75 mb-0">Pantau status pengajuan alat yang Anda pinjam</p>
</div>

<a href="peminjaman.php" class="btn btn-light rounded-pill px-4">
+ Pinjam Alat
</a>

</div>


<div class="row g-4">

<?php

$sql = "SELECT p.*, a.nama_alat 
        FROM peminjaman p 
        JOIN alat a ON p.id_alat = a.id_alat 
        WHERE p.id_user = '$id_user' 
        ORDER BY p.id_peminjaman DESC";

$query = mysqli_query($conn,$sql);

if(mysqli_num_rows($query)>0):

while($row=mysqli_fetch_assoc($query)):

$status=$row['status'];

$class="status-menunggu";

if($status=="Disetujui") $class="status-setuju";
if($status=="Kembali" || $status=="Selesai") $class="status-selesai";
if($status=="Ditolak") $class="status-ditolak";

?>

<div class="col-md-4 col-lg-3">

<div class="pinjam-card">

<div class="mb-2 fw-bold fs-5">
<?= $row['nama_alat']; ?>
</div>

<div class="small opacity-75 mb-3">
Kode Pinjam #LP-<?= $row['id_peminjaman']; ?>
</div>

<div class="mb-2">
<small class="opacity-75">Tanggal Pinjam</small>
<div><?= date('d M Y',strtotime($row['tgl_pinjam'])); ?></div>
</div>

<div class="mb-3">
<small class="opacity-75">Rencana Kembali</small>
<div><?= date('d M Y',strtotime($row['tgl_kembali_rencana'])); ?></div>
</div>

<span class="status-badge <?= $class ?>">
<?= $status ?>
</span>

<div class="mt-3">

<?php if($status=="Menunggu"): ?>

<a href="hapus_pengajuan.php?id=<?= $row['id_peminjaman']; ?>"
class="btn btn-sm btn-soft text-danger"
onclick="return confirm('Batalkan pengajuan ini?')">
Batalkan
</a>

<?php else: ?>

<span class="small opacity-75">Tidak dapat diubah</span>

<?php endif; ?>

</div>

</div>

</div>

<?php
endwhile;

else:
?>

<div class="empty">

<img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60"><br><br>

Belum ada riwayat peminjaman

</div>

<?php endif; ?>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>