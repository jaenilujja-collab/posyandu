<?php
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
$data  = null;

/* =========================================
   QUERY
========================================= */
if ($jenis == 'penimbangan') {

    $title = "Laporan Penimbangan";

    $sql = "
    SELECT penimbangan.*, anak.nama_anak
    FROM penimbangan
    LEFT JOIN anak ON anak.id_anak = penimbangan.id_anak
    $where
    ORDER BY tanggal DESC
    ";
}

elseif ($jenis == 'imunisasi') {

    $title = "Laporan Imunisasi";

    $sql = "
    SELECT imunisasi.*, anak.nama_anak
    FROM imunisasi
    LEFT JOIN anak ON anak.id_anak = imunisasi.id_anak
    $where
    ORDER BY tanggal DESC
    ";
}

elseif ($jenis == 'vitamin') {

    $title = "Laporan Vitamin";

    $sql = "
    SELECT vitamin.*, anak.nama_anak
    FROM vitamin
    LEFT JOIN anak ON anak.id_anak = vitamin.id_anak
    $where
    ORDER BY tanggal DESC
    ";
}

elseif ($jenis == 'pemeriksaan_ibu') {

    $title = "Laporan Pemeriksaan Ibu";

    $sql = "
    SELECT pemeriksaan_ibu.*, ibu_hamil.nama_ibu
    FROM pemeriksaan_ibu
    LEFT JOIN ibu_hamil ON ibu_hamil.id_ibu = pemeriksaan_ibu.id_ibu
    $where
    ORDER BY tanggal DESC
    ";
}

$data  = $conn->query($sql);
$total = $data->num_rows;
?>

<style>
.page-title{
font-size:34px;
font-weight:800;
color:#0f172a;
margin-bottom:5px;
}
.sub-title{
color:#64748b;
font-size:14px;
margin-bottom:25px;
}
.filter-box{
background:linear-gradient(135deg,#ffffff,#f8fafc);
padding:22px;
border-radius:22px;
box-shadow:0 15px 35px rgba(15,23,42,.06);
display:grid;
grid-template-columns:2fr 1fr 1fr auto auto;
gap:14px;
margin-bottom:25px;
border:1px solid #eef2f7;
}
.filter-box select,
.filter-box button,
.filter-box a{
padding:13px 15px;
border-radius:14px;
border:1px solid #dbe2ea;
font-size:14px;
font-weight:600;
outline:none;
text-decoration:none;
}
.filter-box button{
background:linear-gradient(135deg,#2563eb,#1d4ed8);
color:#fff;
border:none;
cursor:pointer;
}
.filter-box a{
background:linear-gradient(135deg,#16a34a,#15803d);
color:#fff;
border:none;
text-align:center;
}
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:18px;
margin-bottom:25px;
}
.card{
background:#fff;
border-radius:22px;
padding:22px;
box-shadow:0 15px 35px rgba(15,23,42,.06);
border:1px solid #eef2f7;
}
.card small{
display:block;
font-size:12px;
text-transform:uppercase;
font-weight:700;
color:#64748b;
margin-bottom:8px;
}
.card h3{
font-size:28px;
font-weight:800;
color:#0f172a;
margin:0;
}
.table-box{
background:#fff;
border-radius:22px;
box-shadow:0 15px 35px rgba(15,23,42,.06);
overflow-x:auto;
border:1px solid #eef2f7;
}
.table-head{
padding:20px 22px;
border-bottom:1px solid #eef2f7;
font-size:18px;
font-weight:800;
color:#0f172a;
}
table{
width:100%;
border-collapse:collapse;
font-size:14px;
}
thead th{
background:#f8fafc;
padding:14px;
text-align:left;
font-weight:800;
color:#334155;
border-bottom:1px solid #e5e7eb;
}
tbody td{
padding:13px 14px;
border-bottom:1px solid #f1f5f9;
color:#334155;
}
tbody tr:nth-child(even){
background:#fcfdff;
}
tbody tr:hover{
background:#eff6ff;
transition:.2s;
}
.empty{
padding:40px;
text-align:center;
color:#64748b;
font-size:15px;
}
.badge{
display:inline-block;
padding:6px 12px;
border-radius:999px;
background:#dbeafe;
color:#1d4ed8;
font-size:12px;
font-weight:700;
}
</style>

<div class="page-title">📊 Laporan Pelayanan</div>
<div class="sub-title">Laporan lengkap sesuai database</div>

<form method="GET" class="filter-box">
<input type="hidden" name="page" value="laporan">

<select name="jenis">
<option value="penimbangan" <?= $jenis=='penimbangan'?'selected':''; ?>>Penimbangan</option>
<option value="imunisasi" <?= $jenis=='imunisasi'?'selected':''; ?>>Imunisasi</option>
<option value="vitamin" <?= $jenis=='vitamin'?'selected':''; ?>>Vitamin</option>
<option value="pemeriksaan_ibu" <?= $jenis=='pemeriksaan_ibu'?'selected':''; ?>>Pemeriksaan Ibu</option>
</select>

<select name="bulan">
<?php for($i=1;$i<=12;$i++): ?>
<option value="<?= sprintf('%02d',$i); ?>" <?= $bulan==sprintf('%02d',$i)?'selected':''; ?>>
<?= date('F',mktime(0,0,0,$i,1)); ?>
</option>
<?php endfor; ?>
</select>

<select name="tahun">
<?php for($t=date('Y');$t>=2020;$t--): ?>
<option value="<?= $t; ?>" <?= $tahun==$t?'selected':''; ?>><?= $t; ?></option>
<?php endfor; ?>
</select>

<button type="submit">🔍 Tampilkan</button>
<a target="_blank"
href="modules/laporan/cetak.php?jenis=<?= $jenis; ?>&bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>">
🖨 Cetak
</a>
</form>

<div class="cards">
<div class="card">
<small>Jenis</small>
<h3><?= $title; ?></h3>
</div>
<div class="card">
<small>Total</small>
<h3><?= $total; ?></h3>
</div>
</div>

<div class="table-box">
<div class="table-head"><?= $title; ?> <span class="badge"><?= $tahun; ?></span></div>

<?php if($total > 0): ?>

<table>

<?php if($jenis=='penimbangan'): ?>
<thead>
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
</thead>
<tbody>
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
</tbody>
<?php endif; ?>


<?php if($jenis=='imunisasi'): ?>
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Anak</th>
<th>Jenis Imunisasi</th>
<th>Dosis</th>
<th>Petugas</th>
<th>Keterangan</th>
</tr>
</thead>
<tbody>
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
</tbody>
<?php endif; ?>


<?php if($jenis=='vitamin'): ?>
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Anak</th>
<th>Jenis Vitamin</th>
<th>Dosis</th>
<th>Keterangan</th>
</tr>
</thead>
<tbody>
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
</tbody>
<?php endif; ?>


<?php if($jenis=='pemeriksaan_ibu'): ?>
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Ibu</th>
<th>Usia Hamil</th>
<th>Berat Badan</th>
<th>Tekanan Darah</th>
<th>Lingkar Lengan</th>
<th>Tinggi Fundus</th>
<th>Detak Janin</th>
<th>Keluhan</th>
<th>Tindakan</th>
<th>Tablet FE</th>
<th>Imunisasi TT</th>
<th>Rujukan</th>
</tr>
</thead>
<tbody>
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
</tbody>
<?php endif; ?>

</table>

<?php else: ?>
<div class="empty">Tidak ada data</div>
<?php endif; ?>

</div>