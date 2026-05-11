<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';
/* =========================================
   FILTER
========================================= */
$jenis = $_GET['jenis'] ?? 'penimbangan';
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$where = " WHERE MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun' ";

$title = '';
$sql   = '';

if ($jenis == 'penimbangan') {
    $title = "Laporan Penimbangan";
    $sql = "
    SELECT penimbangan.*, anak.nama_anak
    FROM penimbangan
    LEFT JOIN anak ON anak.id_anak = penimbangan.id_anak
    $where
    ORDER BY tanggal ASC
    ";
}

elseif ($jenis == 'imunisasi') {
    $title = "Laporan Imunisasi";
    $sql = "
    SELECT imunisasi.*, anak.nama_anak
    FROM imunisasi
    LEFT JOIN anak ON anak.id_anak = imunisasi.id_anak
    $where
    ORDER BY tanggal ASC
    ";
}

elseif ($jenis == 'vitamin') {
    $title = "Laporan Vitamin";
    $sql = "
    SELECT vitamin.*, anak.nama_anak
    FROM vitamin
    LEFT JOIN anak ON anak.id_anak = vitamin.id_anak
    $where
    ORDER BY tanggal ASC
    ";
}

elseif ($jenis == 'pemeriksaan_ibu') {
    $title = "Laporan Pemeriksaan Ibu";
    $sql = "
    SELECT pemeriksaan_ibu.*, ibu_hamil.nama_ibu
    FROM pemeriksaan_ibu
    LEFT JOIN ibu_hamil ON ibu_hamil.id_ibu = pemeriksaan_ibu.id_ibu
    $where
    ORDER BY tanggal ASC
    ";
}

$data = $conn->query($sql);
$total = $data->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= $title; ?></title>

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
color:#111827;
padding:30px;
background:#fff;
}

.header{
display:flex;
justify-content:space-between;
align-items:flex-start;
margin-bottom:18px;
padding-bottom:14px;
border-bottom:3px solid #2563eb;
}

.logo{
font-size:42px;
}

.title h1{
font-size:24px;
margin-bottom:4px;
color:#111827;
}

.title p{
font-size:13px;
color:#6b7280;
}

.meta{
text-align:right;
font-size:12px;
color:#374151;
line-height:1.7;
}

.cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:12px;
margin:18px 0;
}

.card{
border:1px solid #e5e7eb;
border-radius:10px;
padding:12px;
background:#f8fafc;
}

.card small{
display:block;
font-size:11px;
color:#64748b;
margin-bottom:5px;
}

.card h3{
font-size:20px;
color:#111827;
}

.table-wrap{
margin-top:10px;
}

table{
width:100%;
border-collapse:collapse;
font-size:11px;
}

th{
background:#2563eb;
color:#fff;
padding:9px;
text-align:left;
}

td{
padding:8px;
border-bottom:1px solid #e5e7eb;
vertical-align:top;
}

tr:nth-child(even){
background:#f9fafb;
}

.footer{
margin-top:45px;
display:flex;
justify-content:space-between;
align-items:flex-end;
}

.note{
font-size:11px;
color:#6b7280;
}

.ttd{
width:230px;
text-align:center;
font-size:12px;
}

.ttd .space{
height:70px;
}

@media print{
@page{
size:A4 landscape;
margin:12mm;
}

body{
padding:0;
}

.no-print{
display:none;
}
}
</style>

</head>

<body onload="window.print()">

<div class="header">

<div style="display:flex;gap:12px;">
<div class="logo">🩺</div>

<div class="title">
<h1>POSYANDU</h1>
<p><?= $title; ?></p>
<p>Periode <?= date('F', mktime(0,0,0,$bulan,1)); ?> <?= $tahun; ?></p>
</div>
</div>

<div class="meta">
Tanggal Cetak:<br>
<?= date('d-m-Y H:i'); ?>
</div>

</div>

<div class="cards">
<div class="card">
<small>Jenis Laporan</small>
<h3><?= $title; ?></h3>
</div>

<div class="card">
<small>Total Data</small>
<h3><?= $total; ?></h3>
</div>

