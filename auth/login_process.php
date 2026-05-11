<?php
session_start();

// 🔥 RESET SESSION TOTAL (INI KUNCI FIX BUG)
$_SESSION = [];
session_unset();
session_destroy();
session_start();

require_once __DIR__ . "/../config/db.php";
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username == '' || $password == '') {
    header("Location: login.php?error=1");
    exit;
}

/* ===============================
   AMBIL USER AKTIF
================================= */
$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE username=?
    AND status='aktif'
    LIMIT 1
");

$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$data   = $result->fetch_assoc();

/* ===============================
   CEK USER
================================= */
if ($data) {

    $loginBerhasil = false;

    // PASSWORD HASH
    if (password_verify($password, $data['password'])) {
        $loginBerhasil = true;
    }

    // BACKWARD COMPATIBLE (password lama)
    elseif ($password === $data['password']) {
        $loginBerhasil = true;

        // 🔥 upgrade otomatis ke hash
        $hashBaru = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE id_user=?");
        $update->bind_param("si", $hashBaru, $data['id_user']);
        $update->execute();
    }

    if ($loginBerhasil) {

        session_regenerate_id(true);

        // 🔥 SESSION STANDARD (SAMA DENGAN AUTH.PHP)
$_SESSION['user'] = [
    'id_user'  => $data['id_user'],
    'id_kader' => $data['id_kader'],
    'nama'     => $data['nama'],
    'username' => $data['username'],
    'level'    => $data['level'],
    'status'   => $data['status']
];

        header("Location: ../index.php");
        exit;
    }
}

/* ===============================
   GAGAL LOGIN
================================= */
header("Location: login.php?error=1");
exit;
?>