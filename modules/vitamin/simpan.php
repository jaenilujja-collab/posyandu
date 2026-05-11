<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['simpan'])){

$id_anak        = $_POST['id_anak'];
$tanggal        = $_POST['tanggal'];
$jenis_vitamin  = trim($_POST['jenis_vitamin']);
$dosis          = trim($_POST['dosis']);
$petugas        = trim($_POST['petugas']);
$keterangan     = trim($_POST['keterangan']);

$stmt = $conn->prepare("
INSERT INTO vitamin(
id_anak,
tanggal,
jenis_vitamin,
dosis,
petugas,
keterangan
)
VALUES(?,?,?,?,?,?)
");

$stmt->bind_param(
"isssss",
$id_anak,
$tanggal,
$jenis_vitamin,
$dosis,
$petugas,
$keterangan
);

$stmt->execute();

header("Location: ../../index.php?page=vitamin&msg=simpan");
exit;

}
?>