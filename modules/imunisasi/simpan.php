<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* ===============================
   AMBIL DATA FORM
================================= */

$id_anak         = $_POST['id_anak'] ?? '';
$tanggal         = $_POST['tanggal'] ?? '';
$jenis_imunisasi = $_POST['jenis_imunisasi'] ?? '';
$dosis           = $_POST['dosis'] ?? '';
$petugas         = $_POST['petugas'] ?? '';
$keterangan      = $_POST['keterangan'] ?? '';

/* ===============================
   VALIDASI WAJIB
================================= */

if(
$id_anak == '' ||
$tanggal == '' ||
$jenis_imunisasi == ''
){
header("Location:/posyandu/index.php?page=imunisasi_tambah&msg=gagal");
exit;
}

/* ===============================
   SIMPAN DATA
================================= */

$stmt = $conn->prepare("
INSERT INTO imunisasi
(
id_anak,
tanggal,
jenis_imunisasi,
dosis,
petugas,
keterangan
)
VALUES
(
?,
?,
?,
?,
?,
?
)
");

$stmt->bind_param(
"isssss",
$id_anak,
$tanggal,
$jenis_imunisasi,
$dosis,
$petugas,
$keterangan
);

$save = $stmt->execute();

/* ===============================
   REDIRECT
================================= */

if($save){

header("Location:/posyandu/index.php?page=imunisasi&msg=simpan");

}else{

header("Location:/posyandu/index.php?page=imunisasi_tambah&msg=gagal");

}
exit;
?>