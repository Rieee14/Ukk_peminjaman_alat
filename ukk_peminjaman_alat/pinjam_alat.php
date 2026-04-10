<?php
session_start();
include 'config.php';
if ($_SESSION['level'] != 'Peminjam') header("Location: dashboard.php");

$id_user = $_SESSION['id_user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pinjam Alat - SMK Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-control, .form-select { border-radius: 10px; padding: 12px; border: 1px solid #e0e0e0; }
        .btn-primary { border-radius: 10px; padding: 12px; font-weight: bold; background: #007bff; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card p-4 p-md-5">
                <h3 class="fw-bold text-dark mb-1">🛒 Form Peminjaman</h3>
                <p class="text-muted mb-4">Silakan pilih alat dan tentukan durasi peminjaman.</p>

                <form action="proses_pinjam.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Pilih Alat</label>
                        <select name="id_alat" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Alat yang Tersedia --</option>
                            <?php 
                            $alat = mysqli_query($conn, "SELECT * FROM alat WHERE stok_tersedia > 0 ORDER BY nama_alat ASC");
                            while($a = mysqli_fetch_assoc($alat)) {
                                echo "<option value='".$a['id_alat']."'>".$a['nama_alat']." (Tersedia: ".$a['stok_tersedia'].")</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Rencana Kembali</label>
                            <input type="date" name="tgl_kembali_rencana" class="form-control" required>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 py-2 small">
                        * Peminjaman akan diverifikasi oleh petugas sebelum disetujui.
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" name="pinjam" class="btn btn-primary">Ajukan Peminjaman Sekarang</button>
                        <a href="dashboard.php" class="btn btn-link text-muted mt-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>