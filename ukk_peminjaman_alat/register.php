<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | Sistem Peminjaman Alat</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

:root{
--primary:#2563eb;
--accent:#22c55e;
--dark:#0f172a;
--bg:#f1f5f9;
}

body{
margin:0;
height:100vh;
font-family:'Inter',sans-serif;
background:linear-gradient(135deg,#2563eb,#22c55e);
display:flex;
align-items:center;
justify-content:center;
}

/* container */

.register-container{
width:100%;
max-width:420px;
}

/* card */

.register-card{
background:rgba(255,255,255,0.9);
backdrop-filter:blur(14px);
padding:40px;
border-radius:20px;
box-shadow:0 20px 40px rgba(0,0,0,0.15);
}

/* logo */

.logo{
width:70px;
height:70px;
background:linear-gradient(135deg,#2563eb,#22c55e);
border-radius:20px;
display:flex;
align-items:center;
justify-content:center;
color:white;
font-size:28px;
font-weight:bold;
margin:auto;
margin-bottom:20px;
}

/* title */

.title{
text-align:center;
font-weight:700;
color:var(--dark);
}

.subtitle{
text-align:center;
color:#64748b;
margin-bottom:25px;
}

/* input */

.form-control{
border-radius:12px;
padding:12px;
border:1px solid #e2e8f0;
}

.form-control:focus{
border-color:var(--primary);
box-shadow:0 0 0 3px rgba(37,99,235,0.2);
}

/* button */

.btn-register{
background:linear-gradient(135deg,#2563eb,#22c55e);
border:none;
border-radius:12px;
padding:12px;
font-weight:600;
color:white;
transition:0.3s;
}

.btn-register:hover{
transform:translateY(-2px);
box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

/* link */

.login-link{
text-align:center;
margin-top:20px;
}

.login-link a{
text-decoration:none;
font-weight:600;
color:var(--primary);
}

</style>
</head>

<body>

<div class="register-container">

<div class="register-card">

<div class="logo">
👤
</div>

<h3 class="title">Buat Akun Baru</h3>
<p class="subtitle">Daftar untuk menggunakan sistem peminjaman alat</p>

<form action="proses_register.php" method="POST">

<input type="text" name="nama_lengkap" class="form-control mb-3" placeholder="Nama Lengkap" required>

<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button class="btn btn-register w-100">
Daftar Sekarang
</button>

</form>

<div class="login-link">
Sudah punya akun? <br>
<a href="index.php">Login sekarang</a>
</div>

</div>

</div>

</body>
</html>