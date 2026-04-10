<?php
session_start();
include 'config.php';

if (!isset($_SESSION['level'])) {
    header("Location: index.php");
    exit();
}

$level = $_SESSION['level'];

$query = mysqli_query($conn,"SELECT alat.*,kategori.nama_kategori
FROM alat
LEFT JOIN kategori ON alat.id_kategori=kategori.id_kategori
WHERE status!='Dihapus'
ORDER BY nama_alat ASC");

$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Inventaris Alat</title>

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

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
flex-wrap:wrap;
gap:15px;
}

.title{
font-size:28px;
font-weight:700;
}

.search-box{
background:#1e293b;
border:none;
padding:10px 15px;
border-radius:10px;
color:white;
width:200px;
}

/* STAT */

.stat-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
margin-bottom:30px;
}

.stat-card{
background:#1e293b;
padding:20px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.06);
}

.stat-title{
font-size:13px;
color:#94a3b8;
}

.stat-number{
font-size:26px;
font-weight:700;
margin-top:5px;
}

/* GRID */

.inventory-grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
gap:20px;
}

/* CARD */

.inventory-card{
background:#1e293b;
border-radius:16px;
overflow:hidden;
border:1px solid rgba(255,255,255,0.05);
transition:.3s;
}

.inventory-card:hover{
transform:translateY(-6px);
box-shadow:0 12px 25px rgba(0,0,0,0.5);
}

.inventory-img{
height:150px;
width:100%;
object-fit:cover;
}

.inventory-body{
padding:16px;
}

.inventory-title{
font-size:18px;
font-weight:600;
}

.inventory-category{
font-size:12px;
color:#38bdf8;
margin-bottom:10px;
}

/* BADGE */

.badge-status{
font-size:11px;
padding:5px 10px;
border-radius:20px;
}

/* ACTION */

.card-actions{
margin-top:12px;
display:flex;
flex-wrap:wrap;
gap:6px;
}

.btn-modern{
font-size:12px;
border-radius:8px;
padding:5px 10px;
}

/* MODAL */

.modal-content{
background:#1e293b;
border:none;
border-radius:14px;
}

.form-control,.form-select{
background:#0f172a;
border:1px solid rgba(255,255,255,0.1);
color:white;
}

.form-control:focus,.form-select:focus{
background:#0f172a;
color:white;
border-color:#38bdf8;
box-shadow:none;
}

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<!-- HEADER -->

<div class="top-bar">

<div class="title">📦 Inventaris Alat</div>

<div class="d-flex gap-3">

<input type="text" id="searchAlat" placeholder="Cari alat..." class="search-box">

<?php if($level!='Peminjam'): ?>
<button class="btn btn-info text-dark"
data-bs-toggle="modal"
data-bs-target="#modalTambah">
+ Tambah Alat
</button>
<?php endif; ?>

</div>

</div>

<!-- STAT -->

<div class="stat-grid">

<div class="stat-card">
<div class="stat-title">Total Alat</div>
<div class="stat-number"><?= $total ?></div>
</div>

</div>

<!-- GRID -->

<div class="inventory-grid" id="alatList">

<?php while($row=mysqli_fetch_assoc($query)): ?>

<div class="inventory-card">

<img src="<?= $row['gambar'] ? $row['gambar'] : 'https://via.placeholder.com/300x200' ?>" class="inventory-img">

<div class="inventory-body">

<div class="inventory-title"><?= $row['nama_alat'] ?></div>

<div class="inventory-category"><?= $row['nama_kategori'] ?></div>

<div class="d-flex gap-2 flex-wrap">

<span class="badge bg-success badge-status">
Stok <?= $row['stok_tersedia'] ?>
</span>

<?php
$warna="secondary";
if($row['kondisi']=="Baik") $warna="success";
if($row['kondisi']=="Rusak Ringan") $warna="warning";
if($row['kondisi']=="Rusak Berat") $warna="danger";
?>

<span class="badge bg-<?= $warna ?> badge-status">
<?= $row['kondisi'] ?>
</span>

</div>

<div class="card-actions">

<a href="detail_alat.php?id=<?= $row['id_alat'] ?>"
class="btn btn-info btn-modern">
Detail
</a>

<?php if($level!='Peminjam'): ?>

<button
class="btn btn-warning btn-modern"
data-bs-toggle="modal"
data-bs-target="#edit<?= $row['id_alat'] ?>">
Edit
</button>

<a href="hapus_alat.php?id=<?= $row['id_alat'] ?>"
class="btn btn-danger btn-modern btn-hapus">
Hapus
</a>

<?php endif; ?>

</div>

</div>

</div>

<!-- MODAL EDIT -->

<div class="modal fade" id="edit<?= $row['id_alat'] ?>">

<div class="modal-dialog">

<div class="modal-content">

<form action="proses_alat.php" method="POST" enctype="multipart/form-data">

<div class="modal-header">
<h5>Edit Alat</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="id_alat" value="<?= $row['id_alat'] ?>">

<input type="text"
name="nama_alat"
class="form-control mb-2"
value="<?= $row['nama_alat'] ?>">

<select name="kondisi" class="form-select mb-2">

<option <?= $row['kondisi']=="Baik"?'selected':'' ?>>Baik</option>
<option <?= $row['kondisi']=="Rusak Ringan"?'selected':'' ?>>Rusak Ringan</option>
<option <?= $row['kondisi']=="Rusak Berat"?'selected':'' ?>>Rusak Berat</option>

</select>

<input type="number"
name="stok"
value="<?= $row['stok_tersedia'] ?>"
class="form-control mb-2">

<input type="file"
name="gambar"
class="form-control">

</div>

<div class="modal-footer">

<button type="submit"
name="update"
class="btn btn-info text-dark w-100">
Update
</button>

</div>

</form>

</div>
</div>
</div>

<?php endwhile; ?>

</div>

</div>

<!-- MODAL TAMBAH -->

<div class="modal fade" id="modalTambah">

<div class="modal-dialog">

<div class="modal-content">

<form action="proses_alat.php" method="POST" enctype="multipart/form-data">

<div class="modal-header">
<h5>Tambah Alat</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="text" name="nama_alat" class="form-control mb-2" placeholder="Nama alat">

<select name="id_kategori" class="form-select mb-2">

<?php 
$kat=mysqli_query($conn,"SELECT * FROM kategori");
while($k=mysqli_fetch_assoc($kat)){
echo "<option value='$k[id_kategori]'>$k[nama_kategori]</option>";
}
?>

</select>

<input type="number" name="stok" class="form-control mb-2" placeholder="Stok">

<textarea name="spek" class="form-control mb-2" placeholder="Spesifikasi"></textarea>

<select name="kondisi" class="form-select mb-2">
<option>Baik</option>
<option>Rusak Ringan</option>
<option>Rusak Berat</option>
</select>

<input type="file" name="gambar" class="form-control">

</div>

<div class="modal-footer">

<button type="submit" name="simpan"
class="btn btn-info text-dark w-100">
Simpan
</button>

</div>

</form>

</div>
</div>
</div>

<script>

document.getElementById("searchAlat").addEventListener("keyup",function(){

let value=this.value.toLowerCase();
let cards=document.querySelectorAll(".inventory-card");

cards.forEach(card=>{
let text=card.innerText.toLowerCase();
card.style.display=text.includes(value)?"block":"none";
});

});

document.querySelectorAll('.btn-hapus').forEach(btn=>{
btn.addEventListener('click',function(e){

e.preventDefault();
let link=this.href;

Swal.fire({
title:'Hapus alat?',
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