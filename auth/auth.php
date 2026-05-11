<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===============================
   WAJIB LOGIN
================================= */
function require_login()
{
    if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
        session_destroy();
        header("Location: /posyandu/auth/login.php");
        exit;
    }

    if ($_SESSION['user']['status'] !== 'aktif') {
        session_destroy();
        header("Location: /posyandu/auth/login.php");
        exit;
    }
}

/* ===============================
   CEK ROLE USER
================================= */
function require_role($roles = [])
{
    require_login();

    if (!in_array($_SESSION['user']['level'], $roles)) {
        header("Location: /posyandu/index.php?error=forbidden");
        exit;
    }
}

/* ===============================
   USER LOGIN SEKARANG
================================= */
function current_user()
{
    return $_SESSION['user'] ?? null;
}

/* ===============================
   CEK ROLE CEPAT
================================= */
function is_admin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['level'] === 'admin';
}

function is_kader()
{
    return isset($_SESSION['user']) && $_SESSION['user']['level'] === 'kader';
}
if (!isset($_SESSION['user']['level'])) {
    session_destroy();
    header("Location: /posyandu/auth/login.php");
    exit;
}
?>