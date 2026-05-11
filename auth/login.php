<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$error = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Posyandu Kekait Digital</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#2563eb,#16a34a);
padding:20px;
}

.card{
width:430px;
max-width:100%;
background:#fff;
padding:35px;
border-radius:24px;
box-shadow:0 25px 50px rgba(0,0,0,.18);
}

.logo{
text-align:center;
margin-bottom:25px;
}

.logo h1{
font-size:26px;
line-height:1.2;
font-weight:700;
color:#0f172a;
}

.logo p{
font-size:13px;
color:#64748b;
margin-top:6px;
}

.alert{
background:#fee2e2;
color:#dc2626;
padding:12px;
border-radius:12px;
margin-bottom:15px;
font-size:14px;
}

.group{margin-bottom:16px;}

label{
display:block;
margin-bottom:8px;
font-size:14px;
font-weight:600;
}

input{
width:100%;
padding:13px;
border:1px solid #dbe2ea;
border-radius:12px;
outline:none;
font-size:14px;
}

input:focus{
border-color:#2563eb;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:linear-gradient(135deg,#2563eb,#16a34a);
color:#fff;
font-weight:700;
font-size:15px;
cursor:pointer;
}

button:hover{
opacity:.95;
}

.demo{
margin-top:18px;
padding:12px;
background:#f8fafc;
border-radius:12px;
font-size:12px;
color:#475569;
line-height:1.8;
}

.footer{
margin-top:15px;
text-align:center;
font-size:12px;
color:#94a3b8;
}
</style>
</head>
<body>

<div class="card">

<div class="logo">
<h1>POSYANDU<br>KEKAIT DIGITAL</h1>
<p>Sistem Informasi Posyandu Modern</p>
</div>

<?php if($error){ ?>
<div class="alert">
Username atau password salah.
</div>
<?php } ?>

<form method="POST" action="login_process.php">

<div class="group">
<label>Username</label>
<input type="text" name="username" required>
</div>

<div class="group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<button type="submit">LOGIN</button>

</form>

<div class="demo">
<b>Demo Login:</b><br>
Silahkan Login Kader Posyandu<br>
</div>

<div class="footer">
© <?= date('Y'); ?> Posyandu Kekait Digital
</div>

</div>

</body>
</html>