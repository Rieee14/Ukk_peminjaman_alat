<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login & Register | Sistem Peminjaman Alat</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
height:100vh;
display:flex;
align-items:center;
justify-content:center;
background:linear-gradient(135deg,#2563eb,#22c55e);
}

/* MAIN CONTAINER */

.container{
width:900px;
height:550px;
background:white;
border-radius:20px;
overflow:hidden;
display:flex;
box-shadow:0 25px 40px rgba(0,0,0,0.2);
}

/* LEFT PANEL */

.left-panel{
width:50%;
background:linear-gradient(135deg,#2563eb,#22c55e);
color:white;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
text-align:center;
padding:40px;
transition:0.5s;
}

.left-panel h2{
font-weight:700;
margin-bottom:10px;
}

.left-panel p{
opacity:0.9;
margin-bottom:25px;
}

.switch-btn{
padding:10px 22px;
border:2px solid white;
background:none;
color:white;
border-radius:10px;
cursor:pointer;
font-weight:600;
}

/* FORM AREA */

.form-container{
width:50%;
position:relative;
overflow:hidden;
}

.forms{
display:flex;
width:200%;
height:100%;
transition:0.5s;
}

form{
width:50%;
padding:50px;
display:flex;
flex-direction:column;
justify-content:center;
}

form h2{
margin-bottom:20px;
color:#0f172a;
}

input{
padding:12px;
margin-bottom:15px;
border-radius:10px;
border:1px solid #e2e8f0;
}

input:focus{
border-color:#2563eb;
outline:none;
box-shadow:0 0 0 3px rgba(37,99,235,0.2);
}

button{
padding:12px;
border:none;
border-radius:10px;
background:linear-gradient(135deg,#2563eb,#22c55e);
color:white;
font-weight:600;
cursor:pointer;
}

/* ANIMATION */

.container.active .forms{
transform:translateX(-50%);
}

.container.active .left-panel{
background:linear-gradient(135deg,#22c55e,#2563eb);
}

</style>
</head>

<body>

<div class="container" id="container">

<!-- PANEL KIRI -->

<div class="left-panel">

<h2 id="panelTitle">Halo 👋</h2>

<p id="panelText">
Belum punya akun? Silakan daftar terlebih dahulu.
</p>

<button class="switch-btn" onclick="toggleForm()" id="switchBtn">
Daftar
</button>

</div>

<!-- FORM -->

<div class="form-container">

<div class="forms">

<!-- LOGIN -->

<form action="proses_login.php" method="POST">

<h2>Login</h2>

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Masuk</button>

</form>

<!-- REGISTER -->

<form action="proses_register.php" method="POST">

<h2>Register</h2>

<input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Daftar</button>

</form>

</div>

</div>

</div>

<script>

let isRegister=false;

function toggleForm(){

const container=document.getElementById("container");
const title=document.getElementById("panelTitle");
const text=document.getElementById("panelText");
const btn=document.getElementById("switchBtn");

container.classList.toggle("active");

isRegister=!isRegister;

if(isRegister){

title.innerText="Sudah punya akun?";
text.innerText="Silakan login untuk melanjutkan.";
btn.innerText="Login";

}else{

title.innerText="Halo 👋";
text.innerText="Belum punya akun? Silakan daftar terlebih dahulu.";
btn.innerText="Daftar";

}

}

</script>

</body>
</html>