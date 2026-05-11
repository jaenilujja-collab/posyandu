<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);

/* ambil data penimbangan */
$stmt = $conn->prepare("
SELECT 
penimbangan.*,
anak.nama_anak,
anak.id_rt,
rt.nomor_rt
FROM penimbangan
LEFT JOIN anak ON anak.id_anak = penimbangan.id_anak
LEFT JOIN rt ON rt.id_rt = anak.id_rt
WHERE penimbangan.id_timbang=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();

if(!$data){
echo "<script>
alert('Data tidak ditemukan');
location='index.php?page=penimbangan';
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
.top h2{font-size:30px;margin:0;color:#0f172a;}
.sub{font-size:14px;color:#64748b;}
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
.group textarea{min-height:120px;resize:vertical;}
.info{
background:#ecfeff;
padding:16px;
border-radius:14px;
margin-top:15px;
font-size:14px;
line-height:1.7;
color:#0e7490;
}
.full{grid-column:1 / -1;}
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
<h2>Edit Penimbangan</h2>
<div class="sub">Perbarui data penimbangan balita</div>
</div>

<a href="index.php?page=penimbangan" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/penimbangan/update.php" class="form-box">

<input type="hidden" name="id_timbang" value="<?= $data['id_timbang']; ?>">
<input type="hidden" name="umur_bulan" id="umur_bulan" value="<?= $data['umur_bulan']; ?>">

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
<label>Tanggal</label>
<input type="date" name="tanggal" required value="<?= $data['tanggal']; ?>">
</div>

<div class="group">
<label>Berat Badan (kg)</label>
<input type="number" step="0.1" name="berat" required value="<?= $data['berat']; ?>">
</div>

<div class="group">
<label>Tinggi Badan (cm)</label>
<input type="number" step="0.1" name="tinggi" required value="<?= $data['tinggi']; ?>">
</div>

<div class="group">
<label>Lingkar Kepala</label>
<input type="number" step="0.1" name="lingkar_kepala" value="<?= $data['lingkar_kepala']; ?>">
</div>

<div class="group">
<label>Status Gizi</label>
<input type="text" name="status_gizi" value="<?= $data['status_gizi']; ?>">
</div>

<div class="group full">
<label>Catatan</label>
<textarea name="catatan"><?= $data['catatan']; ?></textarea>
</div>

<div class="group full">
<div class="info" id="info">
Memuat informasi anak...
</div>
</div>

</div>

<div class="action">

<button type="submit" name="update" class="save">
💾 Update Data
</button>

<a href="modules/penimbangan/hapus.php?id=<?= $data['id_timbang']; ?>"
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

document.getElementById('umur_bulan').value = umur;

document.getElementById('info').innerHTML =
'<b>Nama:</b> '+nama+
'<br><b>RT:</b> '+rt+
'<br><b>Umur:</b> '+umur+' Bulan';

}

isiData();
</script>