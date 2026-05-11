<?php
require_once __DIR__ . "/auth/auth.php";
require_login();

$page = $_GET['page'] ?? 'dashboard';

/* 🔥 FIX SESSION */
$user  = $_SESSION['user'];
$level = $user['level'];

/* ==================================
   ROUTER HALAMAN
================================== */
$pages = [

    'dashboard' => 'modules/dashboard/index.php',

    // anak
    'anak' => 'modules/anak/index.php',
    'anak_tambah' => 'modules/anak/tambah.php',
    'anak_edit' => 'modules/anak/edit.php',

    // ibu hamil
    'ibu_hamil' => 'modules/ibu_hamil/index.php',
    'ibu_hamil_tambah' => 'modules/ibu_hamil/tambah.php',
    'ibu_hamil_edit' => 'modules/ibu_hamil/edit.php',

    // imunisasi
    'imunisasi' => 'modules/imunisasi/index.php',
    'imunisasi_tambah' => 'modules/imunisasi/tambah.php',
    'imunisasi_edit' => 'modules/imunisasi/edit.php',

    // penimbangan
    'penimbangan' => 'modules/penimbangan/index.php',
    'penimbangan_tambah' => 'modules/penimbangan/tambah.php',
    'penimbangan_edit' => 'modules/penimbangan/edit.php',

    // vitamin
    'vitamin' => 'modules/vitamin/index.php',
    'vitamin_tambah' => 'modules/vitamin/tambah.php',
    'vitamin_edit' => 'modules/vitamin/edit.php',

    // pemeriksaan ibu
    'pemeriksaan_ibu' => 'modules/pemeriksaan_ibu/index.php',
    'pemeriksaan_ibu_tambah' => 'modules/pemeriksaan_ibu/tambah.php',
    'pemeriksaan_ibu_edit' => 'modules/pemeriksaan_ibu/edit.php',

    // kader
    'kader' => 'modules/kader/index.php',
    'kader_tambah' => 'modules/kader/tambah.php',
    'kader_edit' => 'modules/kader/edit.php',

    // laporan
    'laporan' => 'modules/laporan/index.php'
];

$file = $pages[$page] ?? null;

/* ==================================================
   BLOKIR HALAMAN ADMIN
   kader masih boleh lihat data kader
================================================== */
$admin_only = ['kader_tambah','kader_edit'];

