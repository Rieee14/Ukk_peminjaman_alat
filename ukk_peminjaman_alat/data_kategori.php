<?php
session_start();
include 'config.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] == 'Peminjam') {
    header("Location: dashboard.php");
    exit();
}

$query = mysqli_query($conn,"SELECT * FROM kategori ORDER BY nama_kategori ASC");
$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kategori Alat</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
font-family:'Inter',sans-serif;
background:#0f172a;
color:white;
}

/* CONTENT */

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

/* SEARCH */

.search-box{
background:rgba(255,255,255,0.08);
border:none;
padding:10px 15px;
border-radius:10px;
color:white;
}

/* CARD GRID */

.kategori-grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
gap:20px;
}

/* CATEGORY CARD */

.kategori-card{
background:rgba(255,255,255,0.06);
border:1px solid rgba(255,255,255,0.08);
border-radius:16px;
padding:20px;
transition:0.3s;
backdrop-filter:blur(12px);
}

.kategori-card:hover{
transform:translateY(-5px);
box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.kategori-icon{
font-size:26px;
margin-bottom:10px;
}

.kategori-name{
font-weight:600;
font-size:18px;
}

.kategori-actions{
margin-top:15px;
display:flex;
justify-content:space-between;
}

/* BUTTON */

.btn-modern{
border-radius:10px;
padding:6px 12px;
font-size:14px;
}

/* MODAL */

.modal-content{
background:#1e293b;
color:white;
border:none;
border-radius:15px;
}

.form-control{
background:#0f172a;
border:1px solid rgba(255,255,255,0.1);
color:white;
}

.form-control:focus{
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

<div class="page-header">

<div>

<div class="page-title">📁 Kategori Alat</div>
<div class="text-secondary">Total kategori : <?= $total ?></div>

</div>

<div class="d-flex gap-3">

<input type="text" id="searchKategori" placeholder="Cari kategori..."
class="search-box">

<button class="btn btn-info text-dark"
data-bs-toggle="modal"
data-bs-target="#modalTambah">
+ Tambah
</button>

</div>

</div>


<!-- GRID -->

<div class="kategori-grid" id="kategoriList">

<?php while($k = mysqli_fetch_assoc($query)): ?>

<div class="kategori-card">

<div class="kategori-icon">
📦
</div>

<div class="kategori-name">
<?= $k['nama_kategori']; ?>
</div>

<div class="kategori-actions">

<button class="btn btn-warning btn-modern"
onclick="openEdit('<?= $k['id_kategori']; ?>','<?= $k['nama_kategori']; ?>')">
Edit
</button>

<a href="hapus_kategori.php?id=<?= $k['id_kategori']; ?>"
class="btn btn-danger btn-modern"
onclick="return confirm('Hapus kategori?')">
Hapus
</a>

</div>

</div>

<?php endwhile; ?>

</div>

</div>


<!-- MODAL TAMBAH -->

<div class="modal fade" id="modalTambah">

<div class="modal-dialog">

<div class="modal-content">

<form action="simpan_kategori.php" method="POST">

<div class="modal-header">
<h5>Tambah Kategori</h5>
<button type="button" class="btn-close btn-close-white"
data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<label>Nama Kategori</label>

<input type="text"
name="nama_kategori"
class="form-control"
required>

</div>

<div class="modal-footer">

<button type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">
Batal
</button>

<button type="submit"
class="btn btn-info text-dark">
Simpan
</button>

</div>

</form>

</div>

</div>

</div>


<!-- MODAL EDIT -->

<div class="modal fade" id="modalEdit">

<div class="modal-dialog">

<div class="modal-content">

<form action="edit_kategori.php" method="POST">

<div class="modal-header">
<h5>Edit Kategori</h5>

<button type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<input type="hidden" name="id_kategori" id="edit_id">

<label>Nama Kategori</label>

<input type="text"
name="nama_kategori"
id="edit_nama"
class="form-control"
required>

</div>

<div class="modal-footer">

<button type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">
Batal
</button>

<button type="submit"
class="btn btn-warning">
Update
</button>

</div>

</form>

</div>

</div>

</div>


<script>

function openEdit(id,nama){

document.getElementById('edit_id').value=id;
document.getElementById('edit_nama').value=nama;

var modal=new bootstrap.Modal(document.getElementById('modalEdit'));
modal.show();

}


/* SEARCH */

document.getElementById('searchKategori').addEventListener('keyup',function(){

let value=this.value.toLowerCase();
let cards=document.querySelectorAll('.kategori-card');

cards.forEach(card=>{

let text=card.innerText.toLowerCase();

card.style.display=text.includes(value) ? "block":"none";

});

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>