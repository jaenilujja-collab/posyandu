<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* ===============================
   FILTER
================================= */
$q   = trim($_GET['q'] ?? '');
$rt  = $_GET['rt'] ?? '';
$msg = $_GET['msg'] ?? '';

/* ===============================
   QUERY DATA
================================= */
$sql = "
SELECT 
ibu_hamil.*,
rt.nomor_rt
FROM ibu_hamil
LEFT JOIN rt ON rt.id_rt = ibu_hamil.id_rt
WHERE 1=1
";

$params = [];
$types  = '';

if($rt != ''){
    $sql .= " AND ibu_hamil.id_rt=? ";
    $params[] = $rt;
    $types .= "i";
}

if($q != ''){
    $sql .= " AND (
        ibu_hamil.nama_ibu LIKE ?
        OR ibu_hamil.nik LIKE ?
        OR rt.nomor_rt LIKE ?
    ) ";

    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sss";
}

$sql .= " ORDER BY ibu_hamil.id_ibu DESC ";

$stmt = $conn->prepare($sql);

if(count($params) > 0){
    $stmt->bind_param($types,...$params);
}

$stmt->execute();
$data = $stmt->get_result();

/* data rt */
$dataRT = $conn->query("SELECT * FROM rt ORDER BY nomor_rt");

/* statistik */
$total = $conn->query("SELECT COUNT(*) jml FROM ibu_hamil")->fetch_assoc()['jml'];

