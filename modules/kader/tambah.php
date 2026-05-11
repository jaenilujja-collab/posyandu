<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';
?>

<style>
.page-title{
margin-bottom:22px;
}

.page-title h2{
font-size:28px;
font-weight:800;
margin:0;
color:#0f172a;
}

.page-title p{
font-size:14px;
color:#64748b;
margin-top:6px;
}

.form-wrap{
background:#fff;
padding:28px;
border-radius:22px;
box-shadow:0 12px 28px rgba(0,0,0,.05);
}

.section{
margin-bottom:26px;
}

.section h3{
font-size:18px;
font-weight:700;
margin-bottom:16px;
padding-bottom:10px;
border-bottom:1px solid #eef2f7;
}

.grid-2{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

.item{
margin-bottom:14px;
}

label{
display:block;
font-size:14px;
font-weight:700;
margin-bottom:7px;
color:#334155;
}

input,select,textarea{
width:100%;
padding:12px 14px;
border:1px solid #dbe2ea;
border-radius:12px;
font-size:14px;
background:#fff;
outline:none;
transition:.2s;
}

input:focus,
select:focus,
textarea:focus{
border-color:#2563eb;
box-shadow:0 0 0 3px rgba(37,99,235,.08);
}

textarea{
min-height:110px;
resize:vertical;
}

small{
display:block;
margin-top:6px;
font-size:12px;
color:#64748b;
line-height:1.5;
}

.info-box{
background:#eff6ff;
border:1px solid #dbeafe;
padding:14px;
border-radius:14px;
font-size:14px;
line-height:1.7;
color:#1e3a8a;
margin-bottom:18px;
}

.actions{
display:flex;
gap:10px;
flex-wrap:wrap;
margin-top:10px;
}

.btn-back{
background:#e5e7eb;
color:#111827;
text-decoration:none;
padding:13px 18px;
border-radius:12px;
font-weight:700;
}

.btn-save{
border:none;
background:linear-gradient(135deg,#2563eb,#16a34a);
color:#fff;
padding:13px 22px;
border-radius:12px;
font-weight:800;
cursor:pointer;
}

.btn-save:hover{
opacity:.95;
}

@media(max-width:768px){
.grid-2{
grid-template-columns:1fr;
}
}
</style>

<div class="page-title">
<h2>Tambah Data Kader</h2>
<p>Tambah biodata kader sekaligus akun login sistem.</p>
</div>

<form method="POST" action="/posyandu/modules/kader/simpan.php">

<div class="form-wrap">

<!-- ==================== AKUN ==================== -->
<div class="section">
<h3>Informasi Akun Login</h3>

<div class="info-box">
Saat data disimpan, akun login otomatis dibuat di tabel <b>users</b>.
</div>

<div class="grid-2">

<div class="item">
<label>Nama Lengkap</label>
<input type="text" name="nama_kader" required placeholder="Contoh: Siti Aminah">
<small>Nama ini akan digunakan juga sebagai identitas akun.</small>
</div>

<div class="item">
<label>Username</label>
<input type="text" name="username" required placeholder="Contoh: siti01">
</div>

<div class="item">
<label>Password</label>
<input type="password" name="password" required placeholder="Minimal 4 karakter">
</div>

<div class="item">
<label>Level Akses</label>
<select name="level" required>
<option value="">Pilih Level</option>
<option value="admin">Admin</option>
<option value="kader">Kader</option>
</select>
</div>

<div class="item">
<label>No HP</label>
<input type="text" name="no_hp" placeholder="Contoh: 08123456789">
</div>

</div>
</div>

<!-- ==================== TOMBOL ==================== -->
<div class="actions">

<a href="index.php?page=kader" class="btn-back">
Kembali
</a>

<button type="submit" class="btn-save">
Simpan Data
</button>

</div>

</div>
</form>