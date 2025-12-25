<?php
$host = "localhost";
$user = "root";     // Sesuaikan user database kamu
$pass = "";         // Sesuaikan password database kamu
$db   = "web-desa";  // Nama database

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>