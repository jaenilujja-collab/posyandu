<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* ambil id */
$id = (int)($_GET['id'] ?? 0);

if($id <= 0){
    header("Location: ../../index.php?page=anak&msg=error");
    exit;
}

/* ambil data anak */
$stmt = $conn->prepare("SELECT * FROM anak WHERE id_anak=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if(!$data){
    header("Location: ../../index.php?page=anak&msg=error");
    exit;
}

/* data rt */
$rt = $conn->query("SELECT * FROM rt ORDER BY nomor_rt");
?>

<style>
.wrap{
max-width:1100px;
margin:auto;
}

.top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
flex-wrap:wrap;
gap:10px;
}

.top h2{
font-size:30px;
margin:0;
color:#0f172a;
}

.sub{
font-size:14px;
color:#64748b;
}

.back{
padding:12px 18px;
background:#e2e8f0;
text-decoration:none;
border-radius:12px;
font-weight:700;
color:#0f172a;
}

.form-box{
background:#fff;
padding:28px;
border-radius:22px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:18px;
}

.group{
display:flex;
flex-direction:column;
gap:8px;
}

.group label{
font-size:14px;
font-weight:600;
color:#334155;
}

.group input,
.group select,
.group textarea{
padding:13px;
border:1px solid #dbe2ea;
border-radius:14px;
font-size:14px;
outline:none;
transition:.2s;
}

.group input:focus,
.group select:focus,
.group textarea:focus{
border-color:#2563eb;
box-shadow:0 0 0 3px rgba(37,99,235,.08);
}

.group textarea{
min-height:120px;
resize:vertical;
}

.full{
grid-column:1 / -1;
}

.action{
margin-top:25px;
display:flex;
gap:12px;
flex-wrap:wrap;
}

.save{
border:none;
padding:14px 24px;
border-radius:14px;
background:linear-gradient(135deg,#2563eb,#16a34a);
color:#fff;
font-weight:700;
cursor:pointer;
font-size:14px;
}

.delete{
padding:14px 24px;
border-radius:14px;
background:#fee2e2;
color:#dc2626;
text-decoration:none;
font-weight:700;
font-size:14px;
}

@media(max-width:900px){
.grid{
grid-template-columns:1fr;
}
}
</style>

<div class="wrap">

<div class="top">
<div>
<h2>Edit Data Anak</h2>
<div class="sub">Perbarui data bayi / balita</div>
</div>

<a href="index.php?page=anak" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/anak/update.php" class="form-box">

<input type="hidden" name="id_anak" value="<?= $data['id_anak']; ?>">

<div class="grid">

<div class="group">
<label>Nama Anak</label>
<input type="text" name="nama_anak" value="<?= $data['nama_anak']; ?>" required>
</div>

<div class="group">
<label>NIK</label>
<input type="text" name="nik" value="<?= $data['nik']; ?>">
</div>

<div class="group">
<label>RT</label>
<select name="id_rt" required>
<option value="">-- Pilih RT --</option>
<?php while($r = $rt->fetch_assoc()){ ?>
<option value="<?= $r['id_rt']; ?>" <?= $data['id_rt']==$r['id_rt']?'selected':''; ?>>
<?= $r['nomor_rt']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="group">
<label>Jenis Kelamin</label>
<select name="jenis_kelamin" required>
<option value="L" <?= $data['jenis_kelamin']=='L'?'selected':''; ?>>Laki-laki</option>
<option value="P" <?= $data['jenis_kelamin']=='P'?'selected':''; ?>>Perempuan</option>
</select>
</div>

<div class="group">
<label>Tempat Lahir</label>
<input type="text" name="tempat_lahir" value="<?= $data['tempat_lahir']; ?>">
</div>

<div class="group">
<label>Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" value="<?= $data['tanggal_lahir']; ?>" required>
</div>

<div class="group">
<label>Berat Lahir (Kg)</label>
<input type="number" step="0.01" name="berat_lahir" value="<?= $data['berat_lahir']; ?>">
</div>

<div class="group">
<label>Panjang Lahir (Cm)</label>
<input type="number" step="0.01" name="panjang_lahir" value="<?= $data['panjang_lahir']; ?>">
</div>

<div class="group">
<label>Lingkar Kepala (Cm)</label>
<input type="number" step="0.01" name="lingkar_kepala" value="<?= $data['lingkar_kepala']; ?>">
</div>

<div class="group">
<label>ASI Eksklusif</label>
<select name="asi_eksklusif">
<option value="Ya" <?= $data['asi_eksklusif']=='Ya'?'selected':''; ?>>Ya</option>
<option value="Tidak" <?= $data['asi_eksklusif']=='Tidak'?'selected':''; ?>>Tidak</option>
</select>
</div>

<div class="group">
<label>Golongan Darah</label>
<select name="gol_darah">
<option value="">- Pilih -</option>
<option value="A" <?= $data['gol_darah']=='A'?'selected':''; ?>>A</option>
<option value="B" <?= $data['gol_darah']=='B'?'selected':''; ?>>B</option>
<option value="AB" <?= $data['gol_darah']=='AB'?'selected':''; ?>>AB</option>
<option value="O" <?= $data['gol_darah']=='O'?'selected':''; ?>>O</option>
</select>
</div>

<div class="group">
<label>Status Stunting</label>
<select name="status_stunting">
<option value="Tidak" <?= $data['status_stunting']=='Tidak'?'selected':''; ?>>Tidak</option>
<option value="Ya" <?= $data['status_stunting']=='Ya'?'selected':''; ?>>Ya</option>
</select>
</div>

<div class="group full">
<label>Alergi / Catatan</label>
<textarea name="alergi"><?= $data['alergi']; ?></textarea>
</div>

</div>

<div class="action">

<button type="submit" class="save">
💾 Update Data
</button>

<a href="modules/anak/hapus.php?id=<?= $data['id_anak']; ?>"
onclick="return confirm('Yakin ingin menghapus data ini?')"
class="delete">
🗑️ Hapus
</a>

</div>

</form>

</div>