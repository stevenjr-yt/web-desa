<?php
session_start();
include 'includes/koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['user_login'])) { 
    header("Location: login.php"); 
    exit; 
}

$role = $_SESSION['user_role'];

// 2. Cek Role: Hanya Kades (2) DAN Sekretaris (3) yang boleh masuk sini
if ($role != 2 && $role != 3) {
    echo "<script>alert('Akses Ditolak! Anda tidak punya wewenang.'); window.location='dashboard.php';</script>";
    exit;
}

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];
    
    // --- AKSI 1: VERIFIKASI (Khusus Sekretaris -> Teruskan ke Kades) ---
    if ($aksi == 'verifikasi') {
        if($role == 3) {
            $q = "UPDATE surat SET status='Verifikasi Sekdes' WHERE id='$id'";
            if (mysqli_query($conn, $q)) {
                echo "<script>alert('Surat Berhasil Diverifikasi & Diteruskan ke Kades!'); window.location='layanan_surat.php';</script>";
            }
        } else {
            echo "<script>alert('Hanya Sekretaris yang bisa melakukan verifikasi awal!'); window.location='dashboard.php';</script>";
        }
        
    // --- AKSI 2: TANDA TANGAN (Khusus Kades -> Selesai) ---
    } elseif ($aksi == 'ttd') {
        if($role == 2) {
            $q = "UPDATE surat SET status='Siap Diambil', tgl_approval=NOW() WHERE id='$id'";
            if (mysqli_query($conn, $q)) {
                echo "<script>alert('Surat Telah Ditandatangani & Siap Diambil Warga!'); window.location='dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Hanya Kades yang bisa tanda tangan!'); window.location='layanan_surat.php';</script>";
        }

    // --- AKSI 3: TOLAK (Bisa Sekretaris / Bisa Kades) ---
    } elseif ($aksi == 'tolak') {
        $q = "UPDATE surat SET status='Ditolak' WHERE id='$id'";
        
        if (mysqli_query($conn, $q)) {
            echo "<script>alert('Surat Ditolak!');</script>";
            
            // Redirect balik sesuai siapa yang nolak
            if($role == 2) {
                echo "<script>window.location='dashboard.php';</script>"; // Kades balik ke dashboard
            } else {
                echo "<script>window.location='layanan_surat.php';</script>"; // Sekdes balik ke layanan surat
            }
        }
    }
}
?>