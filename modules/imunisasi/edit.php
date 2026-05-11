<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

/* ambil data imunisasi */
$stmt = $conn->prepare("
SELECT 
imunisasi.*,
anak.nama_anak,
anak.id_rt,
rt.nomor_rt
FROM imunisasi
LEFT JOIN anak ON anak.id_anak = imunisasi.id_anak
LEFT JOIN rt ON rt.id_rt = anak.id_rt
WHERE imunisasi.id_imunisasi=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();

if(!$data){
echo "<script>
alert('Data tidak ditemukan');
location='index.php?page=imunisasi';
</script>";
exit;
}

/* semua anak */
$anak = $conn->query("
SELECT 
anak.*,
rt.nomor_rt,
TIMESTAMPDIFF(MONTH, anak.tanggal_lahir, CURDATE()) AS umur_bulan
FROM anak
LEFT JOIN rt ON rt.id_rt = anak.id_rt
ORDER BY anak.nama_anak ASC
");
?>

<style>
.wrap{max-width:1100px;margin:auto;}
.top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
gap:10px;
flex-wrap:wrap;
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
}
.group textarea{
min-height:120px;
resize:vertical;
}
.info{
background:#eff6ff;
padding:16px;
border-radius:14px;
margin-top:15px;
font-size:14px;
line-height:1.7;
color:#1e3a8a;
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
}
.delete{
padding:14px 24px;
border-radius:14px;
background:#fee2e2;
color:#dc2626;
font-weight:700;
text-decoration:none;
}
@media(max-width:900px){
.grid{grid-template-columns:1fr;}
}
</style>

<div class="wrap">

<div class="top">
<div>
<h2>Edit Imunisasi</h2>
<div class="sub">Perbarui data pelayanan imunisasi anak</div>
</div>

<a href="index.php?page=imunisasi" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/imunisasi/update.php" class="form-box">

<input type="hidden" name="id_imunisasi" value="<?= $data['id_imunisasi']; ?>">

<div class="grid">

<div class="group full">
<label>Pilih Anak</label>

<select name="id_anak" id="anak" onchange="isiData()" required>

<?php while($r = $anak->fetch_assoc()){ ?>

<option
value="<?= $r['id_anak']; ?>"
<?= $r['id_anak']==$data['id_anak']?'selected':''; ?>
data-umur="<?= $r['umur_bulan']; ?>"
data-nama="<?= $r['nama_anak']; ?>"
data-rt="<?= $r['nomor_rt']; ?>"
>
<?= $r['nama_anak']; ?> | RT <?= $r['nomor_rt']; ?> | <?= $r['umur_bulan']; ?> Bulan
</option>

<?php } ?>

</select>
</div>

<div class="group">
<label>Tanggal Imunisasi</label>
<input type="date" name="tanggal" required value="<?= $data['tanggal']; ?>">
</div>

<div class="group">
<label>Jenis Imunisasi</label>
<input type="text" name="jenis_imunisasi" id="jenis" required value="<?= $data['jenis_imunisasi']; ?>">
</div>

<div class="group">
<label>Dosis</label>
<input type="text" name="dosis" id="dosis" value="<?= $data['dosis']; ?>">
</div>

<div class="group">
<label>Petugas</label>
<input type="text" name="petugas" value="<?= $data['petugas']; ?>">
</div>

<div class="group full">
<label>Keterangan</label>
<textarea name="keterangan"><?= $data['keterangan']; ?></textarea>
</div>

<div class="group full">
<div class="info" id="info">
Memuat rekomendasi imunisasi...
</div>
</div>

</div>

<div class="action">

<button type="submit" name="update" class="save">
💾 Update Data
</button>

<a href="modules/imunisasi/hapus.php?id=<?= $data['id_imunisasi']; ?>"
onclick="return confirm('Yakin hapus data ini?')"
class="delete">
🗑️ Hapus
</a>

</div>

</form>

</div>

<script>
function isiData(){

let x = document.getElementById('anak');
let opt = x.options[x.selectedIndex];

let umur = parseInt(opt.getAttribute('data-umur'));
let nama = opt.getAttribute('data-nama');
let rt   = opt.getAttribute('data-rt');

let jenis = '';
let dosis = '';
let status = 'Tepat Waktu';

if(umur == 0){
jenis='HB0';
dosis='0';
}
else if(umur == 1){
jenis='BCG / Polio 1';
dosis='1';
}
else if(umur == 2){
jenis='DPT-HB-Hib 1';
dosis='1';
}
else if(umur == 3){
jenis='DPT-HB-Hib 2';
dosis='2';
}
else if(umur == 4){
jenis='DPT-HB-Hib 3';
dosis='3';
}
else if(umur >= 9 && umur < 18){
jenis='Campak / MR';
dosis='1';
}
else if(umur >= 18){
jenis='Booster';
dosis='Lanjutan';
}
else{
jenis='Konsultasi Jadwal';
dosis='-';
}

if(umur > 10 && jenis=='Campak / MR'){
status='Terlambat';
}

document.getElementById('info').innerHTML =
'<b>Nama:</b> '+nama+
'<br><b>RT:</b> '+rt+
'<br><b>Umur:</b> '+umur+' Bulan'+
'<br><b>Rekomendasi:</b> '+jenis+
'<br><b>Status:</b> '+status;

}

/* auto load */
isiData();
</script>