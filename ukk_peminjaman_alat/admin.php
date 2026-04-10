<?php
session_start();
include 'config.php';
if (!isset($_SESSION['level'])) header("Location: index.php");

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; // Halaman default
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - PINJOL ALAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }
        .sidebar { height: 100vh; width: 260px; position: fixed; background: #fff; border-right: 1px solid #e9ecef; z-index: 100; }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        .nav-link { color: #495057; font-weight: 600; padding: 12px 20px; border-radius: 8px; margin-bottom: 5px; }
        .nav-link.active { background: rgba(0, 123, 255, 0.1); color: #007bff; }
        .nav-link:hover { background: #f1f3f5; }
    </style>
</head>
<body>

<div class="sidebar p-3">
    <div class="px-3 mb-4"><h4 class="fw-bold text-primary">PINJOL ALAT</h4></div>
    <nav class="nav flex-column">
        <a class="nav-link <?= ($page == 'dashboard') ? 'active' : ''; ?>" href="admin.php?page=dashboard">📊 Dashboard</a>
        <a class="nav-link <?= ($page == 'user') ? 'active' : ''; ?>" href="admin.php?page=user">👥 Kelola User</a>
        <a class="nav-link <?= ($page == 'alat') ? 'active' : ''; ?>" href="admin.php?page=alat">📦 Stok Alat</a>
        <a class="nav-link <?= ($page == 'laporan') ? 'active' : ''; ?>" href="admin.php?page=laporan">📄 Laporan</a>
        <a class="nav-link text-danger mt-5" href="logout.php">🚪 Keluar</a>
    </nav>
</div>

<div class="main-content">
    <?php 
        // Logika pemanggilan halaman
        switch ($page) {
            case 'user':
                include 'pages/data_user.php';
                break;
            case 'alat':
                include 'pages/data_alat.php';
                break;
            case 'laporan':
                include 'pages/laporan.php';
                break;
            default:
                include 'pages/home.php'; // Isi dashboard utama
                break;
        }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>