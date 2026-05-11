<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

/* ambil data pemeriksaan */
$stmt = $conn->prepare("
SELECT 
p.*,
i.nama_ibu,
i.hpl,
i.risiko_kehamilan,
rt.nomor_rt
FROM pemeriksaan_ibu p
LEFT JOIN ibu_hamil i ON i.id_ibu = p.id_ibu
LEFT JOIN rt ON rt.id_rt = i.id_rt
WHERE p.id_periksa=?
");

$stmt->bind_param("i",$id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if(!$data){
header("Location: index.php?page=pemeriksaan_ibu");
exit;
}

/* semua ibu */
$ibu = $conn->query("
SELECT
i.*,
rt.nomor_rt
FROM ibu_hamil i
LEFT JOIN rt ON rt.id_rt = i.id_rt
ORDER BY i.nama_ibu ASC
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
.group textarea{
min-height:110px;
resize:vertical;
}
.full{grid-column:1/-1;}
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
<h2>Edit Pemeriksaan Ibu</h2>
<div class="sub">Perbarui data ANC / pemeriksaan kehamilan</div>
</div>

<a href="index.php?page=pemeriksaan_ibu" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/pemeriksaan_ibu/update.php" class="form-box">

<input type="hidden" name="id_periksa" value="<?= $data['id_periksa']; ?>">

<div class="grid">

<div class="group full">
<label>Pilih Ibu Hamil</label>

<select name="id_ibu" id="ibu" onchange="isiData()" required>

<?php while($r=$ibu->fetch_assoc()){ ?>
<option
value="<?= $r['id_ibu']; ?>"
<?= $r['id_ibu']==$data['id_ibu'] ? 'selected' : ''; ?>
data-nama="<?= htmlspecialchars($r['nama_ibu']); ?>"
data-rt="<?= $r['nomor_rt']; ?>"
data-hpl="<?= $r['hpl']; ?>"
data-risiko="<?= htmlspecialchars($r['risiko_kehamilan']); ?>"
>
<?= $r['nama_ibu']; ?> | RT <?= $r['nomor_rt']; ?>
</option>
<?php } ?>

</select>
</div>

<div class="group">
<label>Tanggal</label>
<input type="date" name="tanggal" value="<?= $data['tanggal']; ?>" required>
</div>

<div class="group">
<label>Usia Kehamilan (Minggu)</label>
<?php
$minggu = 0;

if(!empty($data['hpht']) && $data['hpht'] != '0000-00-00'){

    $today = new DateTime();

    $hphtDate = new DateTime($data['hpht']);

    $selisih = $today->diff($hphtDate);

    $minggu = floor($selisih->days / 7);
}
?>

<input 
type="number"
name="usia_kehamilan"
value="<?= $minggu; ?>"
readonly>
</div>

<div class="group">
<label>Berat Badan (Kg)</label>
<input type="number" step="0.01" name="berat_badan" value="<?= $data['berat_badan']; ?>">
</div>

<div class="group">
<label>Tekanan Darah</label>
<input type="text" name="tekanan_darah" value="<?= $data['tekanan_darah']; ?>">
</div>

<div class="group">
<label>Lingkar Lengan (Cm)</label>
<input type="number" step="0.01" name="lingkar_lengan" value="<?= $data['lingkar_lengan']; ?>">
</div>

<div class="group">
<label>Tinggi Fundus (Cm)</label>
<input type="number" step="0.01" name="tinggi_fundus" value="<?= $data['tinggi_fundus']; ?>">
</div>

<div class="group">
<label>Detak Jantung Janin</label>
<input type="text" name="detak_jantung_janin" value="<?= $data['detak_jantung_janin']; ?>">
</div>

<div class="group">
<label>Tablet FE</label>
<input type="number" name="tablet_fe" value="<?= $data['tablet_fe']; ?>">
</div>

<div class="group">
<label>Imunisasi TT</label>
<input type="text" name="imunisasi_tt" value="<?= $data['imunisasi_tt']; ?>">
</div>

<div class="group">
<label>Rujukan</label>
<input type="text" name="rujukan" value="<?= $data['rujukan']; ?>">
</div>

<div class="group full">
<label>Keluhan</label>
<textarea name="keluhan"><?= $data['keluhan']; ?></textarea>
</div>

<div class="group full">
<label>Tindakan</label>
<textarea name="tindakan"><?= $data['tindakan']; ?></textarea>
</div>

<div class="group full">
<label>Catatan</label>
<textarea name="catatan"><?= $data['catatan']; ?></textarea>
</div>

<div class="group">
<label>Petugas</label>
<input type="text" name="petugas" value="<?= $data['petugas']; ?>">
</div>

<div class="group full">
<div class="info" id="info"></div>
</div>

</div>

<div class="action">

<button type="submit" name="update" class="save">
💾 Update Data
</button>

<a href="modules/pemeriksaan_ibu/hapus.php?id=<?= $data['id_periksa']; ?>"
class="delete"
onclick="return true;">
🗑️ Hapus
</a>

</div>

</form>

</div>

<script>
function isiData(){

let x = document.getElementById('ibu');
let opt = x.options[x.selectedIndex];

let nama = opt.getAttribute('data-nama');
let rt = opt.getAttribute('data-rt');
let hpl = opt.getAttribute('data-hpl');
let risiko = opt.getAttribute('data-risiko');

document.getElementById('info').innerHTML =
'<b>Nama:</b> '+nama+
'<br><b>RT:</b> '+rt+
'<br><b>HPL:</b> '+(hpl ? hpl : '-')+
'<br><b>Risiko:</b> '+(risiko ? risiko : '-');

}

isiData();
</script>