<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

/* AMBIL ID */
$id = (int) ($_GET['id'] ?? 0);

/* VALIDASI */
if($id <= 0){

header("Location:/posyandu/index.php?page=ibu_hamil&msg=gagal");
exit;

}

/* HAPUS DATA */
$stmt = $conn->prepare("
DELETE FROM ibu_hamil
WHERE id_ibu = ?
");

$stmt->bind_param("i", $id);

$hapus = $stmt->execute();

/* NOTIFIKASI */
if($hapus){

header("Location:/posyandu/index.php?page=ibu_hamil&msg=hapus");

}else{

header("Location:/posyandu/index.php?page=ibu_hamil&msg=gagal");

}

exit;
?>