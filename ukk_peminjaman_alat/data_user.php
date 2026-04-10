<?php
session_start();
include 'config.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'Admin') {
    header("Location: dashboard.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM users ORDER BY level ASC, nama_lengkap ASC");

$total = mysqli_num_rows($query);
$admin = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE level='Admin'"));
$petugas = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE level='Petugas'"));
$peminjam = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE level='Peminjam'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manajemen User</title>

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
/* MODAL GLASS */

.glass-modal{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
border:1px solid rgba(255,255,255,0.15);
border-radius:18px;
color:white;
}

/* INPUT */

.form-group{
margin-bottom:18px;
}

.form-group label{
font-weight:600;
margin-bottom:5px;
display:block;
font-size:14px;
}

/* MODERN INPUT */

.modern-input{
background:rgba(255,255,255,0.12);
border:1px solid rgba(255,255,255,0.2);
color:white;
border-radius:10px;
padding:10px 12px;
}

.modern-input::placeholder{
color:rgba(255,255,255,0.6);
}

.modern-input:focus{
background:rgba(255,255,255,0.18);
border-color:#0FFCBE;
box-shadow:none;
color:white;
}

/* SELECT */

select.modern-input option{
color:black;
}
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

/* USER CARD */
.user-card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(10px);
border-radius:16px;
padding:20px;
border:1px solid rgba(255,255,255,0.15);
transition:.3s;
height:100%;
}

.user-card:hover{
transform:translateY(-5px);
}

/* AVATAR */
.avatar{
width:50px;
height:50px;
border-radius:14px;
display:flex;
align-items:center;
justify-content:center;
font-weight:700;
color:#0f2027;
font-size:20px;
background:var(--mint);
}

/* LEVEL BADGE */
.level-badge{
padding:5px 14px;
border-radius:30px;
font-size:12px;
font-weight:600;
}

.level-admin{
background:#ff4d4d;
color:white;
}

.level-petugas{
background:var(--primary);
color:white;
}

.level-peminjam{
background:var(--mint);
color:#003b3b;
}

/* SEARCH */
.search-box{
border-radius:30px;
padding:10px 18px;
border:none;
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

</style>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<div class="page-title">👥 Manajemen User</div>
<p class="text-light opacity-75 mb-0">Kelola pengguna sistem inventaris</p>
</div>

<button class="btn btn-light rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalUser">
+ Tambah User
</button>

</div>

<!-- STATISTIK -->

<div class="row mb-4 g-3">

<div class="col-md-3">
<div class="stat-card">
<div class="text-light opacity-75">Total User</div>
<h3><?= $total ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="stat-card">
<div class="text-light opacity-75">Admin</div>
<h3><?= $admin ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="stat-card">
<div class="text-light opacity-75">Petugas</div>
<h3><?= $petugas ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="stat-card">
<div class="text-light opacity-75">Peminjam</div>
<h3><?= $peminjam ?></h3>
</div>
</div>

</div>

<!-- SEARCH -->

<div class="glass-card mb-4">
<input type="text" class="form-control search-box" placeholder="Cari user...">
</div>

<!-- USER GRID -->

<div class="row g-4">

<?php mysqli_data_seek($query,0); while($u = mysqli_fetch_assoc($query)): ?>

<div class="col-md-4 col-lg-3">

<div class="user-card">

<div class="d-flex align-items-center mb-3">

<div class="avatar me-3">
<?= strtoupper(substr($u['nama_lengkap'],0,1)); ?>
</div>

<div>

<div class="fw-bold"><?= $u['nama_lengkap']; ?></div>

<small class="opacity-75">@<?= $u['username']; ?></small>

</div>

</div>

<?php
$levelClass="level-peminjam";
if($u['level']=="Admin") $levelClass="level-admin";
if($u['level']=="Petugas") $levelClass="level-petugas";
?>

<span class="level-badge <?= $levelClass ?>">
<?= $u['level']; ?>
</span>

<div class="mt-3 d-flex justify-content-between align-items-center">

<?php if($u['status']=="Aktif"): ?>
<span class="text-success small">● Aktif</span>
<?php else: ?>
<span class="text-danger small">● Nonaktif</span>
<?php endif; ?>

<?php if($u['id_user'] != $_SESSION['id_user']): ?>

<?php if($u['status']=="Aktif"): ?>

<a href="hapus_user.php?id=<?= $u['id_user']; ?>"
class="btn btn-sm btn-soft text-danger"
onclick="return confirm('Nonaktifkan user ini?')">
Nonaktifkan
</a>

<?php else: ?>

<a href="aktifkan_user.php?id=<?= $u['id_user']; ?>"
class="btn btn-sm btn-soft text-success"
onclick="return confirm('Aktifkan kembali user ini?')">
Aktifkan
</a>

<?php endif; ?>

<?php else: ?>

<span class="small opacity-75">Akun Anda</span>

<?php endif; ?>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

</div>

</div>

<!-- MODAL TAMBAH USER -->

<div class="modal fade" id="modalUser" tabindex="-1">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content glass-modal">

<form action="proses_user.php" method="POST">

<div class="modal-header border-0">

<h5 class="modal-title fw-bold">Tambah User Baru</h5>

<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">

<div class="form-group">

<label>Nama Lengkap</label>

<input type="text" name="nama" class="form-control modern-input" required>

</div>


<div class="form-group">

<label>Username</label>

<input type="text" name="user" class="form-control modern-input" required>

</div>


<div class="form-group">

<label>Password</label>

<input type="password" name="pass" class="form-control modern-input" required>

</div>


<div class="form-group">

<label>Level Akses</label>

<select name="level" class="form-select modern-input">

<option value="Admin">Admin</option>
<option value="Petugas">Petugas</option>
<option value="Peminjam">Peminjam</option>

</select>

</div>

</div>


<div class="modal-footer border-0">

<button type="button" class="btn btn-light" data-bs-dismiss="modal">
Batal
</button>

<button type="submit" name="simpan" class="btn btn-primary px-4">
Simpan User
</button>

</div>

</form>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>