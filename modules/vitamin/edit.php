<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

/* ambil data vitamin */
$stmt = $conn->prepare("
SELECT 
vitamin.*,
anak.nama_anak,
anak.id_rt,
rt.nomor_rt
FROM vitamin
LEFT JOIN anak ON anak.id_anak = vitamin.id_anak
LEFT JOIN rt ON rt.id_rt = anak.id_rt
WHERE vitamin.id_vitamin=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();

if(!$data){
echo "<script>
location='index.php?page=vitamin';
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
}
.group textarea{
min-height:110px;
resize:vertical;
}
.full{
grid-column:1 / -1;
}
.info{
background:#eff6ff;
padding:16px;
border-radius:14px;
font-size:14px;
line-height:1.7;
color:#1e3a8a;
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
<h2>Edit Vitamin</h2>
<div class="sub">Perbarui data pemberian vitamin anak</div>
</div>

<a href="index.php?page=vitamin" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/vitamin/update.php" class="form-box">

<input type="hidden" name="id_vitamin" value="<?= $data['id_vitamin']; ?>">

<div class="grid">

<div class="group full">
<label>Pilih Anak</label>

<select name="id_anak" id="anak" required onchange="isiData()">

<?php while($r=$anak->fetch_assoc()){ ?>
<option
value="<?= $r['id_anak']; ?>"
<?= $r['id_anak']==$data['id_anak'] ? 'selected' : ''; ?>
data-nama="<?= $r['nama_anak']; ?>"
data-rt="<?= $r['nomor_rt']; ?>"
data-umur="<?= $r['umur_bulan']; ?>"
>
<?= $r['nama_anak']; ?> | RT <?= $r['nomor_rt']; ?> | <?= $r['umur_bulan']; ?> Bulan
</option>
<?php } ?>

</select>
</div>

<div class="group">
<label>Tanggal</label>
<input type="date" name="tanggal" required value="<?= $data['tanggal']; ?>">
</div>

<div class="group">
<label>Jenis Vitamin</label>
<input type="text" name="jenis_vitamin" id="jenis_vitamin" required value="<?= $data['jenis_vitamin']; ?>">
</div>

<div class="group">
<label>Dosis</label>
<input type="text" name="dosis" id="dosis" required value="<?= $data['dosis']; ?>">
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
Memuat rekomendasi vitamin...
</div>
</div>

</div>

<div class="action">

<button type="submit" name="update" class="save">
💾 Update Data
</button>

<a href="modules/vitamin/hapus.php?id=<?= $data['id_vitamin']; ?>"
class="delete"
onclick="return true;">
🗑️ Hapus
</a>

</div>

</form>

</div>

<script>
function isiData(){

let x = document.getElementById('anak');
let opt = x.options[x.selectedIndex];

let nama = opt.getAttribute('data-nama');
let rt   = opt.getAttribute('data-rt');
let umur = parseInt(opt.getAttribute('data-umur'));

let jenis = 'Vitamin A';
let dosis = '';
let warna = '';

if(umur >= 6 && umur <= 11){
dosis = '100.000 IU';
warna = 'Kapsul Biru';
}
else if(umur >= 12 && umur <= 59){
dosis = '200.000 IU';
warna = 'Kapsul Merah';
}
else{
dosis = 'Konsultasi Petugas';
warna = '-';
}

document.getElementById('info').innerHTML =
'<b>Nama:</b> '+nama+
'<br><b>RT:</b> '+rt+
'<br><b>Umur:</b> '+umur+' Bulan'+
'<br><b>Rekomendasi:</b> '+jenis+
'<br><b>Dosis Ideal:</b> '+dosis+
'<br><b>Kapsul:</b> '+warna;

}

/* auto load */
isiData();
</script>