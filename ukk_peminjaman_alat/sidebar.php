<?php
$current_page = basename($_SERVER['PHP_SELF']);
include 'config.php'; 
?>

<style>
:root {
    --primary-blue: #106EBE;
    --mint: #0FFCBE;
    --dark-blue: #0b5aa3;
}

/* HAMBURGER */
.hamburger {
    position: fixed;
    top: 20px;
    left: 20px;
    background: var(--mint);
    color: var(--primary-blue);
    border: none;
    border-radius: 10px;
    padding: 10px 12px;
    z-index: 1100;
    cursor: pointer;
    font-weight: bold;
}

/* SIDEBAR */
.sidebar {
    height: 100vh;
    width: 260px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #334155;
    color: white;
    padding: 1.5rem 1rem;
    transition: 0.3s;
}

/* HIDE */
.sidebar.hide {
    transform: translateX(-100%);
}

/* BRAND */
.brand-section {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 30px;
}

.brand-icon {
    width: 40px;
    height: 40px;
    color: var(--primary-blue);
    border-radius: 12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight: bold;
}

/* NAV */
.nav-link {
    color: rgba(255,255,255,0.85);
    font-weight: 600;
    padding: 10px 15px;
    border-radius: 12px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    text-decoration: none;
}

/* HOVER */
.nav-link:hover{
background:rgba(255,255,255,0.08);
color:white;
transform:translateX(5px);
}

/* ACTIVE */
.nav-link.active{
background:rgba(255,255,255,0.15);
color:var(--mint);
}

/* SUBMENU */
.submenu {
    display: none;
    padding-left: 10px;
}

.submenu a {
    display: block;
    padding: 8px 15px;
    font-size: 0.9rem;
    border-radius: 10px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
}

.submenu a:hover {
    background: rgba(255,255,255,0.1);
    color: var(--mint);
}

/* CONTENT */
.main-content {
    margin-left: 260px;
    padding: 40px;
    transition: 0.3s;
}

.main-content.full {
    margin-left: 0;
}

/* MOBILE */
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
    }
    .main-content {
        margin-left: 0;
    }
}
</style>

<!-- HAMBURGER -->
<button class="hamburger" onclick="toggleSidebar()">☰</button>

<!-- SIDEBAR -->
<div class="sidebar">

    <!-- BRAND -->
    <div class="brand-section">
        <div class="brand-icon"></div>
        <span class="fw-bold">PINJOL ALAT</span>
    </div>

    <!-- DASHBOARD -->
    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
        <span>📊 Dashboard</span>
    </a>

    <!-- PEMINJAM -->
    <?php if ($_SESSION['level'] == 'Peminjam'): ?>
    <div>
        <div class="nav-link" onclick="toggleMenu(this)">
            <span>🧾 Peminjaman</span>
            <span>▼</span>
        </div>
        <div class="submenu">
            <a href="peminjaman.php">➕ Ajukan</a>
            <a href="riwayat_pinjam.php">📜 Riwayat</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- ADMIN -->
    <?php if ($_SESSION['level'] == 'Admin'): ?>
    <div>
        <div class="nav-link" onclick="toggleMenu(this)">
            <span>⚙️ Administrasi</span>
        </div>
        <div class="submenu">
            <a href="data_user.php">👥 User</a>
            <a href="data_kategori.php">🏷️ Kategori</a>
            <a href="log_aksi.php">📋 Log</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- INVENTARIS -->
    <?php if ($_SESSION['level'] == 'Admin' || $_SESSION['level'] == 'Petugas'): ?>
    <div>
        <div class="nav-link" onclick="toggleMenu(this)">
            <span>📦 Inventaris</span>
        </div>
        <div class="submenu">
            <a href="data_alat.php">📦 Data Alat</a>
            <a href="konfirmasi.php">✅ Persetujuan</a>
            <a href="pengembalian.php">🔄 Pengembalian</a>
            <a href="laporan_peminjaman.php">📄 Laporan</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- LOGOUT -->
    <div class="mt-4">
        <a class="nav-link text-danger" href="logout.php">
            🚪 Logout
        </a>
    </div>

</div>

<!-- SCRIPT -->
<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('hide');
    document.querySelector('.main-content').classList.toggle('full');
}

/* DROPDOWN */
function toggleMenu(el){
    let submenu = el.nextElementSibling;
    submenu.style.display = (submenu.style.display === "block") ? "none" : "block";
}

/* AUTO CLOSE MOBILE */
document.addEventListener("click", function(e){
    const sidebar = document.querySelector('.sidebar');
    const btn = document.querySelector('.hamburger');

    if(!sidebar.contains(e.target) && !btn.contains(e.target)){
        if(window.innerWidth < 992){
            sidebar.classList.add('hide');
        }
    }
});
</script>