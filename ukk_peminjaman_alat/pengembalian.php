```php
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
WHERE p.status = 'Disetujui' 
ORDER BY p.tgl_kembali_rencana ASC");

$today = date('Y-m-d');
$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pengembalian Alat</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

/* HEADER */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
flex-wrap:wrap;
gap:15px;
}

.page-title{
font-size:28px;
font-weight:700;
}

.sub-text{
color:#94a3b8;
font-size:14px;
}

/* CARD */

.main-card{
background:#1e293b;
border-radius:16px;
border:1px solid rgba(255,255,255,0.05);
padding:25px;
}

/* TABLE */

.table{
color:white;
}

.table thead{
color:#94a3b8;
font-size:12px;
text-transform:uppercase;
}

.table tbody tr{
transition:.2s;
}

.table tbody tr:hover{
background:#0f172a;
}

/* LATE */

.late-row{
background:rgba(239,68,68,0.15);
}

/* BUTTON */

.btn-modern{
border-radius:8px;
padding:6px 14px;
font-size:13px;
}

/* STATS */

.stats-grid{
margin-top:25px;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
}

.stat-box{
background:#1e293b;
padding:18px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.05);
}

.stat-number{
font-size:24px;
font-weight:700;
}

.empty-box{
text-align:center;
padding:60px 0;
color:#94a3b8;
}

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="page-header">

<div>
<div class="page-title">🔄 Pengembalian Alat</div>
<div class="sub-text">Proses pengembalian alat ke gudang</div>
</div>

<div class="stat-box">
<div class="sub-text">Belum Kembali</div>
<div class="stat-number"><?= $total ?></div>
</div>

</div>


<div class="main-card">

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>
<th>Peminjam</th>
<th>Alat</th>
<th>Batas Pengembalian</th>
<th class="text-end">Aksi</th>
</tr>

</thead>

<tbody>

<?php if($total > 0): ?>

<?php while($d = mysqli_fetch_assoc($query)):

$is_late = ($today > $d['tgl_kembali_rencana']);

?>

<tr class="<?= $is_late ? 'late-row' : ''; ?>">

<td>

<div class="fw-bold"><?= $d['nama_lengkap']; ?></div>

<small class="text-secondary">
ID Pinjam : #<?= $d['id_peminjaman']; ?>
</small>

</td>

<td>

<div class="text-info fw-semibold">
<?= $d['nama_alat']; ?>
</div>

</td>

<td>

<div class="<?= $is_late ? 'text-danger fw-bold' : '' ?>">
<?= date('d M Y', strtotime($d['tgl_kembali_rencana'])); ?>
</div>

<?php if($is_late): ?>

<span class="badge bg-danger">
Terlambat
</span>

<?php else: ?>

<small class="text-secondary">
Sedang dipinjam
</small>

<?php endif; ?>

</td>

<td class="text-end">

<a href="proses_kembali.php?id=<?= $d['id_peminjaman']; ?>"
class="btn btn-primary btn-modern btn-kembali">

Konfirmasi Kembali

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="4">

<div class="empty-box">

<img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png"
width="80"
class="mb-3 opacity-50">

<p>Semua alat sudah kembali</p>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>


<div class="stats-grid">

<div class="stat-box">

<div class="sub-text">Alat Belum Kembali</div>

<div class="stat-number"><?= $total ?></div>

</div>

<div class="stat-box">

<div class="sub-text">Status Sistem</div>

<span class="badge bg-success mt-2">
Siap Proses
</span>

</div>

</div>

</div>


<script>

document.querySelectorAll('.btn-kembali').forEach(btn=>{

btn.addEventListener('click',function(e){

e.preventDefault();

let url=this.href;

Swal.fire({
title:'Konfirmasi Pengembalian?',
text:'Pastikan alat sudah dicek',
icon:'question',
showCancelButton:true,
confirmButtonText:'Ya, Sudah Kembali'
}).then((r)=>{
if(r.isConfirmed){
window.location=url;
}
});

});

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```
