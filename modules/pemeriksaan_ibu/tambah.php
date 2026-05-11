<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* ambil data ibu */
$query = $conn->query("
SELECT
ibu_hamil.*,
rt.nomor_rt
FROM ibu_hamil
LEFT JOIN rt ON rt.id_rt = ibu_hamil.id_rt
ORDER BY ibu_hamil.nama_ibu ASC
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
.reset{
border:none;
padding:14px 24px;
border-radius:14px;
background:#fee2e2;
color:#dc2626;
font-weight:700;
cursor:pointer;
}
@media(max-width:900px){
.grid{grid-template-columns:1fr;}
}
</style>

<div class="wrap">

<div class="top">
<div>
<h2>Tambah Pemeriksaan Ibu</h2>
<div class="sub">Input data ANC / pemeriksaan kehamilan</div>
</div>

<a href="index.php?page=pemeriksaan_ibu" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/pemeriksaan_ibu/simpan.php" class="form-box">

<div class="grid">

<div class="group full">
<label>Pilih Ibu Hamil</label>

<select name="id_ibu" id="ibu" required onchange="isiData()">
<option value="">-- Pilih Ibu --</option>

<?php while($r = $query->fetch_assoc()){ 

$minggu = 0;

if(!empty($r['hpht']) && $r['hpht'] != '0000-00-00'){

    $today = new DateTime();
    $hphtDate = new DateTime($r['hpht']);

    $selisih = $today->diff($hphtDate);

    $minggu = floor($selisih->days / 7);
}
?>

<option
value="<?= $r['id_ibu']; ?>"
data-nama="<?= $r['nama_ibu']; ?>"
data-rt="<?= $r['nomor_rt']; ?>"
data-hpl="<?= $r['hpl']; ?>"
data-risiko="<?= $r['risiko_kehamilan']; ?>"
data-minggu="<?= $minggu; ?>"
>
<?= $r['nama_ibu']; ?> | RT <?= $r['nomor_rt']; ?>
</option>

<?php } ?>

</select>
</div>

<div class="group">
<label>Tanggal</label>
<input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
</div>

<div class="group">
<label>Usia Kehamilan (Minggu)</label>
<input 
type="number" 
name="usia_kehamilan"
id="usia_kehamilan"
readonly>
</div>

<div class="group">
<label>Berat Badan (Kg)</label>
<input type="number" step="0.01" name="berat_badan">
</div>

<div class="group">
<label>Tekanan Darah</label>
<input type="text" name="tekanan_darah" placeholder="120/80">
</div>

<div class="group">
<label>Lingkar Lengan (Cm)</label>
<input type="number" step="0.01" name="lingkar_lengan">
</div>

<div class="group">
<label>Tinggi Fundus (Cm)</label>
<input type="number" step="0.01" name="tinggi_fundus">
</div>

<div class="group">
<label>Detak Jantung Janin</label>
<input type="text" name="detak_jantung_janin">
</div>

<div class="group">
<label>Tablet FE</label>
<input type="number" name="tablet_fe">
</div>

<div class="group">
<label>Imunisasi TT</label>
<input type="text" name="imunisasi_tt" placeholder="TT1 / TT2">
</div>

<div class="group">
<label>Rujukan</label>
<input type="text" name="rujukan">
</div>

<div class="group full">
<label>Keluhan</label>
<textarea name="keluhan"></textarea>
</div>

<div class="group full">
<label>Tindakan</label>
<textarea name="tindakan"></textarea>
</div>

<div class="group full">
<label>Catatan</label>
<textarea name="catatan"></textarea>
</div>

<div class="group">
<label>Petugas</label>
<input type="text" name="petugas">
</div>

<div class="group full">
<div class="info" id="info">
Pilih ibu hamil terlebih dahulu.
</div>
</div>

</div>

<div class="action">

<button type="submit" name="simpan" class="save">
💾 Simpan Data
</button>

<button type="reset" class="reset">
↺ Reset
</button>

</div>

</form>

</div>

<script>
function isiData(){

let x = document.getElementById('ibu');
let opt = x.options[x.selectedIndex];

if(x.value==''){
document.getElementById('info').innerHTML='Pilih ibu hamil terlebih dahulu.';
return;
}

let nama   = opt.getAttribute('data-nama');
let rt     = opt.getAttribute('data-rt');
let hpl    = opt.getAttribute('data-hpl');
let risiko = opt.getAttribute('data-risiko');
let minggu = opt.getAttribute('data-minggu');

document.getElementById('usia_kehamilan').value = minggu;

document.getElementById('info').innerHTML =
'<b>Nama:</b> '+nama+
'<br><b>RT:</b> '+rt+
'<br><b>Usia Kehamilan:</b> '+minggu+' Minggu'+
'<br><b>HPL:</b> '+(hpl ? hpl : '-')+
'<br><b>Risiko:</b> '+(risiko ? risiko : '-');

}
</script>