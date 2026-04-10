<?php
session_start();
if (!isset($_SESSION['level'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

$level = $_SESSION['level'];
$nama = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

$q_alat = mysqli_query($conn,"SELECT COUNT(*) as total FROM alat");
$d_alat = mysqli_fetch_assoc($q_alat);
$total_alat = $d_alat['total'];

$q_user = mysqli_query($conn,"SELECT COUNT(*) as total FROM users");
$d_user = mysqli_fetch_assoc($q_user);
$total_user = $d_user['total'];

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
box-sizing:border-box;
}

html,body{
overflow-x:hidden;
}
body{
margin:0;
font-family:'Poppins',sans-serif;
background:#0f172a;
color:white;
}

/* HERO */

.hero{
background:linear-gradient(135deg,#6366f1,#8b5cf6);
padding:40px;
border-radius:20px;
margin-bottom:30px;
}

.hero h1{
margin:0;
font-size:30px;
}

.hero p{
opacity:0.8;
}

/* GRID */

.grid{
display:grid;
grid-template-columns:2fr 1fr;
gap:25px;
}

/* PANEL */

.panel{
background:#1e293b;
padding:25px;
border-radius:18px;
}

/* QUICK ACTION */

.actions{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:15px;
margin-top:20px;
}

.action-btn{
background:#334155;
padding:20px;
border-radius:14px;
text-decoration:none;
color:white;
display:block;
transition:0.2s;
}

.action-btn:hover{
background:#475569;
transform:translateY(-3px);
}

/* TIMELINE */

.timeline{
margin-top:15px;
}

.timeline-item{
padding:15px 0;
border-bottom:1px solid #334155;
}

.timeline-item:last-child{
border:none;
}

.status{
padding:3px 10px;
border-radius:12px;
font-size:12px;
}

.disetujui{background:#1e40af;}
.kembali{background:#15803d;}
.menunggu{background:#b45309;}
.ditolak{background:#991b1b;}

/* STATS */

.stat{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.stat h2{
margin:0;
}

.progress{
height:8px;
background:#334155;
border-radius:10px;
overflow:hidden;
margin-top:8px;
}

.bar{
height:8px;
background:#6366f1;
width:60%;
}

/* RESPONSIVE */

@media (max-width:900px){

.grid{
grid-template-columns:1fr;
}

.actions{
grid-template-columns:1fr;
}

}

</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<!-- MAIN CONTENT (HARUS pakai class ini agar sidebar bekerja) -->
<div class="main-content">

<!-- HERO -->
<div class="hero">

<h1>Halo, <?= $nama ?> 👋</h1>

<p>Selamat datang di sistem peminjaman alat.</p>

</div>


<div class="grid">

<!-- LEFT PANEL -->

<div class="panel">

<h3>Quick Actions</h3>

<div class="actions">

<?php if($level=="Admin"){ ?>

<a class="action-btn" href="data_alat.php">
📦 Kelola Alat
</a>

<a class="action-btn" href="laporan_peminjaman.php">
📄 Data Peminjaman
</a>

<a class="action-btn" href="pengembalian.php">
🔁 Pengembalian
</a>

<a class="action-btn" href="data_user.php">
👤 Manajemen User
</a>

<?php } elseif($level=="Petugas"){ ?>

<a class="action-btn" href="data_alat.php">
📦 Kelola Alat
</a>

<a class="action-btn" href="laporan_peminjaman.php">
📄 Data Peminjaman
</a>

<a class="action-btn" href="pengembalian.php">
🔁 Pengembalian
</a>

<?php } elseif($level=="Peminjam"){ ?>

<a class="action-btn" href="peminjaman.php">
📝 Ajukan Peminjaman
</a>

<a class="action-btn" href="riwayat_pinjam.php">
📚 Riwayat Peminjaman
</a>

<?php } ?>

</div>


<h3 style="margin-top:35px;">Aktivitas Terbaru</h3>

<div class="timeline">

<?php

$where = ($level=='Peminjam') ? "WHERE p.id_user='$id_user'" : "";

$sql = "SELECT p.*,a.nama_alat,u.nama_lengkap
FROM peminjaman p
JOIN alat a ON p.id_alat=a.id_alat
JOIN users u ON p.id_user=u.id_user
$where
ORDER BY p.id_peminjaman DESC LIMIT 5";

$q = mysqli_query($conn,$sql);

while($d=mysqli_fetch_assoc($q)){

$status = strtolower($d['status']);

?>

<div class="timeline-item">

<b><?= $d['nama_alat'] ?></b>

<br>

<small><?= $d['nama_lengkap'] ?> • <?= $d['tgl_pinjam'] ?></small>

<br>

<span class="status <?= $status ?>">

<?= $d['status'] ?>

</span>

</div>

<?php } ?>

</div>

</div>


<!-- RIGHT PANEL -->

<div class="panel">

<h3>Statistik Sistem</h3>

<div class="stat">

<div>
Total Alat
<h2><?= $total_alat ?></h2>
</div>

</div>

<div class="progress">
<div class="bar"></div>
</div>

<br>

<div class="stat">

<div>
Total User
<h2><?= $total_user ?></h2>
</div>

</div>

<div class="progress">
<div class="bar" style="width:40%"></div>
</div>

<br>

<div class="stat">

<div>
Login Sebagai
<h2><?= $level ?></h2>
</div>

</div>

</div>

</div>

</div>

</body>
</html>