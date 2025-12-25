<?php
session_start();
include 'includes/koneksi.php';

// Cek Akses: Hanya Developer (1) & Sekdes (3)
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: dashboard.php");
    exit;
}

// LOGIC: Tambah User Baru
if (isset($_POST['tambah_user'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Cek Username Kembar
    $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE username='$user'"));
    if($cek > 0) {
        echo "<script>alert('Username sudah dipakai!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role_level) VALUES ('$user', '$pass', '$nama', '$role')");
        echo "<script>alert('User Berhasil Ditambah'); window.location='kelola_user.php';</script>";
    }
}

// LOGIC: Hapus User
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    // Cegah hapus diri sendiri & Super Admin
    if($id != $_SESSION['user_id'] && $id != 1) { 
        mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    }
    header("Location: kelola_user.php");
}

include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-6">
                <h3 class="font-bold text-slate-800 mb-4">Tambah Pegawai Baru</h3>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Username Login</label>
                        <input type="text" name="username" required class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Password Awal</label>
                        <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Jabatan / Role</label>
                        <select name="role" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="3">Sekretaris Desa</option>
                            <option value="4">Bendahara Desa</option>
                            <option value="2">Kepala Desa (Monitoring)</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_user" class="w-full bg-slate-800 text-white py-2 rounded-lg hover:bg-slate-900 transition font-medium">Simpan User</button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700">Daftar Pengguna Sistem</h3>
                    <?php if($_SESSION['user_role'] == 1): ?>
                    <a href="reset_password_user.php" class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-full font-bold hover:bg-red-200">⚠️ Menu Reset Password</a>
                    <?php endif; ?>
                </div>
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-white border-b">
                        <tr>
                            <th class="p-4">Nama</th>
                            <th class="p-4">Username</th>
                            <th class="p-4">Role</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php 
                        // INI BAGIAN PENTING: Loop while mendefinisikan variabel $u
                        $q = mysqli_query($conn, "SELECT * FROM users ORDER BY role_level ASC"); 
                        while($u = mysqli_fetch_assoc($q)): 
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-slate-800"><?= $u['nama_lengkap']; ?></td>
                            <td class="p-4 font-mono text-xs bg-slate-50 w-fit rounded"><?= $u['username']; ?></td>
                            <td class="p-4">
                                <?php 
                                    if($u['role_level']==1) echo '<span class="text-purple-600 font-bold">Developer</span>';
                                    elseif($u['role_level']==2) echo '<span class="text-blue-600">Kades</span>';
                                    elseif($u['role_level']==3) echo '<span class="text-emerald-600">Sekretaris</span>';
                                    elseif($u['role_level']==4) echo '<span class="text-orange-600">Bendahara</span>';
                                    elseif($u['role_level']==5) echo '<span class="text-gray-500">Warga</span>';
                                ?>
                            </td>
                            <td class="p-4 text-right">
                                <?php 
                                // Cek ID biar gak error
                                $my_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                                
                                if($u['id'] != 1 && $u['id'] != $my_id): 
                                ?>
                                    <a href="?del=<?= $u['id']; ?>" onclick="return confirm('Hapus user ini?')" class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                <?php else: ?>
                                    <span class="text-gray-300 cursor-not-allowed text-xs">Locked</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>