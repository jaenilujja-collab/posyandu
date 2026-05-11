<?php
require_once __DIR__ . '/../../auth/auth.php';
require_role(['admin']);

require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_kader   = $_POST['id_kader'] ?? 0;
    $nama_kader = trim($_POST['nama_kader'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $level      = $_POST['level'] ?? 'kader';
    $status     = $_POST['status'] ?? 'aktif';

    if ($id_kader == 0 || $nama_kader == '' || $username == '') {
        header("Location: ../../index.php?page=kader&msg=gagal");
        exit;
    }

    /* ===============================
       CEK USER BERDASARKAN KADER
    =============================== */
    $cekUser = $conn->prepare("
        SELECT id_user FROM users WHERE id_kader=? LIMIT 1
    ");
    $cekUser->bind_param("i", $id_kader);
    $cekUser->execute();
    $res = $cekUser->get_result();
    $u   = $res->fetch_assoc();

    $id_user = $u['id_user'] ?? 0;

    /* ===============================
       CEK USERNAME DUPLIKAT
    =============================== */
    $cek = $conn->prepare("
        SELECT id_user FROM users WHERE username=? AND id_user!=?
    ");
    $cek->bind_param("si", $username, $id_user);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        header("Location: ../../index.php?page=kader_edit&id=".$id_kader."&msg=username");
        exit;
    }

    $conn->begin_transaction();

    try {

        /* ===============================
           UPDATE KADER
        =============================== */
        $stmt1 = $conn->prepare("
            UPDATE kader SET
                nama_kader=?,
                no_hp=?
            WHERE id_kader=?
        ");

        $stmt1->bind_param(
            "ssi",
            $nama_kader,
            $no_hp,
            $id_kader
        );

        $stmt1->execute();

        /* ===============================
           UPDATE / INSERT USERS
        =============================== */

        if ($id_user) {

            // UPDATE USER
            if ($password != '') {

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt2 = $conn->prepare("
                    UPDATE users SET
                        nama=?,
                        username=?,
                        password=?,
                        level=?,
                        status=?
                    WHERE id_user=?
                ");

                $stmt2->bind_param(
                    "sssssi",
                    $nama_kader,
                    $username,
                    $hash,
                    $level,
                    $status,
                    $id_user
                );

            } else {

                $stmt2 = $conn->prepare("
                    UPDATE users SET
                        nama=?,
                        username=?,
                        level=?,
                        status=?
                    WHERE id_user=?
                ");

                $stmt2->bind_param(
                    "ssssi",
                    $nama_kader,
                    $username,
                    $level,
                    $status,
                    $id_user
                );
            }

            $stmt2->execute();

        } else {

            // BUAT USER BARU
            $hash = password_hash($password ?: '123456', PASSWORD_DEFAULT);

            $stmt2 = $conn->prepare("
                INSERT INTO users (id_kader,nama,username,password,level,status)
                VALUES (?,?,?,?,?,?)
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
        }

        $conn->commit();

        header("Location: ../../index.php?page=kader&msg=update");
        exit;

    } catch (Exception $e) {

        $conn->rollback();

        echo "Error: " . $e->getMessage();
    }
}
?>