<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* wajib POST */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../../index.php?page=anak&msg=error");
    exit;
}

/* =========================================
   AMBIL DATA
========================================= */
$id_rt            = (int)($_POST['id_rt'] ?? 0);
$nama_anak        = trim($_POST['nama_anak'] ?? '');
$nik              = trim($_POST['nik'] ?? '');
$jenis_kelamin    = $_POST['jenis_kelamin'] ?? '';
$tempat_lahir     = trim($_POST['tempat_lahir'] ?? '');
$tanggal_lahir    = $_POST['tanggal_lahir'] ?? '';

$berat_lahir      = ($_POST['berat_lahir'] === '') ? 0 : (float)$_POST['berat_lahir'];
$panjang_lahir    = ($_POST['panjang_lahir'] === '') ? 0 : (float)$_POST['panjang_lahir'];
$lingkar_kepala   = ($_POST['lingkar_kepala'] === '') ? 0 : (float)$_POST['lingkar_kepala'];

$asi_eksklusif    = (($_POST['asi_eksklusif'] ?? '') === 'Ya') ? 'Ya' : 'Tidak';
$gol_darah        = trim($_POST['gol_darah'] ?? '');
$alergi           = trim($_POST['alergi'] ?? '');
$status_stunting  = (($_POST['status_stunting'] ?? '') === 'Ya') ? 'Ya' : 'Tidak';

/* =========================================
   VALIDASI
========================================= */
if(
    $id_rt <= 0 ||
    $nama_anak == '' ||
    $jenis_kelamin == '' ||
    $tanggal_lahir == ''
){
    header("Location: ../../index.php?page=anak_tambah&msg=error");
    exit;
}

/* =========================================
   SIMPAN
========================================= */
$stmt = $conn->prepare("
INSERT INTO anak(
id_rt,nama_anak,nik,jenis_kelamin,tempat_lahir,
tanggal_lahir,berat_lahir,panjang_lahir,lingkar_kepala,
asi_eksklusif,gol_darah,alergi,status_stunting
)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
"isssssdddssss",
$id_rt,
$nama_anak,
$nik,
$jenis_kelamin,
$tempat_lahir,
$tanggal_lahir,
$berat_lahir,
$panjang_lahir,
$lingkar_kepala,
$asi_eksklusif,
$gol_darah,
$alergi,
$status_stunting
);

try{

    if($stmt->execute()){
        header("Location: ../../index.php?page=anak&msg=success");
        exit;
    }else{
        header("Location: ../../index.php?page=anak_tambah&msg=error");
        exit;
    }

}catch(mysqli_sql_exception $e){

    die($e->getMessage());
}
?>