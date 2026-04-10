```php
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: dashboard.php");
    exit();
}

$level = $_SESSION['level'];
$nama  = $_SESSION['nama'];

$query = mysqli_query($conn, "SELECT p.*, u.nama_lengkap, a.nama_alat, a.stok_tersedia 
FROM peminjaman p 
JOIN users u ON p.id_user = u.id_user 
JOIN alat a ON p.id_alat = a.id_alat 
WHERE p.status = 'Menunggu' 
ORDER BY p.tgl_pinjam ASC");

$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Persetujuan Peminjaman</title>

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

/* STAT */

.stat-box{
background:#1e293b;
padding:18px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.05);
width:200px;
}

.stat-number{
font-size:24px;
font-weight:700;
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

/* STATUS */

.status-badge{
padding:6px 12px;
font-size:12px;
border-radius:20px;
background:#facc15;
color:black;
font-weight:600;
}

/* BUTTON */

.btn-modern{
border-radius:8px;
font-size:13px;
padding:6px 12px;
}

/* EMPTY */

.empty-box{
text-align:center;
padding:60px 0;
color:#94a3b8;
}

.info-box{
margin-top:25px;
background:#1e293b;
padding:18px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.05);
display:flex;
align-items:center;
gap:10px;
}

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="page-header">

<div>
<div class="page-title">✅ Persetujuan Peminjaman</div>
<div class="sub-text">Konfirmasi permintaan peminjaman alat</div>
</div>

<div class="stat-box">
<div class="sub-text">Permintaan Menunggu</div>
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
<th>Tanggal Pinjam</th>
<th>Status</th>
<th class="text-center">Aksi</th>
</tr>
</thead>

<tbody>

<?php if($total > 0): ?>

<?php while($d = mysqli_fetch_assoc($query)): ?>

<tr>

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

<small class="text-secondary">
Stok tersedia : <?= $d['stok_tersedia']; ?>
</small>

</td>

<td>
<div><?= date('d M Y', strtotime($d['tgl_pinjam'])); ?></div>
</td>

<td>
<span class="status-badge">
<?= $d['status']; ?>
</span>
</td>

<td class="text-center">

<a href="proses_konfirmasi.php?id=<?= $d['id_peminjaman']; ?>&status=Disetujui"
class="btn btn-success btn-modern">
Setujui
</a>

<a href="proses_konfirmasi.php?id=<?= $d['id_peminjaman']; ?>&status=Ditolak"
class="btn btn-danger btn-modern">
Tolak
</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="5">

<div class="empty-box">

<img src="https://cdn-icons-png.flaticon.com/512/10452/10452062.png"
width="80"
class="mb-3 opacity-50">

<p>Tidak ada permintaan peminjaman</p>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>


<div class="info-box">

<div style="font-size:22px;">💡</div>

<div>

Persetujuan akan otomatis mengurangi stok alat jika status berubah menjadi <b>Disetujui</b>

</div>

</div>

</div>

<script>

document.querySelectorAll('.btn-danger').forEach(btn=>{
btn.addEventListener('click',function(e){

e.preventDefault();

let link=this.href;

Swal.fire({
title:'Tolak peminjaman?',
icon:'warning',
showCancelButton:true
}).then((r)=>{
if(r.isConfirmed){
window.location=link;
}
});

});
});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```
