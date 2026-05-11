<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* ambil id */
$id = (int)($_GET['id'] ?? 0);

/* validasi */
if($id <= 0){
    header("Location: ../../index.php?page=anak&msg=error");
    exit;
}

/* cek data ada atau tidak */
$cek = $conn->prepare("SELECT id_anak FROM anak WHERE id_anak=?");
$cek->bind_param("i", $id);
$cek->execute();
$hasil = $cek->get_result();

if($hasil->num_rows == 0){
    header("Location: ../../index.php?page=anak&msg=notfound");
    exit;
}

/* hapus data */
$stmt = $conn->prepare("DELETE FROM anak WHERE id_anak=?");
$stmt->bind_param("i", $id);

try{

    if($stmt->execute()){
        header("Location: ../../index.php?page=anak&msg=delete");
        exit;
    }else{
        header("Location: ../../index.php?page=anak&msg=error");
        exit;
    }

}catch(mysqli_sql_exception $e){

    /* jika dipakai relasi tabel lain */
    header("Location: ../../index.php?page=anak&msg=used");
    exit;
}
?>