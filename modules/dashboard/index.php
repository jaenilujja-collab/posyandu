<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* =========================================
   DATA RINGKAS
========================================= */
$total = $conn->query("
SELECT
(SELECT COUNT(*) FROM anak) AS anak,
(SELECT COUNT(*) FROM ibu_hamil) AS ibu,
(SELECT COUNT(*) FROM kader) AS kader,
(SELECT COUNT(*) FROM rt) AS rt,
(SELECT COUNT(*) FROM penimbangan WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())) AS timbang,
(SELECT COUNT(*) FROM imunisasi WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())) AS imunisasi
")->fetch_assoc();

/* =========================================
   DATA TERBARU
========================================= */
$anakBaru = $conn->query("
SELECT anak.nama_anak, rt.nomor_rt
FROM anak
LEFT JOIN rt ON rt.id_rt=anak.id_rt
ORDER BY anak.id_anak DESC
LIMIT 5
");

$rtStat = $conn->query("
SELECT rt.nomor_rt, COUNT(anak.id_anak) AS total
FROM rt
LEFT JOIN anak ON anak.id_rt=rt.id_rt
GROUP BY rt.id_rt
ORDER BY rt.nomor_rt ASC
LIMIT 7
");

$tahun = date('Y');
?>

<style>
.title-box{
margin-bottom:25px;
}

.title-box h1{
font-size:30px;
font-weight:700;
color:#0f172a;
margin-bottom:6px;
}

.title-box p{
font-size:14px;
color:#64748b;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:18px;
margin-bottom:25px;
}

.card{
background:#fff;
border-radius:20px;
padding:22px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
display:flex;
justify-content:space-between;
align-items:center;
transition:.25s;
}

.card:hover{
transform:translateY(-5px);
box-shadow:0 18px 35px rgba(0,0,0,.08);
}

.card small{
display:block;
font-size:13px;
color:#64748b;
margin-bottom:8px;
font-weight:600;
}

.card h2{
font-size:30px;
font-weight:700;
color:#111827;
}

.icon{
width:55px;
height:55px;
border-radius:18px;
display:flex;
align-items:center;
justify-content:center;
font-size:28px;
}

.blue{background:#dbeafe;}
.green{background:#dcfce7;}
.orange{background:#ffedd5;}
.red{background:#fee2e2;}
.purple{background:#ede9fe;}

.grid{
display:grid;
grid-template-columns:2fr 1fr;
gap:20px;
margin-bottom:20px;
}

.grid2{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

.box{
background:#fff;
border-radius:20px;
padding:22px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.box h3{
font-size:18px;
font-weight:700;
margin-bottom:16px;
color:#111827;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:12px;
border-bottom:1px solid #eef2f7;
font-size:14px;
text-align:left;
}

th{
background:#f8fafc;
color:#64748b;
font-size:13px;
}

.badge{
padding:5px 10px;
background:#eff6ff;
color:#2563eb;
border-radius:20px;
font-size:12px;
font-weight:600;
}

.quick{
display:grid;
grid-template-columns:1fr 1fr;
gap:12px;
}

.quick a{
text-decoration:none;
padding:14px;
border-radius:14px;
font-size:14px;
font-weight:700;
text-align:center;
background:#eff6ff;
color:#2563eb;
transition:.2s;
}

.quick a:hover{
background:#2563eb;
color:#fff;
}

.info{
display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #eef2f7;
font-size:14px;
}

.info:last-child{
border:none;
}

@media(max-width:900px){
.grid,.grid2{
grid-template-columns:1fr;
}
}
</style>

<div class="title-box">
<h1>Dashboard Posyandu</h1>
<p>Ringkasan pelayanan ibu dan anak tahun <?= $tahun; ?>.</p>
</div>

<!-- KARTU -->
<div class="cards">

<div class="card">
<div>
<small>Total Anak</small>
<h2><?= $total['anak']; ?></h2>
</div>
<div class="icon orange">🧒</div>
</div>

<div class="card">
<div>
<small>Ibu Hamil</small>
<h2><?= $total['ibu']; ?></h2>
</div>
<div class="icon red">🤰</div>
</div>

<div class="card">
<div>
<small>Penimbangan Bulan Ini</small>
<h2><?= $total['timbang']; ?></h2>
</div>
<div class="icon green">⚖️</div>
</div>

<div class="card">
<div>
<small>Imunisasi Bulan Ini</small>
<h2><?= $total['imunisasi']; ?></h2>
</div>
<div class="icon blue">💉</div>
</div>

<div class="card">
<div>
<small>Kader Aktif</small>
<h2><?= $total['kader']; ?></h2>
</div>
<div class="icon purple">👩‍⚕️</div>
</div>

<div class="card">
<div>
<small>Jumlah RT</small>
<h2><?= $total['rt']; ?></h2>
</div>
<div class="icon blue">🏘️</div>
</div>

</div>

<!-- BARIS 1 -->
<div class="grid">

<!-- kiri -->
<div class="box">
<h3>Data Anak Terbaru</h3>

<table>
<tr>
<th>Nama Anak</th>
<th>RT</th>
<th>Status</th>
</tr>

<?php while($r = $anakBaru->fetch_assoc()){ ?>
<tr>
<td><?= $r['nama_anak']; ?></td>
<td><?= $r['nomor_rt']; ?></td>
<td><span class="badge">Aktif</span></td>
</tr>
<?php } ?>

</table>
</div>

<!-- kanan -->
<div class="box">
<h3>Akses Cepat</h3>

<div class="quick">
<a href="index.php?page=anak_tambah">➕ Tambah Anak</a>
<a href="index.php?page=ibu_hamil_tambah">➕ Tambah Ibu</a>
<a href="index.php?page=penimbangan_tambah">⚖️ Penimbangan</a>
<a href="index.php?page=imunisasi_tambah">💉 Imunisasi</a>
<a href="index.php?page=vitamin_tambah">💊 Vitamin</a>
<a href="index.php?page=laporan">📄 Laporan</a>
</div>

</div>

</div>

<!-- BARIS 2 -->
<div class="grid2">

<div class="box">
<h3>Statistik Anak per RT</h3>

<?php while($rt = $rtStat->fetch_assoc()){ ?>
<div class="info">
<span><?= $rt['nomor_rt']; ?></span>
<b><?= $rt['total']; ?> Anak</b>
</div>
<?php } ?>

</div>

<div class="box">
<h3>Laporan Tahun <?= $tahun; ?></h3>

<div class="info">
<span>Total Anak</span>
<b><?= $total['anak']; ?></b>
</div>

<div class="info">
<span>Ibu Hamil</span>
<b><?= $total['ibu']; ?></b>
</div>

<div class="info">
<span>Kader</span>
<b><?= $total['kader']; ?></b>
</div>

<div class="info">
<span>RT Aktif</span>
<b><?= $total['rt']; ?></b>
</div>

<div class="info">
<span>Pelayanan Bulan Ini</span>
<b><?= $total['timbang'] + $total['imunisasi']; ?></b>
</div>

</div>

</div>