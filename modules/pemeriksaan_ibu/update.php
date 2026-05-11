<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if(isset($_POST['update'])){

    $id_periksa          = (int) $_POST['id_periksa'];
    $id_ibu              = (int) $_POST['id_ibu'];
    $tanggal             = $_POST['tanggal'];

    $berat_badan         = (float) $_POST['berat_badan'];
    $tekanan_darah       = trim($_POST['tekanan_darah']);
    $lingkar_lengan      = (float) $_POST['lingkar_lengan'];
    $tinggi_fundus       = (float) $_POST['tinggi_fundus'];

    $detak_jantung_janin = trim($_POST['detak_jantung_janin']);
    $keluhan             = trim($_POST['keluhan']);
    $tindakan            = trim($_POST['tindakan']);

    $tablet_fe           = (int) $_POST['tablet_fe'];

    $imunisasi_tt        = trim($_POST['imunisasi_tt']);
    $rujukan             = trim($_POST['rujukan']);
    $catatan             = trim($_POST['catatan']);
    $petugas             = trim($_POST['petugas']);

    /* =========================
       AMBIL HPHT
    ========================= */
    $get = $conn->query("
        SELECT hpht
        FROM ibu_hamil
        WHERE id_ibu='$id_ibu'
    ");

    $ibu = $get->fetch_assoc();

    /* =========================
       HITUNG USIA KEHAMILAN
    ========================= */
    $usia_kehamilan = 0;

    if(!empty($ibu['hpht']) && $ibu['hpht'] != '0000-00-00'){

        $today = new DateTime();

        $hphtDate = new DateTime($ibu['hpht']);

        $selisih = $today->diff($hphtDate);

        $usia_kehamilan = floor($selisih->days / 7);
    }

    /* =========================
       UPDATE
    ========================= */
    $stmt = $conn->prepare("
        UPDATE pemeriksaan_ibu SET
            id_ibu=?,
            tanggal=?,
            usia_kehamilan=?,
            berat_badan=?,
            tekanan_darah=?,
            lingkar_lengan=?,
            tinggi_fundus=?,
            detak_jantung_janin=?,
            keluhan=?,
            tindakan=?,
            tablet_fe=?,
            imunisasi_tt=?,
            rujukan=?,
            catatan=?,
            petugas=?
        WHERE id_periksa=?
    ");

    $stmt->bind_param(
        "isidsdssssissssi",
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
        $petugas,
        $id_periksa
    );

    $stmt->execute();

    header("Location: ../../index.php?page=pemeriksaan_ibu&msg=update");
    exit;
}
?>