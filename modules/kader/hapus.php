<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

if ($id == 0) {
    header("Location: ../../index.php?page=kader");
    exit;
}

$conn->begin_transaction();

try {

    /* ===============================
       CEK USER BERDASARKAN KADER
    =============================== */
    $cek = $conn->prepare("
        SELECT id_user
        FROM users
        WHERE id_kader=?
        LIMIT 1
    ");

    $cek->bind_param("i", $id);
    $cek->execute();

    $result = $cek->get_result();
    $user   = $result->fetch_assoc();

    /* ===============================
       HAPUS USER JIKA ADA
    =============================== */
    if ($user) {

        $hapusUser = $conn->prepare("
            DELETE FROM users
            WHERE id_user=?
        ");

        $hapusUser->bind_param("i", $user['id_user']);
        $hapusUser->execute();
    }

    /* ===============================
       HAPUS KADER
    =============================== */
    $hapusKader = $conn->prepare("
        DELETE FROM kader
        WHERE id_kader=?
    ");

    $hapusKader->bind_param("i", $id);
    $hapusKader->execute();

    $conn->commit();

    header("Location: ../../index.php?page=kader&msg=hapus");
    exit;

} catch (Exception $e) {

    $conn->rollback();

    header("Location: ../../index.php?page=kader&msg=gagal");
    exit;
}
?>