<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$search = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';

$sql = "
SELECT alat.*, kategori.nama_kategori
FROM alat
LEFT JOIN kategori 
ON alat.id_kategori = kategori.id_kategori
WHERE alat.stok_tersedia > 0
AND alat.status='Tersedia'
";

if($search!=''){
$sql .= " AND alat.nama_alat LIKE '%$search%'";
}

if($kategori!=''){
$sql .= " AND alat.id_kategori='$kategori'";
}

$sql .= " ORDER BY alat.nama_alat ASC";

$query = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Katalog Peminjaman Alat</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

:root{
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

.main-content{
margin-left:260px;
padding:30px;
}

.page-title{
font-size:26px;
font-weight:700;
}

.glass-card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(12px);
border-radius:18px;
padding:20px;
border:1px solid rgba(255,255,255,0.15);
transition:0.25s;
}

.glass-card:hover{
transform:translateY(-5px);
}

.img-thumb{
width:100%;
height:180px;
object-fit:cover;
border-radius:12px;
}

.modern-input{
background:rgba(255,255,255,0.12);
border:1px solid rgba(255,255,255,0.2);
color:white;
border-radius:10px;
}

.modern-input:focus{
background:rgba(255,255,255,0.18);
border-color:#0FFCBE;
color:white;
box-shadow:none;
}

select.modern-input option{
color:black;
}

.glass-modal{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
border:1px solid rgba(255,255,255,0.15);
border-radius:18px;
color:white;
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
<div class="page-title">📦 Katalog Peminjaman Alat</div>
<p class="opacity-75 mb-0">
Pilih alat dari katalog atau ajukan peminjaman
</p>
</div>

<button class="btn btn-light rounded-pill px-4 fw-bold"
data-bs-toggle="modal"
data-bs-target="#modalPinjam">
+ Ajukan Peminjaman
</button>

</div>


<!-- SEARCH & FILTER -->

<div class="glass-card mb-4">

<form method="GET" class="row g-3">

<div class="col-md-5">
<input type="text"
name="search"
value="<?= $search ?>"
class="form-control modern-input"
placeholder="Cari alat...">
</div>

<div class="col-md-4">

<select name="kategori" class="form-select modern-input">

<option value="">Semua Kategori</option>

<?php
$kategori_data = mysqli_query($conn,"SELECT * FROM kategori ORDER BY nama_kategori ASC");

while($k=mysqli_fetch_assoc($kategori_data)){

$selected = ($kategori == $k['id_kategori']) ? "selected" : "";

echo "<option value='".$k['id_kategori']."' $selected>
".$k['nama_kategori']."
</option>";

}
?>

</select>

</div>

<div class="col-md-3">
<button class="btn btn-light w-100 fw-bold">
Cari
</button>
</div>

</form>

</div>


<!-- KATALOG ALAT -->

<div class="row g-4">

<?php while($row=mysqli_fetch_assoc($query)): ?>

<div class="col-md-4">

<div class="glass-card h-100 text-center">

<?php if($row['gambar']){ ?>

<img src="<?= $row['gambar']; ?>" class="img-thumb">

<?php } ?>

<h5 class="mt-3 fw-bold">
<?= $row['nama_alat']; ?>
</h5>

<p class="text-info small">
<?= $row['nama_kategori']; ?>
</p>

<p class="small opacity-75">
<?= $row['spesifikasi']; ?>
</p>

<p class="mb-1">
Stok: <b><?= $row['stok_tersedia']; ?></b>
</p>

<?php

$warna="secondary";

if($row['kondisi']=="Baik") $warna="success";
if($row['kondisi']=="Rusak Ringan") $warna="warning";
if($row['kondisi']=="Rusak Berat") $warna="danger";

?>

<span class="badge bg-<?= $warna ?>">
<?= $row['kondisi']; ?>
</span>

<div class="mt-3">

<button class="btn btn-primary w-100"

data-bs-toggle="modal"
data-bs-target="#modalPinjam"

onclick="setAlat('<?= $row['id_alat']; ?>','<?= $row['nama_alat']; ?>')">

Pinjam Alat

</button>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

</div>

</div>


<!-- MODAL PINJAM -->

<div class="modal fade" id="modalPinjam">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content glass-modal">

<form action="proses_pinjam.php" method="POST">

<div class="modal-header border-0">

<h5 class="modal-title fw-bold">
📦 Ajukan Peminjaman
</h5>

<button type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">

<input type="hidden" name="id_alat" id="id_alat">

<div class="mb-3">

<label class="fw-semibold">Pilih Alat</label>

<select name="id_alat" id="dropdown_alat"
class="form-select modern-input" required>

<option value="">-- Pilih Alat --</option>

<?php
$alat=mysqli_query($conn,"
SELECT * FROM alat
WHERE stok_tersedia>0
AND status='Tersedia'
ORDER BY nama_alat ASC
");

while($a=mysqli_fetch_assoc($alat)){

echo "<option value='$a[id_alat]'>
$a[nama_alat] (Stok: $a[stok_tersedia])
</option>";

}
?>

</select>

</div>


<div class="mb-3">

<label class="fw-semibold">Tanggal Pinjam</label>

<input type="date"
name="tgl_pinjam"
value="<?= date('Y-m-d'); ?>"
class="form-control modern-input"
required>

</div>


<div class="mb-3">

<label class="fw-semibold">Tanggal Kembali</label>

<input type="date"
name="tgl_kembali_rencana"
min="<?= date('Y-m-d'); ?>"
class="form-control modern-input"
required>

</div>

</div>


<div class="modal-footer border-0">

<button type="button"
class="btn btn-light"
data-bs-dismiss="modal">
Batal
</button>

<button type="submit"
class="btn btn-primary px-4">
Ajukan
</button>

</div>

</form>

</div>

</div>

</div>


<script>

function setAlat(id,nama){

document.getElementById("id_alat").value=id;

document.getElementById("dropdown_alat").value=id;

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>