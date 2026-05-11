<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM ibu_hamil WHERE id_ibu=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan');location='index.php?page=ibu_hamil';</script>";
    exit;
}

$rt = $conn->query("SELECT * FROM rt ORDER BY nomor_rt");
?>

<style>
.wrap{max-width:1100px;margin:auto;}
.top{
display:flex;justify-content:space-between;align-items:center;
margin-bottom:20px;flex-wrap:wrap;gap:10px;
}
.top h2{font-size:30px;margin:0;color:#0f172a;}
.sub{font-size:14px;color:#64748b;}
.back{
padding:12px 18px;background:#e2e8f0;text-decoration:none;
border-radius:12px;font-weight:700;color:#0f172a;
}
.form-box{
background:#fff;padding:28px;border-radius:22px;
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
.group input,.group select,.group textarea{
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
<h2>Edit Data Ibu Hamil</h2>
<div class="sub">Perbarui data ibu hamil</div>
</div>

<a href="index.php?page=ibu_hamil" class="back">← Kembali</a>
</div>

<form method="POST" action="modules/ibu_hamil/update.php" class="form-box">

<input type="hidden" name="id_ibu" value="<?= $data['id_ibu']; ?>">

<div class="grid">

<div class="group">
<label>Nama Ibu</label>
<input type="text" name="nama_ibu" required value="<?= $data['nama_ibu']; ?>">
</div>

<div class="group">
<label>NIK</label>
<input type="text" name="nik" value="<?= $data['nik']; ?>">
</div>

<div class="group">
<label>RT</label>
<select name="id_rt" required>
<?php while($r = $rt->fetch_assoc()){ ?>
<option value="<?= $r['id_rt']; ?>"
<?= $r['id_rt']==$data['id_rt']?'selected':''; ?>>
<?= $r['nomor_rt']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="group">
<label>Umur</label>
<input type="number" name="umur" value="<?= $data['umur']; ?>">
</div>

<div class="group">
<label>Gravida</label>
<input type="text" name="gravida" value="<?= $data['gravida']; ?>">
</div>

<div class="group">
<label>Usia Kehamilan (Minggu)</label>
<input 
type="text" 
name="usia_kehamilan"
id="usia_kehamilan"
readonly>
</div>

<div class="group">
<label>HPHT</label>
<input 
type="date" 
name="hpht" 
id="hpht"
max="<?= date('Y-m-d'); ?>">
</div>

<div class="group">
<label>HPL</label>
<input 
type="date" 
name="hpl" 
id="hpl"
readonly>
</div>

<div class="group">
<label>Golongan Darah</label>
<input type="text" name="gol_darah" value="<?= $data['gol_darah']; ?>">
</div>

<div class="group">
<label>Tinggi Badan</label>
<input type="number" step="0.01" name="tinggi_badan" value="<?= $data['tinggi_badan']; ?>">
</div>

<div class="group">
<label>Berat Awal</label>
<input type="number" step="0.01" name="berat_awal" value="<?= $data['berat_awal']; ?>">
</div>

<div class="group">
<label>No BPJS</label>
<input type="text" name="bpjs" value="<?= $data['bpjs']; ?>">
</div>

<div class="group full">
<label>Risiko Kehamilan</label>
<textarea name="risiko_kehamilan"><?= $data['risiko_kehamilan']; ?></textarea>
</div>

</div>

<div class="action">

<button type="submit" class="save" name="update">
💾 Update Data
</button>

<a href="modules/ibu_hamil/hapus.php?id=<?= $data['id_ibu']; ?>"
onclick="return confirm('Yakin ingin menghapus data ini?')"
class="delete">
🗑 Hapus
</a>

</div>

</form>
<script>
document.getElementById('hpht').addEventListener('change', function () {

    let hpht = new Date(this.value);

    if(!isNaN(hpht.getTime())){

        // tambah 280 hari
        hpht.setDate(hpht.getDate() + 280);

        // format yyyy-mm-dd
        let tahun = hpht.getFullYear();
        let bulan = String(hpht.getMonth() + 1).padStart(2, '0');
        let hari  = String(hpht.getDate()).padStart(2, '0');

        document.getElementById('hpl').value =
            `${tahun}-${bulan}-${hari}`;
    }
});
</script>
<script>
document.getElementById('hpht').addEventListener('change', function () {

    let hpht = new Date(this.value);

    if(!isNaN(hpht.getTime())){

        // =========================
        // HITUNG HPL
        // =========================
        let hpl = new Date(hpht);
        hpl.setDate(hpl.getDate() + 280);

        let tahun = hpl.getFullYear();
        let bulan = String(hpl.getMonth() + 1).padStart(2, '0');
        let hari  = String(hpl.getDate()).padStart(2, '0');

        document.getElementById('hpl').value =
            `${tahun}-${bulan}-${hari}`;

        // =========================
        // HITUNG USIA KEHAMILAN
        // =========================
        let sekarang = new Date();

        let selisih = sekarang - hpht;

        let hariKehamilan = Math.floor(
            selisih / (1000 * 60 * 60 * 24)
        );

        let minggu = Math.floor(hariKehamilan / 7);
        let hariSisa = hariKehamilan % 7;

        document.getElementById('usia_kehamilan').value =
            minggu + " Minggu " + hariSisa + " Hari";
    }
});
</script>

</div>