<div class="card">
<small>Periode</small>
<h3><?= date('F', mktime(0,0,0,$bulan,1)); ?></h3>
</div>
</div>

<div class="table-wrap">
<table>

<?php if($jenis=='penimbangan'): ?>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Anak</th>
<th>Umur</th>
<th>Berat</th>
<th>Tinggi</th>
<th>Lingkar Kepala</th> 
<th>Status Gizi</th>
<th>Catatan</th>
</tr>

<?php $no=1; while($row=$data->fetch_assoc()): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= date('d-m-Y',strtotime($row['tanggal'])); ?></td>
<td><?= $row['nama_anak']; ?></td>
<td><?= $row['umur_bulan']; ?> bln</td>
<td><?= $row['berat']; ?> kg</td>
<td><?= $row['tinggi']; ?> cm</td>
<td><?= $row['lingkar_kepala']; ?> cm</td> 
<td><?= $row['status_gizi']; ?></td>
<td><?= $row['catatan']; ?></td>
</tr>
<?php endwhile; ?>
<?php endif; ?>


<?php if($jenis=='imunisasi'): ?>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Anak</th>
<th>Jenis</th>
<th>Dosis</th>
<th>Petugas</th>
<th>Keterangan</th>
</tr>

<?php $no=1; while($row=$data->fetch_assoc()): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= date('d-m-Y',strtotime($row['tanggal'])); ?></td>
<td><?= $row['nama_anak']; ?></td>
<td><?= $row['jenis_imunisasi']; ?></td>
<td><?= $row['dosis']; ?></td>
<td><?= $row['petugas']; ?></td>
<td><?= $row['keterangan']; ?></td>
</tr>
<?php endwhile; ?>
<?php endif; ?>


<?php if($jenis=='vitamin'): ?>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Anak</th>
<th>Jenis Vitamin</th>
<th>Dosis</th>
<th>Keterangan</th>
</tr>

<?php $no=1; while($row=$data->fetch_assoc()): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= date('d-m-Y',strtotime($row['tanggal'])); ?></td>
<td><?= $row['nama_anak']; ?></td>
<td><?= $row['jenis_vitamin']; ?></td>
<td><?= $row['dosis']; ?></td>
<td><?= $row['keterangan']; ?></td>
</tr>
<?php endwhile; ?>
<?php endif; ?>


<?php if($jenis=='pemeriksaan_ibu'): ?>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Ibu</th>
<th>Usia Hamil</th>
<th>BB</th>
<th>TD</th>
<th>Lingkar Lengan</th>
<th>Tinggi Fundus</th>
<th>Detak Janin</th>
<th>Keluhan</th>
<th>Tindakan</th>
<th>Tablet FE</th>
<th>Imunisasi TT</th>
<th>Rujukan</th>
</tr>

<?php $no=1; while($row=$data->fetch_assoc()): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= date('d-m-Y',strtotime($row['tanggal'])); ?></td>
<td><?= $row['nama_ibu']; ?></td>
<td><?= $row['usia_kehamilan']; ?> minggu</td>
<td><?= $row['berat_badan']; ?> kg</td>
<td><?= $row['tekanan_darah']; ?></td>
<td><?= $row['lingkar_lengan']; ?> cm</td>
<td><?= $row['tinggi_fundus']; ?> cm</td>
<td><?= $row['detak_jantung_janin']; ?></td>
<td><?= $row['keluhan']; ?></td>
<td><?= $row['tindakan']; ?></td>
<td><?= $row['tablet_fe']; ?></td>
<td><?= $row['imunisasi_tt']; ?></td>
<td><?= $row['rujukan']; ?></td>
</tr>
<?php endwhile; ?>
<?php endif; ?>

</table>
</div>

<div class="footer">

<div class="note">
Dicetak otomatis dari Sistem Informasi Posyandu
</div>

<div class="ttd">
Kekait, <?= date('d-m-Y'); ?><br>
Petugas Posyandu

<div class="space"></div>

<b>__________________</b>
</div>

</div>

</body>
</html>