$risiko = $conn->query("
SELECT COUNT(*) jml 
FROM ibu_hamil 
WHERE risiko_kehamilan IS NOT NULL 
AND risiko_kehamilan!=''
")->fetch_assoc()['jml'];

$trimester3 = $conn->query("
SELECT COUNT(*) jml
FROM ibu_hamil
WHERE usia_kehamilan >= 28
")->fetch_assoc()['jml'];
?>

<style>
.top{
display:flex;
justify-content:space-between;
align-items:center;
gap:15px;
flex-wrap:wrap;
margin-bottom:22px;
}

.top h2{
font-size:30px;
margin:0;
color:#0f172a;
}

.sub{
font-size:14px;
color:#64748b;
margin-top:5px;
}

.btn-add{
padding:13px 20px;
border-radius:14px;
background:linear-gradient(135deg,#2563eb,#16a34a);
color:#fff;
text-decoration:none;
font-weight:700;
}

.alert{
padding:14px 18px;
border-radius:16px;
margin-bottom:18px;
font-size:14px;
font-weight:600;
box-shadow:0 8px 20px rgba(0,0,0,.04);
}

.success{
background:#dcfce7;
color:#166534;
border-left:5px solid #16a34a;
}

.info{
background:#dbeafe;
color:#1d4ed8;
border-left:5px solid #2563eb;
}

.danger{
background:#fee2e2;
color:#b91c1c;
border-left:5px solid #dc2626;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:15px;
margin-bottom:20px;
}

.card{
background:#fff;
padding:20px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.card small{
display:block;
font-size:13px;
color:#64748b;
margin-bottom:8px;
font-weight:600;
}

.card h3{
font-size:28px;
margin:0;
color:#0f172a;
}

.filter{
display:grid;
grid-template-columns:2fr 1fr auto;
gap:12px;
background:#fff;
padding:16px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
margin-bottom:20px;
}

.filter input,
.filter select{
padding:12px;
border:1px solid #dbe2ea;
border-radius:12px;
font-size:14px;
}

.filter button{
border:none;
padding:12px 18px;
border-radius:12px;
background:#16a34a;
color:#fff;
font-weight:700;
cursor:pointer;
}

.list{
display:flex;
flex-direction:column;
gap:18px;
}

.box{
background:#fff;
padding:22px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.head{
display:flex;
justify-content:space-between;
align-items:center;
gap:10px;
flex-wrap:wrap;
margin-bottom:16px;
}

.name{
font-size:22px;
font-weight:700;
color:#0f172a;
}

.badge{
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:700;
background:#fef3c7;
color:#92400e;
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:18px;
}

.part{
background:#f8fafc;
padding:18px;
border-radius:14px;
}

.part h4{
margin:0 0 12px;
font-size:15px;
color:#2563eb;
}

.row{
display:grid;
grid-template-columns:145px 1fr;
gap:8px;
padding:6px 0;
border-bottom:1px solid #e9eef4;
font-size:14px;
}

.row:last-child{
border:none;
}

.label{
font-weight:600;
color:#64748b;
}

.value{
color:#111827;
}

.action{
display:flex;
gap:10px;
margin-top:18px;
}

.edit,.hapus{
flex:1;
text-align:center;
padding:11px;
border-radius:12px;
text-decoration:none;
font-size:13px;
font-weight:700;
}

.edit{
background:#fef3c7;
color:#b45309;
}

.hapus{
background:#fee2e2;
color:#dc2626;
}

.empty{
background:#fff;
padding:35px;
text-align:center;
border-radius:18px;
color:#64748b;
}

@media(max-width:900px){
.filter{grid-template-columns:1fr;}
.grid{grid-template-columns:1fr;}
.row{grid-template-columns:120px 1fr;}
}
</style>

<!-- HEADER -->
<div class="top">
<div>
<h2>Data Ibu Hamil</h2>
<div class="sub">Kelola data ibu hamil posyandu</div>
</div>

<a href="index.php?page=ibu_hamil_tambah" class="btn-add">+ Tambah Data</a>
</div>

<!-- NOTIFIKASI -->
<?php if($msg=='simpan'){ ?>
<div class="alert success">✅ Data ibu hamil berhasil disimpan</div>
<?php } ?>

<?php if($msg=='update'){ ?>
<div class="alert info">✏️ Data ibu hamil berhasil diperbarui</div>
<?php } ?>

<?php if($msg=='hapus'){ ?>
<div class="alert danger">🗑️ Data ibu hamil berhasil dihapus</div>
<?php } ?>

<!-- FILTER -->
<form method="GET" class="filter">

<input type="hidden" name="page" value="ibu_hamil">

<input type="text"
name="q"
placeholder="Cari nama / NIK / RT..."
value="<?= htmlspecialchars($q); ?>">

<select name="rt">
<option value="">Semua RT</option>
<?php while($r = $dataRT->fetch_assoc()){ ?>
<option value="<?= $r['id_rt']; ?>" <?= $rt==$r['id_rt']?'selected':''; ?>>
<?= $r['nomor_rt']; ?>
</option>
<?php } ?>
</select>

<button type="submit">Filter</button>

</form>

<!-- LIST DATA -->
<div class="list">

<?php if($data->num_rows > 0){ ?>
<?php while($row = $data->fetch_assoc()){ ?>

<div class="box">

<div class="head">
<div class="name"><?= $row['nama_ibu']; ?></div>
<div class="badge"><?= $row['usia_kehamilan']; ?> </div>
</div>

<div class="grid">

<div class="part">
<h4>Informasi Ibu</h4>

<div class="row"><div class="label">NIK</div><div class="value"><?= $row['nik']; ?></div></div>
<div class="row"><div class="label">Umur</div><div class="value"><?= $row['umur']; ?> Tahun</div></div>
<div class="row"><div class="label">RT</div><div class="value"><?= $row['nomor_rt']; ?></div></div>
<div class="row"><div class="label">Gravida</div><div class="value"><?= $row['gravida']; ?></div></div>
<div class="row"><div class="label">HPHT</div><div class="value"><?= $row['hpht'] ? date('d-m-Y',strtotime($row['hpht'])) : '-'; ?></div></div>
<div class="row"><div class="label">HPL</div><div class="value"><?= $row['hpl'] ? date('d-m-Y',strtotime($row['hpl'])) : '-'; ?></div></div>

</div>

<div class="part">
<h4>Kesehatan</h4>

<div class="row"><div class="label">Gol Darah</div><div class="value"><?= $row['gol_darah']; ?></div></div>
<div class="row"><div class="label">Tinggi Badan</div><div class="value"><?= $row['tinggi_badan']; ?> Cm</div></div>
<div class="row"><div class="label">Berat Awal</div><div class="value"><?= $row['berat_awal']; ?> Kg</div></div>
<div class="row"><div class="label">BPJS</div><div class="value"><?= $row['bpjs']; ?></div></div>
<div class="row"><div class="label">Risiko</div><div class="value"><?= $row['risiko_kehamilan'] ?: '-'; ?></div></div>

</div>

</div>

<div class="action">

<a href="index.php?page=ibu_hamil_edit&id=<?= $row['id_ibu']; ?>" class="edit">
✏️ Edit
</a>

<a href="modules/ibu_hamil/hapus.php?id=<?= $row['id_ibu']; ?>"
class="hapus"
onclick="return true;">
🗑️ Hapus
</a>

</div>

</div>

<?php } ?>
<?php } else { ?>

<div class="empty">
Belum ada data ibu hamil ditemukan.
</div>

<?php } ?>

</div>

<script>
setTimeout(()=>{
let x=document.querySelector('.alert');
if(x){x.style.display='none';}
},3000);
</script>