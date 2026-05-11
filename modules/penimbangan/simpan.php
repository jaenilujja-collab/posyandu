<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['simpan'])){

$id_anak         = (int) ($_POST['id_anak'] ?? 0);
$tanggal         = $_POST['tanggal'] ?? '';
$umur_bulan      = (int) ($_POST['umur_bulan'] ?? 0);
$berat           = (float) ($_POST['berat'] ?? 0);
$tinggi          = (float) ($_POST['tinggi'] ?? 0);
$lingkar_kepala  = (float) ($_POST['lingkar_kepala'] ?? 0);
$status_gizi     = $_POST['status_gizi'] ?? '';
$catatan         = trim($_POST['catatan'] ?? '');


// 🔥 VALIDASI WAJIB (biar tidak gagal diam-diam)
if($id_anak <= 0){
    die("Anak belum dipilih");
}

if($tanggal == ''){
    die("Tanggal wajib diisi");
}

$stmt = $conn->prepare("
INSERT INTO penimbangan(
id_anak,
tanggal,
umur_bulan,
berat,
tinggi,
lingkar_kepala,
status_gizi,
catatan
)
VALUES(?,?,?,?,?,?,?,?)
");

if(!$stmt){
    die("Prepare gagal: " . $conn->error);
}

$stmt->bind_param(
"isidddss",
$id_anak,
$tanggal,
$umur_bulan,
$berat,
$tinggi,
$lingkar_kepala,
$status_gizi,
$catatan
);

if(!$stmt->execute()){
    die("Execute error: " . $stmt->error);
}

header("Location: ../../index.php?page=penimbangan&msg=simpan");
exit;

}
?>