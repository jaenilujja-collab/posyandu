<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

if($id > 0){

$stmt = $conn->prepare("
DELETE FROM pemeriksaan_ibu
WHERE id_periksa=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

}

header("Location: ../../index.php?page=pemeriksaan_ibu&msg=hapus");
exit;
?>