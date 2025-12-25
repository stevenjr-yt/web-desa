<?php
session_start();
include 'includes/koneksi.php';

// Jika sudah login, cek role untuk redirect
if (isset($_SESSION['user_login'])) {
    if ($_SESSION['user_role'] == 5) {
        header("Location: index.php"); // Warga ke Index
    } else {
        header("Location: dashboard.php"); // Admin ke Dashboard
    }
    exit;
}

$error = "";
if (isset($_POST['btn_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        
        if (password_verify($password, $data['password'])) {
            // SET SESSION
            $_SESSION['user_login'] = true;
            $_SESSION['user_id']    = $data['id'];
            $_SESSION['user_name']  = $data['nama_lengkap'];
            $_SESSION['user_role']  = $data['role_level'];
            $_SESSION['user_nik']   = $data['username']; // Username warga adalah NIK

            // REDIRECT SESUAI ROLE
            if ($data['role_level'] == 5) {
                header("Location: index.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username/NIK tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm border-t-4 border-emerald-600">
        <h2 class="text-2xl font-bold text-center text-emerald-800 mb-2">Login Masuk</h2>
        <p class="text-center text-gray-400 text-sm mb-6">Silakan masuk menggunakan akun Anda</p>
        
        <?php if($error): ?>
            <div class="bg-red-50 text-red-600 px-4 py-2 rounded text-sm mb-4 border border-red-200 text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username / NIK</label>
                <input type="text" name="username" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Masukan Username/NIK" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="••••••••" required>
            </div>
            <button type="submit" name="btn_login" class="w-full bg-emerald-600 text-white py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition shadow-md">Masuk Sekarang</button>
        </form>
        
        <div class="mt-6 text-center text-xs text-gray-400">
            <a href="index.php" class="hover:text-emerald-600">← Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>