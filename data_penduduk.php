<?php
session_start();
include 'includes/koneksi.php';

// Cek Akses
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: dashboard.php"); exit;
}

// Logic Tambah & Hapus (Sama kayak sebelumnya)
if (isset($_POST['tambah_warga'])) {
    $nik = $_POST['nik']; $nama = $_POST['nama']; $jk = $_POST['jk']; 
    $alamat = $_POST['alamat']; $pek = $_POST['pekerjaan'];
    if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM penduduk WHERE nik='$nik'")) > 0){
        echo "<script>alert('NIK Sudah Ada!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO penduduk (nik, nama, jk, alamat, pekerjaan) VALUES ('$nik','$nama','$jk','$alamat','$pek')");
        $pass = password_hash($nik, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role_level) VALUES ('$nik','$pass','$nama', 5)");
        echo "<script>window.location='data_penduduk.php';</script>";
    }
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $dt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nik FROM penduduk WHERE id='$id'"));
    mysqli_query($conn, "DELETE FROM penduduk WHERE id='$id'");
    mysqli_query($conn, "DELETE FROM users WHERE username='$dt[nik]'");
    header("Location: data_penduduk.php");
}

// DATA CHART
$lk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE jk='L'"))['t'];
$pr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE jk='P'"))['t'];

include 'includes/header.php'; include 'includes/sidebar.php'; include 'includes/navbar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Statistik Demografi</h3>
            <p class="text-slate-500">Total Warga: <span class="font-bold text-emerald-600"><?= $lk+$pr; ?></span> Jiwa</p>
        </div>
        <div style="height: 150px; width: 300px;">
            <canvas id="pendudukChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h4 class="font-bold text-emerald-600 mb-4">Input Data Warga Baru</h4>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="number" name="nik" required class="w-full border rounded-lg px-3 py-2" placeholder="NIK (Auto jadi Username Login)">
            <input type="text" name="nama" required class="w-full border rounded-lg px-3 py-2" placeholder="Nama Lengkap">
            <select name="jk" class="w-full border rounded-lg px-3 py-2"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>
            <input type="text" name="pekerjaan" required class="w-full border rounded-lg px-3 py-2" placeholder="Pekerjaan">
            <textarea name="alamat" required class="w-full border rounded-lg px-3 py-2 md:col-span-2" placeholder="Alamat Lengkap" rows="2"></textarea>
            <button type="submit" name="tambah_warga" class="w-full bg-emerald-600 text-white py-2 rounded-lg font-bold hover:bg-emerald-700 md:col-span-2">Simpan Data</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-gray-50 font-bold uppercase text-xs">
                <tr><th class="p-4">NIK</th><th class="p-4">Nama</th><th class="p-4">JK</th><th class="p-4">Alamat</th><th class="p-4 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $q=mysqli_query($conn, "SELECT * FROM penduduk ORDER BY id DESC"); while($r=mysqli_fetch_assoc($q)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-mono font-bold text-emerald-600"><?= $r['nik']; ?></td>
                    <td class="p-4 font-bold text-slate-800"><?= $r['nama']; ?></td>
                    <td class="p-4"><?= $r['jk']; ?></td>
                    <td class="p-4 text-xs"><?= $r['alamat']; ?></td>
                    <td class="p-4 text-right"><a href="?hapus=<?= $r['id']; ?>" onclick="return confirm('Hapus?')" class="text-red-500 font-bold hover:underline">Hapus</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    const ctx = document.getElementById('pendudukChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{ data: [<?= $lk; ?>, <?= $pr; ?>], backgroundColor: ['#3b82f6', '#ec4899'] }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
    });
</script>
<?php include 'includes/footer.php'; ?>