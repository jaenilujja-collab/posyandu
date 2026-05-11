<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['simpan'])){

    $id_rt            = (int) $_POST['id_rt'];
    $nama_ibu         = trim($_POST['nama_ibu']);
    $nik              = trim($_POST['nik']);
    $umur             = (int) $_POST['umur'];
    $gravida          = trim($_POST['gravida']);
    $usia_kehamilan   = trim($_POST['usia_kehamilan']);

    // pastikan format tanggal valid
    $hpht = !empty($_POST['hpht']) ? $_POST['hpht'] : null;
    $hpl  = !empty($_POST['hpl'])  ? $_POST['hpl']  : null;

    $gol_darah        = trim($_POST['gol_darah']);
    $tinggi_badan     = (float) $_POST['tinggi_badan'];
    $berat_awal       = (float) $_POST['berat_awal'];
    $risiko_kehamilan = trim($_POST['risiko_kehamilan']);
    $bpjs             = trim($_POST['bpjs']);

    $stmt = $conn->prepare("
        INSERT INTO ibu_hamil(
            id_rt,
            nama_ibu,
            nik,
            umur,
            gravida,
            usia_kehamilan,
            hpht,
            hpl,
            gol_darah,
            tinggi_badan,
            berat_awal,
            risiko_kehamilan,
            bpjs
        )
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ississsssddss",
        $id_rt,
        $nama_ibu,
        $nik,
        $umur,
        $gravida,
        $usia_kehamilan,
        $hpht,
        $hpl,
        $gol_darah,
        $tinggi_badan,
        $berat_awal,
        $risiko_kehamilan,
        $bpjs
    );

    if($stmt->execute()){
        header("Location: ../../index.php?page=ibu_hamil&msg=simpan");
        exit;
    } else {
        echo "Gagal menyimpan data";
    }
}
?>