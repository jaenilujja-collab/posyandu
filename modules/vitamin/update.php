<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['update'])){

$id_vitamin     = $_POST['id_vitamin'];
$id_anak        = $_POST['id_anak'];
$tanggal        = $_POST['tanggal'];
$jenis_vitamin  = trim($_POST['jenis_vitamin']);
$dosis          = trim($_POST['dosis']);
$petugas        = trim($_POST['petugas']);
$keterangan     = trim($_POST['keterangan']);

$stmt = $conn->prepare("
UPDATE vitamin SET
id_anak=?,
tanggal=?,
jenis_vitamin=?,
dosis=?,
petugas=?,
keterangan=?
WHERE id_vitamin=?
");

$stmt->bind_param(
"isssssi",
$id_anak,
$tanggal,
$jenis_vitamin,
$dosis,
$petugas,
$keterangan,
$id_vitamin
);

$stmt->execute();

header("Location: ../../index.php?page=vitamin&msg=update");
exit;

}
?>