if ($level == 'kader' && in_array($page, $admin_only)) {
    $file = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>POSYANDU DIGITAL DUSUN KEKAIT II</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

:root{
--primary:#2563eb;
--green:#16a34a;
--dark:#0f172a;
--text:#334155;
--muted:#64748b;
--bg:#f1f5f9;
--white:#ffffff;
}

body{
background:var(--bg);
color:var(--text);
}

.wrapper{
display:flex;
min-height:100vh;
}

/* Sidebar */
.sidebar{
width:280px;
background:#fff;
border-right:1px solid #e5e7eb;
display:flex;
flex-direction:column;
position:fixed;
top:0;
left:0;
bottom:0;
z-index:100;
}

.brand{
padding:24px;
border-bottom:1px solid #eef2f7;
}

.brand h2{
font-size:22px;
line-height:1.2;
font-weight:700;
color:#0f172a;
}

.brand p{
margin-top:6px;
font-size:12px;
color:#64748b;
}

.menu-scroll{
flex:1;
overflow:auto;
padding:18px;
}

.menu-group{
margin-bottom:22px;
}

.menu-title{
font-size:12px;
font-weight:700;
color:#94a3b8;
text-transform:uppercase;
letter-spacing:.8px;
margin-bottom:10px;
padding-left:8px;
}

.menu-group a{
display:block;
padding:12px 14px;
margin-bottom:8px;
border-radius:14px;
text-decoration:none;
font-size:14px;
font-weight:600;
color:#475569;
transition:.2s;
}

.menu-group a:hover{
background:#f8fafc;
transform:translateX(3px);
}

.menu-group a.active{
background:linear-gradient(135deg,#2563eb,#16a34a);
color:#fff;
box-shadow:0 10px 20px rgba(37,99,235,.15);
}

.logout{
padding:18px;
border-top:1px solid #eef2f7;
}

.logout a{
display:block;
text-align:center;
padding:13px;
border-radius:14px;
background:#fee2e2;
color:#dc2626;
text-decoration:none;
font-weight:700;
}

/* Main */
.main{
margin-left:280px;
flex:1;
min-width:0;
}

/* Header */
.header{
height:72px;
background:#fff;
border-bottom:1px solid #e5e7eb;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 25px;
position:sticky;
top:0;
z-index:90;
}

.header h3{
font-size:18px;
font-weight:700;
}

.user{
display:flex;
align-items:center;
gap:12px;
}

.avatar{
width:42px;
height:42px;
border-radius:50%;
background:linear-gradient(135deg,#16a34a,#2563eb);
color:#fff;
display:flex;
align-items:center;
justify-content:center;
font-weight:700;
}

.content{
padding:25px;
}

.notfound{
background:#fff;
padding:30px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
text-align:center;
}

@media(max-width:900px){
.sidebar{width:220px;}
.main{margin-left:220px;}
}

@media(max-width:768px){
.sidebar{display:none;}
.main{margin-left:0;}
.header{padding:0 15px;}
.content{padding:15px;}
}
</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<div class="brand">
<h2>POSYANDU DIGITAL<br>DUSUN KEKAIT II</h2>
<p>Sistem Pelayanan Modern</p>
</div>

<div class="menu-scroll">

<!-- MENU UTAMA -->
<div class="menu-group">
<div class="menu-title">Menu Utama</div>

<a href="index.php?page=dashboard" class="<?= $page=='dashboard'?'active':''; ?>">🏠 Dashboard</a>
<a href="index.php?page=anak" class="<?= $page=='anak'?'active':''; ?>">🧒 Data Anak</a>
<a href="index.php?page=ibu_hamil" class="<?= $page=='ibu_hamil'?'active':''; ?>">🤰 Ibu Hamil</a>

</div>

<!-- PELAYANAN -->
<div class="menu-group">
<div class="menu-title">Pelayanan</div>

<a href="index.php?page=imunisasi" class="<?= $page=='imunisasi'?'active':''; ?>">💉 Imunisasi</a>
<a href="index.php?page=penimbangan" class="<?= $page=='penimbangan'?'active':''; ?>">⚖️ Penimbangan</a>
<a href="index.php?page=vitamin" class="<?= $page=='vitamin'?'active':''; ?>">💊 Vitamin</a>
<a href="index.php?page=pemeriksaan_ibu" class="<?= $page=='pemeriksaan_ibu'?'active':''; ?>">🩺 Pemeriksaan Ibu</a>

</div>

<!-- ADMIN ONLY -->
<?php if($level=='admin'): ?>
<div class="menu-group">
<div class="menu-title">Admin</div>

<a href="index.php?page=kader" class="<?= $page=='kader'?'active':''; ?>">👥 Kader</a>
<a href="index.php?page=laporan" class="<?= $page=='laporan'?'active':''; ?>">📄 Laporan</a>

</div>
<?php endif; ?>

<!-- KADER ONLY -->
<?php if($level=='kader'): ?>
<div class="menu-group">
<div class="menu-title">Laporan</div>

<a href="index.php?page=laporan" class="<?= $page=='laporan'?'active':''; ?>">📄 Laporan</a>

</div>
<?php endif; ?>

</div>

<div class="logout">
<a href="auth/logout.php">🚪 Logout</a>
</div>

</div>

<!-- MAIN -->
<div class="main">

<div class="header">

<h3><?= ucwords(str_replace('_',' ',$page)); ?></h3>

<div class="user">
<div>
<div style="font-size:12px;color:#64748b;">Login sebagai</div>
<div style="font-size:14px;font-weight:700;">
<?= $user['nama']; ?> (<?= strtoupper($level); ?>)
</div>
</div>

<div class="avatar">
<?= strtoupper(substr($user['nama'],0,1)); ?>
</div>
</div>

</div>

<div class="content">

<?php
if ($file && file_exists($file)) {
    include $file;
} else {
?>
<div class="notfound">
<h2>404</h2>
<p>Halaman tidak ditemukan / akses ditolak.</p>
</div>
<?php } ?>

</div>

</div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
</html>