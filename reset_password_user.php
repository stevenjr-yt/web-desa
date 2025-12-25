<?php
session_start();
include 'includes/koneksi.php';

// HANYA DEVELOPER YG BOLEH AKSES
if ($_SESSION['user_role'] != 1) { header("Location: dashboard"); exit; }

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    $default_pass = password_hash('123456', PASSWORD_DEFAULT); // Reset jadi 123456
    
    mysqli_query($conn, "UPDATE users SET password='$default_pass' WHERE id='$id_user'");
    echo "<script>alert('Password berhasil direset menjadi: 123456'); window.location='kelola_user.php';</script>";
}
?>