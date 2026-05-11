<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['simpan'])){

$id_ibu              = $_POST['id_ibu'];
$tanggal             = $_POST['tanggal'];
$usia_kehamilan      = $_POST['usia_kehamilan'];
$berat_badan         = $_POST['berat_badan'];
$tekanan_darah       = trim($_POST['tekanan_darah']);
$lingkar_lengan      = $_POST['lingkar_lengan'];
$tinggi_fundus       = $_POST['tinggi_fundus'];
$detak_jantung_janin = trim($_POST['detak_jantung_janin']);
$keluhan             = trim($_POST['keluhan']);
$tindakan            = trim($_POST['tindakan']);
$tablet_fe           = $_POST['tablet_fe'];
$imunisasi_tt        = trim($_POST['imunisasi_tt']);
$rujukan             = trim($_POST['rujukan']);
$catatan             = trim($_POST['catatan']);
$petugas             = trim($_POST['petugas']);

$stmt = $conn->prepare("
INSERT INTO pemeriksaan_ibu(
id_ibu,tanggal,usia_kehamilan,berat_badan,tekanan_darah,
lingkar_lengan,tinggi_fundus,detak_jantung_janin,
keluhan,tindakan,tablet_fe,imunisasi_tt,
rujukan,catatan,petugas
)
VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
"isidsddsssissss",
$id_ibu,
$tanggal,
$usia_kehamilan,
$berat_badan,
$tekanan_darah,
$lingkar_lengan,
$tinggi_fundus,
$detak_jantung_janin,
$keluhan,
$tindakan,
$tablet_fe,
$imunisasi_tt,
$rujukan,
$catatan,
$petugas
);

$stmt->execute();

header("Location: ../../index.php?page=pemeriksaan_ibu&msg=simpan");
exit;

}
?>