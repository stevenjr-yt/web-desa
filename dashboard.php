<?php
session_start();
include 'includes/koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['user_login'])) {
    header("Location: login.php");
    exit;
}

// 2. Proteksi Warga (Role 5)
if ($_SESSION['user_role'] == 5) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['user_role'];
$nama_user = $_SESSION['user_name'];

// --- QUERY DATA UNTUK GRAFIK & STATISTIK ---
// Data Penduduk
$count_penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM penduduk"))['total'];
$lk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE jk='L'"))['t'];
$pr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE jk='P'"))['t'];

// Data Surat Kades
$count_surat_kades = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat WHERE status='Verifikasi Sekdes'"))['total'];

// Data Keuangan
$in = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as t FROM apb_desa WHERE jenis='Pendapatan'"))['t'] ?? 0;
$out = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as t FROM apb_desa WHERE jenis='Belanja'"))['t'] ?? 0;


// --- LOGIC BENDAHARA (CRUD) ---
if ($role == 4) {
    if(isset($_POST['simpan_transaksi'])){
        $uraian = $_POST['uraian']; $jenis = $_POST['jenis']; $nominal= $_POST['nominal'];
        $tahun = date('Y'); $id_user= $_SESSION['user_id'];
        mysqli_query($conn, "INSERT INTO apb_desa (tahun, uraian, nominal, jenis, id_user) VALUES ('$tahun', '$uraian', '$nominal', '$jenis', '$id_user')");
        echo "<script>alert('Data Tersimpan!'); window.location='dashboard.php';</script>";
    }
    if(isset($_GET['hapus_keuangan'])){
        mysqli_query($conn, "DELETE FROM apb_desa WHERE id='$_GET[hapus_keuangan]'");
        header("Location: dashboard.php");
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/navbar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Halo, <?= $nama_user; ?>! 👋</h3>
            <p class="text-slate-500">Posisi: <span class="font-bold text-emerald-600">
                <?php 
                    if($role==1) echo "Super Developer";
                    elseif($role==2) echo "Bapak Kepala Desa";
                    elseif($role==3) echo "Sekretaris Desa";
                    elseif($role==4) echo "Bendahara Desa";
                ?>
            </span></p>
        </div>
        <?php if($role == 1): ?>
            <a href="https://cpanel.byethost.com" target="_blank" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-900 shadow-lg">🔧 Panel Hosting</a>
        <?php endif; ?>
    </div>

    <?php if($role == 1): $count_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total']; ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg relative overflow-hidden"><h4 class="text-4xl font-bold"><?= $count_user; ?></h4><p class="text-blue-100">Total Akun User</p><a href="kelola_user.php" class="absolute bottom-4 right-4 bg-blue-700 px-3 py-1 rounded text-xs hover:bg-blue-800">Kelola →</a></div>
        <div class="bg-emerald-600 text-white p-6 rounded-xl shadow-lg"><h4 class="text-4xl font-bold"><?= $count_penduduk; ?></h4><p class="text-emerald-100">Total Penduduk</p></div>
        <div class="bg-slate-700 text-white p-6 rounded-xl shadow-lg"><h4 class="text-2xl font-bold">Online</h4><p class="text-slate-300">Server Status</p></div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"><h3 class="font-bold mb-4 text-slate-700">Developer Tools</h3><div class="flex gap-4"><a href="reset_password_user.php" class="border border-red-200 bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 text-sm font-bold">⚠️ Reset Password User</a></div></div>
    <?php endif; ?>

    <?php if($role == 2): ?>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"><div class="flex justify-between items-start"><div><h4 class="text-3xl font-bold text-slate-800"><?= $count_penduduk; ?></h4><p class="text-sm text-slate-500">Total Warga</p></div><div class="p-2 bg-blue-50 rounded-lg text-blue-600">👥</div></div></div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"><div class="flex justify-between items-start"><div><h4 class="text-3xl font-bold text-slate-800"><?= $count_surat_kades; ?></h4><p class="text-sm text-slate-500">Menunggu Tanda Tangan</p></div><div class="p-2 bg-orange-50 rounded-lg text-orange-600"><?php if($count_surat_kades > 0): ?><span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-orange-400 opacity-75"></span><?php endif; ?>📝</div></div></div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"><div class="flex justify-between items-start"><div><h4 class="text-3xl font-bold text-emerald-600">Rp <?= number_format($in-$out, 0,',','.'); ?></h4><p class="text-sm text-slate-500">Sisa Anggaran Desa</p></div><div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">💰</div></div></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-slate-800 mb-4">📊 Realisasi APB Desa</h3>
            <div style="height: 250px;"><canvas id="kadesMoneyChart"></canvas></div>
        </div>
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-slate-800 mb-4">👥 Demografi</h3>
            <div style="height: 200px; display: flex; justify-content: center;"><canvas id="kadesPopChart"></canvas></div>
            <div class="mt-4 text-center text-xs text-gray-500">Laki-laki: <b><?= $lk; ?></b> | Perempuan: <b><?= $pr; ?></b></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-orange-200 mb-8 overflow-hidden">
        <div class="p-6 border-b border-orange-100 bg-orange-50">
            <h3 class="font-bold text-orange-800 text-lg flex items-center gap-2">✍️ Berkas Perlu Tanda Tangan</h3>
        </div>
        <?php $q_surat = mysqli_query($conn, "SELECT * FROM surat WHERE status='Verifikasi Sekdes' LIMIT 10"); if(mysqli_num_rows($q_surat) == 0): ?>
            <div class="p-8 text-center text-gray-400 italic">Tidak ada berkas di meja Bapak saat ini.</div>
        <?php else: ?>
            <div class="overflow-x-auto"><table class="w-full text-left text-sm text-slate-600"><thead class="bg-white border-b"><tr><th class="p-4">Pemohon</th><th class="p-4">Jenis Surat</th><th class="p-4">Tanggal Request</th><th class="p-4 text-right">Aksi</th></tr></thead><tbody><?php while($s = mysqli_fetch_assoc($q_surat)): ?><tr class="border-b hover:bg-orange-50 transition"><td class="p-4 font-bold text-slate-800"><?= $s['nama_pemohon']; ?><br><span class="text-xs font-normal text-gray-500"><?= $s['nik_pemohon']; ?></span></td><td class="p-4"><?= $s['jenis_surat']; ?></td><td class="p-4"><?= date('d M Y', strtotime($s['tanggal'])); ?></td><td class="p-4 text-right"><a href="approve_surat.php?id=<?= $s['id']; ?>&aksi=ttd" onclick="return confirm('Tanda tangani surat ini secara digital?')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 text-xs font-bold shadow-md inline-block">✍️ Tanda Tangani</a> <a href="approve_surat.php?id=<?= $s['id']; ?>&aksi=tolak" onclick="return confirm('Tolak surat ini?')" class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200 text-xs font-bold ml-2 inline-block">Tolak</a></td></tr><?php endwhile; ?></tbody></table></div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-slate-700">🗂️ Riwayat Surat Selesai / Ditolak</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-gray-50 uppercase font-semibold text-xs"><tr><th class="p-4">Tgl Proses</th><th class="p-4">Pemohon</th><th class="p-4">Jenis Surat</th><th class="p-4 text-right">Status Akhir</th></tr></thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $q_hist = mysqli_query($conn, "SELECT * FROM surat WHERE status IN ('Siap Diambil', 'Ditolak') ORDER BY tgl_approval DESC LIMIT 5"); while($h = mysqli_fetch_assoc($q_hist)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4"><?= ($h['tgl_approval']) ? date('d M Y', strtotime($h['tgl_approval'])) : '-'; ?></td>
                        <td class="p-4"><?= $h['nama_pemohon']; ?></td>
                        <td class="p-4"><?= $h['jenis_surat']; ?></td>
                        <td class="p-4 text-right"><?php if($h['status']=='Siap Diambil'): ?><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Disetujui ✅</span><?php else: ?><span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Ditolak ❌</span><?php endif; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('kadesMoneyChart'), {
            type: 'bar',
            data: { labels: ['Pendapatan', 'Belanja'], datasets: [{ label: 'Nominal (Rp)', data: [<?= $in; ?>, <?= $out; ?>], backgroundColor: ['#10b981', '#ef4444'], borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
        new Chart(document.getElementById('kadesPopChart'), {
            type: 'doughnut',
            data: { labels: ['Laki-laki', 'Perempuan'], datasets: [{ data: [<?= $lk; ?>, <?= $pr; ?>], backgroundColor: ['#3b82f6', '#ec4899'], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    </script>
    <?php endif; ?>

    <?php if($role == 3): $count_inventaris = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM inventaris"))['t']; ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="berita.php" class="bg-gradient-to-br from-purple-500 to-indigo-600 text-white p-6 rounded-xl shadow-lg hover:-translate-y-1 transition"><h4 class="font-bold text-lg mb-1">+ Tulis Berita</h4><p class="text-indigo-100 text-xs">Update info desa</p></a>
        <a href="data_penduduk.php" class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-6 rounded-xl shadow-lg hover:-translate-y-1 transition"><h4 class="font-bold text-lg mb-1">+ Input Warga</h4><p class="text-emerald-100 text-xs">Tambah data & akun</p></a>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"><h4 class="text-3xl font-bold text-slate-800"><?= $count_inventaris; ?> <span class="text-sm font-normal text-gray-500">Item</span></h4><p class="text-sm text-slate-500 mb-2">Inventaris Desa</p><a href="inventaris.php" class="text-emerald-600 text-xs font-bold hover:underline">Lihat Detail →</a></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center"><div><h3 class="font-bold text-slate-800 mb-1">Jadwal Ronda</h3><p class="text-sm text-slate-500">Atur jadwal jaga malam.</p></div><a href="jadwal_ronda.php" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-100">Atur Ronda</a></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center"><div><h3 class="font-bold text-slate-800 mb-1">Jadwal Posyandu</h3><p class="text-sm text-slate-500">Buka/Tutup pendaftaran.</p></div><a href="kelola_posyandu.php" class="bg-pink-50 text-pink-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-pink-100">Kelola Posyandu</a></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"><h3 class="font-bold text-slate-800 mb-2">Manajemen Staff</h3><p class="text-sm text-slate-500 mb-4">Kelola akun perangkat desa.</p><a href="kelola_user.php" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-200 block text-center">Buka Kelola User</a></div><div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"><h3 class="font-bold text-slate-800 mb-2">Administrasi Surat</h3><p class="text-sm text-slate-500 mb-4">Verifikasi permohonan warga.</p><a href="layanan_surat.php" class="bg-orange-50 text-orange-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-orange-100 block text-center">Cek Surat Masuk</a></div></div>
    <?php endif; ?>

    <?php if($role == 4): $sisa = $in - $out; ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-l-4 border-emerald-500"><p class="text-sm text-slate-500 mb-1">Total Pendapatan</p><h4 class="text-2xl font-bold text-emerald-600">Rp <?= number_format($in, 0,',','.'); ?></h4></div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-l-4 border-red-500"><p class="text-sm text-slate-500 mb-1">Total Belanja</p><h4 class="text-2xl font-bold text-red-600">Rp <?= number_format($out, 0,',','.'); ?></h4></div>
        <div class="bg-slate-800 text-white p-6 rounded-xl shadow-lg"><p class="text-slate-300 mb-1">Sisa Anggaran</p><h4 class="text-3xl font-bold">Rp <?= number_format($sisa, 0,',','.'); ?></h4></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit"><h3 class="font-bold text-slate-800 mb-4 text-lg">📥 Input Transaksi</h3><form method="POST" class="space-y-4"><div><label class="text-xs font-bold text-gray-500">Uraian Transaksi</label><input type="text" name="uraian" placeholder="Contoh: Dana Desa Tahap 1" required class="w-full border p-2 rounded mt-1"></div><div><label class="text-xs font-bold text-gray-500">Jenis</label><select name="jenis" class="w-full border p-2 rounded mt-1"><option value="Pendapatan">Pendapatan (+)</option><option value="Belanja">Belanja (-)</option></select></div><div><label class="text-xs font-bold text-gray-500">Nominal (Rp)</label><input type="number" name="nominal" placeholder="0" required class="w-full border p-2 rounded mt-1"></div><button type="submit" name="simpan_transaksi" class="w-full bg-emerald-600 text-white py-2 rounded font-bold hover:bg-emerald-700">Simpan Data</button></form></div>
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="p-6 border-b border-gray-100 flex justify-between items-center"><h3 class="font-bold text-slate-800">📋 Log Transaksi Terkini</h3><a href="apb_desa.php" class="text-xs text-blue-600 font-bold hover:underline">Lihat Grafik & Full →</a></div><div class="overflow-x-auto"><table class="w-full text-left text-sm text-slate-600"><thead class="bg-gray-50 uppercase font-semibold text-xs"><tr><th class="p-4">Uraian</th><th class="p-4">Jenis</th><th class="p-4 text-right">Nominal</th><th class="p-4 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50"><?php $q_apb = mysqli_query($conn, "SELECT * FROM apb_desa ORDER BY id DESC LIMIT 10"); while($d = mysqli_fetch_assoc($q_apb)): ?><tr class="hover:bg-gray-50"><td class="p-4 font-medium text-slate-800"><?= $d['uraian']; ?></td><td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold <?= $d['jenis']=='Pendapatan'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-700' ?>"><?= $d['jenis']; ?></span></td><td class="p-4 text-right font-mono">Rp <?= number_format($d['nominal'], 0,',','.'); ?></td><td class="p-4 text-right"><a href="?hapus_keuangan=<?= $d['id']; ?>" onclick="return confirm('Hapus transaksi ini?')" class="text-red-500 hover:text-red-700 font-bold text-xs border border-red-200 px-2 py-1 rounded hover:bg-red-50">Hapus</a></td></tr><?php endwhile; ?></tbody></table></div></div>
    </div>
    <?php endif; ?>

</main>
<?php include 'includes/footer.php'; ?>