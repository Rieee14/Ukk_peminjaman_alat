<?php
session_start();
include 'config.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'Admin') {
    header("Location: dashboard.php");
    exit();
}

$query = mysqli_query($conn,"SELECT l.*,u.nama_lengkap,u.level 
FROM log_aktivitas l
JOIN users u ON l.id_user=u.id_user
ORDER BY l.waktu DESC");

$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Log Aktivitas</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
font-family:'Inter',sans-serif;
background:#0f172a;
color:white;
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

/* HEADER */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.page-title{
font-size:28px;
font-weight:700;
}

/* STATS */

.stats-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:20px;
margin-bottom:30px;
}

.stat-card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.08);
border-radius:15px;
padding:20px;
backdrop-filter:blur(12px);
}

.stat-number{
font-size:24px;
font-weight:700;
}

.stat-label{
font-size:13px;
color:#94a3b8;
}

/* SEARCH */

.search-box{
background:rgba(255,255,255,0.08);
border:none;
padding:10px 15px;
border-radius:10px;
color:white;
}

/* ACTIVITY */

.activity-container{
margin-top:10px;
}

.activity-item{
display:flex;
gap:20px;
margin-bottom:25px;
}

.activity-time{
width:80px;
font-size:13px;
color:#94a3b8;
}

.activity-dot{
width:12px;
height:12px;
background:#38bdf8;
border-radius:50%;
margin-top:6px;
}

.activity-card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.08);
border-radius:14px;
padding:15px;
flex:1;
transition:0.3s;
}

.activity-card:hover{
transform:translateY(-3px);
box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

.activity-user{
font-weight:600;
}

.activity-aksi{
color:#38bdf8;
font-weight:500;
}

/* BUTTON */

.btn-print{
background:#38bdf8;
border:none;
color:black;
font-weight:600;
}

</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<!-- HEADER -->

<div class="page-header">

<div>

<div class="page-title">📜 Log Aktivitas</div>
<div class="text-secondary">Rekam semua aktivitas pengguna</div>

</div>

<div class="d-flex gap-3">

<input type="text"
id="searchLog"
placeholder="Cari aktivitas..."
class="search-box">

<button onclick="window.print()"
class="btn btn-print">
🖨 Cetak
</button>

</div>

</div>


<!-- STATS -->

<div class="stats-grid">

<div class="stat-card">
<div class="stat-number"><?= $total ?></div>
<div class="stat-label">Total Aktivitas</div>
</div>

<div class="stat-card">
<div class="stat-number">Admin</div>
<div class="stat-label">Pengguna Admin</div>
</div>

<div class="stat-card">
<div class="stat-number">Petugas</div>
<div class="stat-label">Pengguna Sistem</div>
</div>

</div>


<!-- ACTIVITY FEED -->

<div class="activity-container" id="logList">

<?php if($total > 0): ?>

<?php while($l = mysqli_fetch_assoc($query)): ?>

<div class="activity-item">

<div class="activity-time">

<?= date('H:i',strtotime($l['waktu'])) ?>

<br>

<small><?= date('d M',strtotime($l['waktu'])) ?></small>

</div>

<div class="activity-dot"></div>

<div class="activity-card">

<div class="d-flex justify-content-between mb-1">

<span class="activity-user">

<?= $l['nama_lengkap']; ?>

</span>

<span class="badge bg-secondary">

<?= $l['level']; ?>

</span>

</div>

<div>

<span class="text-secondary">Aksi :</span>

<span class="activity-aksi">

<?= $l['aksi']; ?>

</span>

</div>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="text-center py-5 text-secondary">
Belum ada aktivitas sistem
</div>

<?php endif; ?>

</div>

</div>


<script>

/* SEARCH */

document.getElementById("searchLog").addEventListener("keyup",function(){

let value=this.value.toLowerCase();

let items=document.querySelectorAll(".activity-item");

items.forEach(item=>{

let text=item.innerText.toLowerCase();

item.style.display=text.includes(value)?"flex":"none";

});

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>