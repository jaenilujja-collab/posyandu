<?php
$conn = new mysqli("127.0.0.1", "root", "", "db_posyandu", 3306);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
date_default_timezone_set("Asia/Jakarta");
?>