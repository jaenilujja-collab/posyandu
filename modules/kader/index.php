<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$msg = $_GET['msg'] ?? '';

/* ===============================
   DATA KADER = TABEL UTAMA
================================= */
$sql = "
SELECT
kader.*,
users.id_user,
users.nama,
users.username,
users.level,
users.status
FROM kader
LEFT JOIN users ON users.id_kader = kader.id_kader
ORDER BY kader.id_kader DESC
";

$data = $conn->query($sql);

/* statistik */
$total  = $conn->query("SELECT COUNT(*) jml FROM kader")->fetch_assoc()['jml'];
$akun   = $conn->query("SELECT COUNT(*) jml FROM users WHERE id_kader IS NOT NULL")->fetch_assoc()['jml'];
$aktif  = $conn->query("SELECT COUNT(*) jml FROM users WHERE status='aktif'")->fetch_assoc()['jml'];
$admin  = $conn->query("SELECT COUNT(*) jml FROM users WHERE level='admin'")->fetch_assoc()['jml'];
?>

<style>
.top{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;margin-bottom:22px}
.top h2{font-size:30px;margin:0;color:#0f172a}
.sub{font-size:14px;color:#64748b;margin-top:5px}
.btn-add{padding:13px 20px;border-radius:14px;background:linear-gradient(135deg,#2563eb,#16a34a);color:#fff;text-decoration:none;font-weight:700}

.alert{padding:14px 18px;border-radius:16px;margin-bottom:18px;font-size:14px;font-weight:600;box-shadow:0 8px 20px rgba(0,0,0,.04)}
.success{background:#dcfce7;color:#166534;border-left:5px solid #16a34a}
.info{background:#dbeafe;color:#1d4ed8;border-left:5px solid #2563eb}
.danger{background:#fee2e2;color:#b91c1c;border-left:5px solid #dc2626}

.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:20px}
.card{background:#fff;padding:20px;border-radius:18px;box-shadow:0 10px 25px rgba(0,0,0,.05)}
.card small{display:block;font-size:13px;color:#64748b;margin-bottom:8px;font-weight:600}
.card h3{font-size:28px;margin:0;color:#0f172a}

.list{display:flex;flex-direction:column;gap:18px}
.box{background:#fff;padding:22px;border-radius:18px;box-shadow:0 10px 25px rgba(0,0,0,.05)}

.head{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px}
.name{font-size:22px;font-weight:700;color:#0f172a}

.badge{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:700}
.ok{background:#dcfce7;color:#166534}
.no{background:#fee2e2;color:#b91c1c}

.grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.part{background:#f8fafc;padding:18px;border-radius:14px}
.part h4{margin:0 0 12px;font-size:15px;color:#2563eb}

.row{display:grid;grid-template-columns:145px 1fr;gap:8px;padding:6px 0;border-bottom:1px solid #e9eef4;font-size:14px}
.row:last-child{border:none}

.label{font-weight:600;color:#64748b}
.value{color:#111827}

.action{display:flex;gap:10px;margin-top:18px}
.edit,.hapus{flex:1;text-align:center;padding:11px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:700}
.edit{background:#fef3c7;color:#b45309}
.hapus{background:#fee2e2;color:#dc2626}

.empty{background:#fff;padding:35px;text-align:center;border-radius:18px;color:#64748b}

@media(max-width:900px){
.grid{grid-template-columns:1fr}
.row{grid-template-columns:120px 1fr}
}
</style>

<div class="top">
<div>
<h2>Data Kader</h2>
<div class="sub">Kelola data kader dan akun login</div>
</div>

<a href="index.php?page=kader_tambah" class="btn-add">+ Tambah Data</a>
</div>

<?php if($msg=='simpan'){ ?><div class="alert success">✅ Data berhasil disimpan</div><?php } ?>
<?php if($msg=='update'){ ?><div class="alert info">✏️ Data berhasil diperbarui</div><?php } ?>
<?php if($msg=='hapus'){ ?><div class="alert danger">🗑️ Data berhasil dihapus</div><?php } ?>

<div class="list">

<?php if($data->num_rows > 0){ ?>
<?php while($row = $data->fetch_assoc()){ ?>

<?php $punya = $row['id_user'] ? true : false; ?>

<div class="box">

<div class="head">
<div class="name"><?= $row['nama_kader']; ?></div>

<?php if($punya){ ?>
<div class="badge ok">Punya Akun</div>
<?php } else { ?>
<div class="badge no">Belum Ada Akun</div>
<?php } ?>

</div>

<div class="grid">

<div class="part">
<h4>Informasi Kader</h4>

<div class="row"><div class="label">Nama</div><div class="value"><?= $row['nama_kader']; ?></div></div>
<div class="row"><div class="label">No HP</div><div class="value"><?= $row['no_hp']; ?></div></div>
</div>

<div class="part">
<h4>Informasi Login</h4>

<div class="row"><div class="label">Username</div><div class="value"><?= $row['username'] ?: '-'; ?></div></div>
<div class="row"><div class="label">Level</div><div class="value"><?= $row['level'] ?: '-'; ?></div></div>
<div class="row"><div class="label">Status</div><div class="value"><?= $row['status'] ?: '-'; ?></div></div>

</div>

</div>

<div class="action">

<a href="index.php?page=kader_edit&id=<?= $row['id_kader']; ?>" class="edit">
✏️ Edit
</a>

<a href="modules/kader/hapus.php?id=<?= $row['id_kader']; ?>"
class="hapus"
onclick="return confirm('Yakin hapus data ini?')">
🗑️ Hapus
</a>

</div>

</div>

<?php } ?>
<?php } else { ?>

<div class="empty">Belum ada data kader ditemukan.</div>

<?php } ?>

</div>

<script>
setTimeout(()=>{
let x=document.querySelector('.alert');
if(x){x.style.display='none';}
},3000);
</script>