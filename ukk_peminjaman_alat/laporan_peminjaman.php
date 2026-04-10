<?php
session_start();
include 'config.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: dashboard.php");
    exit();
}

$query = mysqli_query($conn, "SELECT p.*, u.nama_lengkap, a.nama_alat 
FROM peminjaman p 
JOIN users u ON p.id_user = u.id_user 
JOIN alat a ON p.id_alat = a.id_alat 
ORDER BY p.tgl_pinjam DESC");

$total = mysqli_num_rows($query);
$dipinjam = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='Disetujui'"));
$kembali = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='Kembali'"));
$ditolak = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='Ditolak'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Peminjaman</title>

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

/* MAIN CONTENT */

.main-content{
margin-left:260px;
padding:30px;
}

/* TITLE */

.page-title{
font-size:26px;
font-weight:700;
}

/* GLASS CARD */

.glass-card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(10px);
border-radius:18px;
padding:20px;
border:1px solid rgba(255,255,255,0.15);
}

/* STAT CARD */

.stat-card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(10px);
border-radius:16px;
padding:20px;
border:1px solid rgba(255,255,255,0.15);
transition:.3s;
}

.stat-card:hover{
transform:translateY(-3px);
}

/* TABLE */

.table{
color:white;
}

.table thead{
background:rgba(255,255,255,0.15);
}

.table tbody tr{
border-color:rgba(255,255,255,0.1);
}

.table tbody tr:hover{
background:rgba(255,255,255,0.08);
}

/* STATUS */

.badge-status{
padding:6px 12px;
border-radius:30px;
font-size:12px;
font-weight:600;
}

.status-disetujui{
background:#0d6efd;
}

.status-kembali{
background:#00c851;
}

.status-ditolak{
background:#ff4444;
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

/* PRINT */

@media print{

.sidebar,
.no-print{
display:none!important;
}

body{
background:white;
color:black;
}

.main-content{
margin-left:0;
}

.glass-card,
.stat-card{
background:white;
border:none;
}

.table{
color:black;
}

}

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid">

<!-- HEADER -->

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<div class="page-title">📄 Laporan Peminjaman</div>

<p class="text-light opacity-75 mb-0">
Rekap data peminjaman alat inventaris
</p>

</div>

<button onclick="window.print()" class="btn btn-light rounded-pill px-4 no-print">
🖨 Cetak PDF
</button>

</div>


<!-- STATISTIK -->

<div class="row mb-4 g-3">

<div class="col-md-3">

<div class="stat-card">

<div class="text-light opacity-75">Total Transaksi</div>

<h3><?= $total ?></h3>

</div>

</div>


<div class="col-md-3">

<div class="stat-card">

<div class="text-light opacity-75">Dipinjam</div>

<h3><?= $dipinjam ?></h3>

</div>

</div>


<div class="col-md-3">

<div class="stat-card">

<div class="text-light opacity-75">Dikembalikan</div>

<h3><?= $kembali ?></h3>

</div>

</div>


<div class="col-md-3">

<div class="stat-card">

<div class="text-light opacity-75">Ditolak</div>

<h3><?= $ditolak ?></h3>

</div>

</div>

</div>


<!-- TABLE -->

<div class="glass-card">

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>No</th>
<th>Peminjam</th>
<th>Alat</th>
<th>Tanggal Pinjam</th>
<th>Tanggal Kembali</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php $no=1; mysqli_data_seek($query,0); while($d=mysqli_fetch_assoc($query)): ?>

<tr>

<td><?= $no++ ?></td>

<td class="fw-semibold"><?= $d['nama_lengkap']; ?></td>

<td><?= $d['nama_alat']; ?></td>

<td><?= date('d/m/Y',strtotime($d['tgl_pinjam'])); ?></td>

<td>

<?= (!empty($d['tgl_kembali_asli']))
? date('d/m/Y',strtotime($d['tgl_kembali_asli']))
: '<span class="opacity-75">Belum kembali</span>' ?>

</td>

<td>

<?php
$class="status-disetujui";
if($d['status']=="Kembali") $class="status-kembali";
if($d['status']=="Ditolak") $class="status-ditolak";
?>

<span class="badge-status <?= $class ?>">
<?= $d['status']; ?>
</span>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>


<!-- TANDA TANGAN PRINT -->

<div class="row mt-5 d-none d-print-flex">

<div class="col-8"></div>

<div class="col-4 text-center">

<p>Bogor, <?= date('d M Y'); ?><br>Petugas</p>

<br><br>

<p class="fw-bold">( ________________ )</p>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>