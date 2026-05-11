<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("
SELECT
kader.*,
users.id_user,
users.username,
users.level,
users.status
FROM kader
LEFT JOIN users ON users.id_kader = kader.id_kader
WHERE kader.id_kader=?
LIMIT 1
");

$stmt->bind_param("i",$id);
$stmt->execute();

$data = $stmt->get_result();
$row  = $data->fetch_assoc();

if(!$row){
    echo "<div style='padding:20px'>Data tidak ditemukan</div>";
    exit;
}
?>

<style>
.wrap{
max-width:900px;
margin:auto;
background:#fff;
padding:30px;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.top{
margin-bottom:25px;
}

.top h2{
margin:0;
font-size:30px;
color:#0f172a;
}

.sub{
color:#64748b;
margin-top:6px;
font-size:14px;
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

.part{
background:#f8fafc;
padding:22px;
border-radius:16px;
}

.part h3{
margin:0 0 18px;
font-size:18px;
color:#2563eb;
}

.input{
margin-bottom:15px;
}

.input label{
display:block;
font-size:14px;
font-weight:600;
margin-bottom:7px;
color:#334155;
}

.input input,
.input select{
width:100%;
padding:12px;
border:1px solid #dbe2ea;
border-radius:12px;
font-size:14px;
}

.action{
display:flex;
gap:12px;
margin-top:25px;
}

.btn{
flex:1;
padding:13px;
border:none;
border-radius:14px;
font-weight:700;
cursor:pointer;
font-size:14px;
}

.save{
background:#16a34a;
color:#fff;
}

.back{
background:#e2e8f0;
color:#111827;
text-decoration:none;
text-align:center;
line-height:45px;
}

@media(max-width:900px){
.grid{grid-template-columns:1fr;}
}
</style>

<div class="wrap">

<div class="top">
<h2>Edit Data Kader</h2>
<div class="sub">Perbarui data kader dan akun login</div>
</div>

<form method="POST" action="modules/kader/update.php">

<input type="hidden" name="id_kader" value="<?= $row['id_kader']; ?>">
<input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">

<div class="grid">

<div class="part">
<h3>Informasi Kader</h3>

<div class="input">
<label>Nama Kader</label>
<input type="text" name="nama_kader"
value="<?= $row['nama_kader']; ?>" required>
</div>

<div class="input">
<label>No HP</label>
<input type="text" name="no_hp"
value="<?= $row['no_hp']; ?>">
</div>

<div class="input">
<label>Level</label>
<select name="level">
<option value="admin" <?= $row['level']=='admin'?'selected':''; ?>>Admin</option>
<option value="kader" <?= $row['level']=='kader'?'selected':''; ?>>Kader</option>
</select>
</div>

</div>

<div class="part">
<h3>Akun Login</h3>

<div class="input">
<label>Username</label>
<input type="text" name="username"
value="<?= $row['username']; ?>" required>
</div>

<div class="input">
<label>Password Baru</label>
<input type="password" name="password"
placeholder="Kosongkan jika tidak diubah">
</div>

<div class="input">
<label>Status</label>
<select name="status">
<option value="aktif" <?= $row['status']=='aktif'?'selected':''; ?>>Aktif</option>
<option value="nonaktif" <?= $row['status']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
</select>
</div>

</div>

</div>

<div class="action">

<a href="index.php?page=kader" class="btn back">
Kembali
</a>

<button type="submit" class="btn save">
Update Data
</button>

</div>

</form>

</div>