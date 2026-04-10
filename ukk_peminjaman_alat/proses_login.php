<?php
session_start();
include 'config.php';

// Ambil data dari form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Ambil user berdasarkan username saja
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$data  = mysqli_fetch_assoc($query);

// Output HTML SweetAlert
echo "<!DOCTYPE html>
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>";

if ($data) {

    // 🔐 CEK PASSWORD HASH
    if (password_verify($password, $data['password'])) {

        // 🚫 CEK STATUS
        if ($data['status'] == 'Nonaktif') {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Akun Nonaktif',
                    text: 'Akun Anda telah dinonaktifkan oleh admin!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
            exit();
        }

        // ✅ Simpan ke session
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama']     = $data['nama_lengkap'];
        $_SESSION['level']    = $data['level'];
        $_SESSION['id_user']  = $data['id_user'];

        // ✅ Notifikasi sukses
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: 'Selamat Datang, " . $data['nama_lengkap'] . "!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'dashboard.php';
            });
        </script>";

    } else {
        // ❌ Password salah
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Password salah!',
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    }

} else {
    // ❌ Username tidak ditemukan
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: 'Username tidak ditemukan!',
            confirmButtonText: 'Coba Lagi'
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>";
}

echo "</body></html>";
?>