<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['update'])){

$id_timbang      = (int) ($_POST['id_timbang'] ?? 0);
$id_anak         = (int) ($_POST['id_anak'] ?? 0);
$tanggal         = $_POST['tanggal'] ?? '';
$umur_bulan      = (int) ($_POST['umur_bulan'] ?? 0);
$berat           = (float) ($_POST['berat'] ?? 0);
$tinggi          = (float) ($_POST['tinggi'] ?? 0);
$lingkar_kepala  = (float) ($_POST['lingkar_kepala'] ?? 0);
$status_gizi     = $_POST['status_gizi'] ?? '';
$catatan         = trim($_POST['catatan'] ?? '');


// 🔥 VALIDASI PENTING
if($id_timbang <= 0){
    die("ID timbang tidak valid");
}

$stmt = $conn->prepare("
UPDATE penimbangan SET
id_anak=?,
tanggal=?,
umur_bulan=?,
berat=?,
tinggi=?,
lingkar_kepala=?,
status_gizi=?,
catatan=?
WHERE id_timbang=?
");

if(!$stmt){
    die("Prepare gagal: " . $conn->error);
}

$stmt->bind_param(
"isidddssi",
$id_anak,
$tanggal,
$umur_bulan,
$berat,
$tinggi,
$lingkar_kepala,
$status_gizi,
$catatan,
$id_timbang
);

if(!$stmt->execute()){
    die("Execute error: " . $stmt->error);
}

// 🔥 CEK apakah benar update
if($stmt->affected_rows === 0){
    // Tidak error, tapi tidak ada perubahan
    // Bisa karena data sama
    header("Location: ../../index.php?page=penimbangan&msg=nochange");
    exit;
}

header("Location: ../../index.php?page=penimbangan&msg=update");
exit;

}
?>