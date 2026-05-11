<?php
require_once __DIR__ . '/../../auth/auth.php';
require_login();

require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_kader = trim($_POST['nama_kader'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    $username   = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $level      = $_POST['level'] ?? 'kader';

    /* VALIDASI */
    if ($nama_kader == '' || $username == '' || $password == '') {
        header("Location: ../../index.php?page=kader_tambah&msg=gagal");
        exit;
    }

    /* VALIDASI LEVEL */
    if (!in_array($level, ['admin','kader'])) {
        $level = 'kader';
    }

    /* ===============================
       CEK USERNAME
    =============================== */
    $cek = $conn->prepare("
        SELECT id_user
        FROM users
        WHERE username=?
    ");

    $cek->bind_param("s", $username);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        header("Location: ../../index.php?page=kader_tambah&msg=username");
        exit;
    }

    /* ===============================
       TRANSACTION
    =============================== */
    $conn->begin_transaction();

    try {

        /* ===============================
           1. SIMPAN KADER
        =============================== */
        $stmt1 = $conn->prepare("
            INSERT INTO kader (
                nama_kader,
                no_hp
            ) VALUES (?,?)
        ");

        $stmt1->bind_param(
            "ss",
            $nama_kader,
            $no_hp
        );

        $stmt1->execute();

        $id_kader = $conn->insert_id;

        /* ===============================
           2. SIMPAN USERS
        =============================== */
        $hash   = password_hash($password, PASSWORD_DEFAULT);
        $status = 'aktif';

        $stmt2 = $conn->prepare("
            INSERT INTO users (
                id_kader,
                nama,
                username,
                password,
                level,
                status
            ) VALUES (?,?,?,?,?,?)
        ");

        $stmt2->bind_param(
            "isssss",
            $id_kader,
            $nama_kader,
            $username,
            $hash,
            $level,
            $status
        );

        $stmt2->execute();

        $conn->commit();

        header("Location: ../../index.php?page=kader&msg=simpan");
        exit;

    } catch (Exception $e) {

        $conn->rollback();

        header("Location: ../../index.php?page=kader_tambah&msg=gagal");
        exit;
    }
}

header("Location: ../../index.php?page=kader");
exit;
?>