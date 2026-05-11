<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* ===============================
   AMBIL DATA FORM
================================= */

$id_imunisasi    = $_POST['id_imunisasi'] ?? '';
$id_anak         = $_POST['id_anak'] ?? '';
$tanggal         = $_POST['tanggal'] ?? '';
$jenis_imunisasi = $_POST['jenis_imunisasi'] ?? '';
$dosis           = $_POST['dosis'] ?? '';
$petugas         = $_POST['petugas'] ?? '';
$keterangan      = $_POST['keterangan'] ?? '';

/* ===============================
   VALIDASI
================================= */

if(
$id_imunisasi == '' ||
$id_anak == '' ||
$tanggal == '' ||
$jenis_imunisasi == ''
){
header("Location:/posyandu/index.php?page=imunisasi&msg=gagal");
exit;
}

/* ===============================
   UPDATE DATA
================================= */

$stmt = $conn->prepare("
UPDATE imunisasi SET
id_anak = ?,
tanggal = ?,
jenis_imunisasi = ?,
dosis = ?,
petugas = ?,
keterangan = ?
WHERE id_imunisasi = ?
");

$stmt->bind_param(
"isssssi",
$id_anak,
$tanggal,
$jenis_imunisasi,
$dosis,
$petugas,
$keterangan,
$id_imunisasi
);

$update = $stmt->execute();

/* ===============================
   REDIRECT
================================= */

if($update){

header("Location:/posyandu/index.php?page=imunisasi&msg=update");

}else{

header("Location:/posyandu/index.php?page=imunisasi_edit&id=".$id_imunisasi."&msg=gagal");

}

exit;
?>