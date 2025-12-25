<?php
session_start();
include 'includes/koneksi.php';

// --- QUERIES DATA UMUM ---
$total_lk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE jk='L'"))['t'];
$total_pr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE jk='P'"))['t'];
$total_warga = $total_lk + $total_pr;

$apb_in = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as t FROM apb_desa WHERE jenis='Pendapatan'"))['t'] ?? 0;
$apb_out = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as t FROM apb_desa WHERE jenis='Belanja'"))['t'] ?? 0;

// --- QUERY STRUKTUR ORGANISASI (REAL DARI DB USERS) ---
// Role 2: Kades, 3: Sekdes, 4: Bendahara
$d_kades = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_lengkap FROM users WHERE role_level='2' LIMIT 1"));
$d_sekdes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_lengkap FROM users WHERE role_level='3' LIMIT 1"));
$d_bendahara = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_lengkap FROM users WHERE role_level='4' LIMIT 1"));

// Kalau kosong, isi default
$nama_kades = $d_kades['nama_lengkap'] ?? "Pejabat Belum Diisi";
$nama_sekdes = $d_sekdes['nama_lengkap'] ?? "Pejabat Belum Diisi";
$nama_bendahara = $d_bendahara['nama_lengkap'] ?? "Pejabat Belum Diisi";


// --- LOGIC FORM SUBMIT ---
if(isset($_POST['daftar_posyandu'])){
    if(!isset($_SESSION['user_login'])){ echo "<script>alert('Harap login!');</script>"; }
    else {
        $id_sesi = $_POST['id_sesi']; $ibu = $_POST['nama_ibu']; $balita = $_POST['nama_balita']; $usia = $_POST['usia'];
        mysqli_query($conn, "INSERT INTO posyandu_pendaftaran (id_sesi, nama_ibu, nama_balita, usia_balita) VALUES ('$id_sesi', '$ibu', '$balita', '$usia')");
        echo "<script>alert('Pendaftaran Berhasil!'); window.location='index.php#layanan';</script>";
    }
}

if(isset($_POST['request_surat'])){
    if(!isset($_SESSION['user_login'])){ echo "<script>alert('Harap login!');</script>"; }
    else {
        $nik = $_POST['nik']; $nama = $_POST['nama']; $jenis = $_POST['jenis_surat']; $ket = $_POST['keterangan'];
        $q = "INSERT INTO surat (nama_pemohon, nik_pemohon, jenis_surat, keterangan, tanggal, status) VALUES ('$nama', '$nik', '$jenis', '$ket', CURDATE(), 'Pending')";
        if(mysqli_query($conn, $q)){
            echo "<script>alert('Surat Berhasil Diajukan!'); window.location='index.php#layanan';</script>";
        } else {
            echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Desa Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-slate-800">

    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <h1 class="text-2xl font-bold text-emerald-600 tracking-wider">DESA<span class="text-slate-700">KU</span></h1>
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="#stats" class="hover:text-emerald-600 transition">Statistik</a>
                    <a href="#apb" class="hover:text-emerald-600 transition">Transparansi</a>
                    <a href="#layanan" class="hover:text-emerald-600 transition">Layanan</a>
                    <a href="#struktur" class="hover:text-emerald-600 transition">Struktur</a>
                    <a href="#ronda" class="hover:text-emerald-600 transition">Jadwal Ronda</a>
                    
                    <?php if(isset($_SESSION['user_login'])): ?>
                        <div class="flex items-center gap-3 pl-4 border-l">
                            <span class="text-emerald-700 font-bold">Hi, <?= $_SESSION['user_name']; ?></span>
                            <a href="logout.php" class="text-xs bg-red-50 text-red-600 px-3 py-1 rounded-full font-bold hover:bg-red-100 border border-red-200">Logout</a>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="bg-emerald-600 text-white px-5 py-2 rounded-full hover:bg-emerald-700 transition shadow-md">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-emerald-600 text-white py-24 text-center px-4 relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Sistem Informasi Desa Terpadu</h1>
            <p class="text-emerald-100 max-w-xl mx-auto text-lg">Melayani masyarakat dengan transparansi data, kemudahan administrasi, dan pelayanan kesehatan digital.</p>
        </div>
    </div>

    <div id="stats" class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 mb-4">Demografi Penduduk</h2>
                <p class="text-slate-600 mb-6">Data kependudukan terkini yang terdata dalam sistem database desa.</p>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 text-center">
                    <h3 class="text-5xl font-bold text-emerald-600 mb-2"><?= number_format($total_warga); ?></h3>
                    <p class="text-gray-500 uppercase tracking-widest text-sm font-semibold">Total Jiwa</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 h-64">
                <canvas id="chartPenduduk"></canvas> 
            </div>
        </div>
    </div>

    <div id="apb" class="bg-slate-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8">Transparansi Anggaran Desa 2025</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-700 p-8 rounded-2xl relative overflow-hidden"><p class="text-emerald-400 font-semibold mb-2">Total Pendapatan</p><h3 class="text-3xl font-bold">Rp <?= number_format($apb_in, 0, ',', '.'); ?></h3></div>
                <div class="bg-slate-700 p-8 rounded-2xl relative overflow-hidden"><p class="text-red-400 font-semibold mb-2">Total Belanja</p><h3 class="text-3xl font-bold">Rp <?= number_format($apb_out, 0, ',', '.'); ?></h3></div>
                <div class="bg-emerald-600 p-8 rounded-2xl relative overflow-hidden"><p class="text-emerald-100 font-semibold mb-2">Sisa Anggaran</p><h3 class="text-3xl font-bold">Rp <?= number_format($apb_in - $apb_out, 0, ',', '.'); ?></h3></div>
            </div>
        </div>
    </div>

    <div id="layanan" class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-10"><h2 class="text-3xl font-bold text-slate-800">Layanan Mandiri</h2><p class="text-slate-500 mt-2">Pilih layanan yang Anda butuhkan tanpa harus antri.</p></div>
        <div class="flex justify-center mb-8"><div class="bg-gray-200 p-1 rounded-lg inline-flex"><button onclick="switchTab('posyandu')" id="btn-posyandu" class="px-6 py-2 rounded-md font-medium text-sm transition bg-white shadow text-emerald-600">❤️ Pendaftaran Posyandu</button><button onclick="switchTab('surat')" id="btn-surat" class="px-6 py-2 rounded-md font-medium text-sm transition text-gray-500 hover:text-gray-700">📄 Request Surat</button></div></div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden min-h-[400px]">
            <div id="tab-posyandu" class="p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/2">
                        <h3 class="text-xl font-bold text-emerald-600 mb-4">Pendaftaran Online Posyandu</h3>
                        <p class="text-gray-600 mb-4 text-sm">Pendaftaran dibuka sesuai jadwal. Silakan pilih sesi yang statusnya <span class="font-bold text-emerald-600">"BUKA"</span>.</p>
                        <div class="bg-blue-50 p-4 rounded-lg text-sm text-blue-800"><strong>Info:</strong> Setelah mendaftar, silakan datang sesuai jam yang dipilih.</div>
                    </div>
                    <div class="md:w-1/2 bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <?php if(!isset($_SESSION['user_login'])): ?>
                            <div class="text-center py-6"><p class="mb-4 text-gray-600 font-bold">Silakan Login Warga untuk Mendaftar</p><a href="login.php" class="bg-emerald-600 text-white px-4 py-2 rounded">Login Disini</a></div>
                        <?php else: ?>
                            <form method="POST">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Jadwal</label>
                                <select name="id_sesi" class="w-full p-2 border rounded mb-3" required><option value="">-- Pilih Jadwal Aktif --</option><?php $q_sesi = mysqli_query($conn, "SELECT * FROM posyandu_sesi WHERE status='Buka'"); while($s=mysqli_fetch_assoc($q_sesi)): ?><option value="<?= $s['id']; ?>"><?= $s['nama_kegiatan']; ?> (<?= $s['tanggal']; ?>)</option><?php endwhile; ?></select>
                                <input type="text" name="nama_ibu" placeholder="Nama Ibu" class="w-full p-2 border rounded mb-3" required>
                                <input type="text" name="nama_balita" placeholder="Nama Balita" class="w-full p-2 border rounded mb-3" required>
                                <input type="number" name="usia" placeholder="Usia (Bulan)" class="w-full p-2 border rounded mb-4" required>
                                <button type="submit" name="daftar_posyandu" class="w-full bg-emerald-600 text-white font-bold py-2 rounded hover:bg-emerald-700">Daftar Sekarang</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div id="tab-surat" class="p-8 hidden">
                <?php if(!isset($_SESSION['user_login'])): ?>
                    <div class="text-center py-10"><h3 class="text-xl font-bold text-gray-700">Layanan Persuratan Online</h3><p class="text-gray-500 mb-6">Silakan login atau hubungi admin desa.</p><a href="login.php" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Login Warga</a></div>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <h3 class="font-bold text-emerald-600 mb-4 text-center">Form Pengajuan Surat</h3>
                            <form method="POST">
                                <div class="mb-2"><label class="text-xs font-bold text-gray-500">NIK (Otomatis)</label><input type="text" name="nik" value="<?= $_SESSION['user_nik']; ?>" readonly class="w-full p-2 border rounded bg-gray-200"></div>
                                <div class="mb-2"><label class="text-xs font-bold text-gray-500">Nama (Otomatis)</label><input type="text" name="nama" value="<?= $_SESSION['user_name']; ?>" readonly class="w-full p-2 border rounded bg-gray-200"></div>
                                <div class="mb-2"><label class="text-xs font-bold text-gray-500">Jenis Surat</label><select name="jenis_surat" class="w-full p-2 border rounded"><option>Surat Keterangan Usaha</option><option>Surat Domisili</option><option>Surat Pengantar SKCK</option><option>Surat Keterangan Tidak Mampu</option></select></div>
                                <div class="mb-4"><label class="text-xs font-bold text-gray-500">Keperluan</label><textarea name="keterangan" class="w-full p-2 border rounded" required></textarea></div>
                                <button type="submit" name="request_surat" class="w-full bg-emerald-600 text-white font-bold py-2 rounded">Ajukan Surat</button>
                            </form>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">📂 Riwayat Permohonan Anda</h3>
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                <table class="w-full text-sm text-left"><thead class="bg-gray-100 text-gray-600 font-bold border-b"><tr><th class="p-3">Tgl</th><th class="p-3">Jenis</th><th class="p-3 text-right">Status</th></tr></thead><tbody class="divide-y"><?php $nik_saya = $_SESSION['user_nik']; $q_hist = mysqli_query($conn, "SELECT * FROM surat WHERE nik_pemohon='$nik_saya' ORDER BY id DESC LIMIT 5"); if(mysqli_num_rows($q_hist) == 0): ?><tr><td colspan="3" class="p-4 text-center text-gray-400 italic">Belum ada riwayat surat.</td></tr><?php else: while($h = mysqli_fetch_assoc($q_hist)): ?><tr><td class="p-3 text-xs text-gray-500"><?= date('d/m/y', strtotime($h['tanggal'])); ?></td><td class="p-3 font-medium"><?= $h['jenis_surat']; ?></td><td class="p-3 text-right"><?php if($h['status'] == 'Pending') echo '<span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">Menunggu</span>'; elseif($h['status'] == 'Verifikasi Sekdes') echo '<span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs font-bold">Verifikasi</span>'; elseif($h['status'] == 'Siap Diambil') echo '<span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs font-bold">Siap Diambil ✅</span>'; else echo '<span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-bold">Ditolak</span>'; ?></td></tr><?php endwhile; endif; ?></tbody></table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="struktur" class="bg-slate-900 py-16 text-white border-t border-slate-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-emerald-400">Struktur Pemerintahan Desa</h2>
                <p class="text-slate-400 mt-2">Sinergi membangun desa yang lebih maju.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 hover:border-emerald-500 transition">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_kades); ?>&background=10b981&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-slate-600">
                    <h4 class="font-bold text-lg"><?= $nama_kades; ?></h4>
                    <p class="text-emerald-400 text-sm uppercase tracking-wide font-semibold mt-1">Kepala Desa</p>
                </div>
                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 hover:border-emerald-500 transition">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_sekdes); ?>&background=3b82f6&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-slate-600">
                    <h4 class="font-bold text-lg"><?= $nama_sekdes; ?></h4>
                    <p class="text-blue-400 text-sm uppercase tracking-wide font-semibold mt-1">Sekretaris Desa</p>
                </div>
                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 hover:border-emerald-500 transition">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_bendahara); ?>&background=f59e0b&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-slate-600">
                    <h4 class="font-bold text-lg"><?= $nama_bendahara; ?></h4>
                    <p class="text-yellow-400 text-sm uppercase tracking-wide font-semibold mt-1">Bendahara Desa</p>
                </div>
                <div class="bg-gradient-to-b from-purple-900 to-slate-800 p-6 rounded-2xl border border-purple-500 shadow-lg shadow-purple-900/20 transform hover:-translate-y-2 transition">
                    <div class="relative w-24 h-24 mx-auto mb-4">
                        <img src="https://ui-avatars.com/api/?name=Steven+Erlinto&background=8b5cf6&color=fff&size=128" class="w-full h-full rounded-full border-4 border-purple-400">
                        <span class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-slate-800"></span>
                    </div>
                    <h4 class="font-bold text-lg text-white">Steven Erlinto</h4>
                    <p class="text-purple-300 text-sm uppercase tracking-wide font-bold mt-1">ICT Desa</p>
                    <span class="inline-block mt-3 px-3 py-1 bg-purple-900/50 text-purple-200 text-xs rounded-full border border-purple-700">System Developer</span>
                </div>
            </div>
        </div>
    </div>

    <div id="ronda" class="max-w-7xl mx-auto px-4 py-16 border-t border-gray-200">
        <div class="grid md:grid-cols-3 gap-12">
            <div class="md:col-span-1">
                <h3 class="text-2xl font-bold mb-6 text-slate-800">Jadwal Ronda Malam Ini</h3>
                <div class="bg-white shadow rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-emerald-600 text-white"><tr><th class="p-3">Hari</th><th class="p-3">Lokasi</th></tr></thead>
                        <tbody>
                            <?php $hari_ini = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")[date('w')];
                            $q_ronda = mysqli_query($conn, "SELECT * FROM jadwal WHERE jenis='Ronda' LIMIT 5");
                            while($r=mysqli_fetch_assoc($q_ronda)): $highlight = ($r['hari'] == $hari_ini) ? "bg-emerald-50 font-bold text-emerald-700" : ""; ?>
                            <tr class="border-b <?= $highlight ?>"><td class="p-3"><?= $r['hari']; ?></td><td class="p-3"><?= $r['kegiatan']; ?> <span class="text-xs text-gray-400 block"><?= $r['jam']; ?></span></td></tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-2xl font-bold mb-6 text-slate-800">Kabar Desa</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php $q_news = mysqli_query($conn, "SELECT * FROM berita ORDER BY id DESC LIMIT 4"); while($n=mysqli_fetch_assoc($q_news)): ?>
                    <div class="flex gap-4 items-start bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition">
                        <?php if(!empty($n['gambar']) && file_exists('uploads/'.$n['gambar'])): ?><img src="uploads/<?= $n['gambar']; ?>" class="w-24 h-24 object-cover rounded-lg bg-gray-200"><?php else: ?><div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-400">No Image</div><?php endif; ?>
                        <div><span class="text-xs font-bold text-emerald-600 uppercase"><?= $n['kategori']; ?></span><h4 class="font-bold text-slate-800 leading-tight mt-1 mb-2"><?= $n['judul']; ?></h4><a href="#" class="text-sm text-gray-500 hover:text-emerald-600">Baca selengkapnya...</a></div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t py-8 text-center text-gray-500 text-sm mt-12">&copy; 2025 Pemerintah Desa Digital.</footer>

    <script>
        const ctx = document.getElementById('chartPenduduk').getContext('2d');
        new Chart(ctx, { type: 'doughnut', data: { labels: ['Laki-laki', 'Perempuan'], datasets: [{ data: [<?= $total_lk; ?>, <?= $total_pr; ?>], backgroundColor: ['#3b82f6', '#ec4899'], hoverOffset: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } } });
        function switchTab(tabName) {
            document.getElementById('tab-posyandu').classList.add('hidden'); document.getElementById('tab-surat').classList.add('hidden');
            document.getElementById('btn-posyandu').className = "px-6 py-2 rounded-md font-medium text-sm transition text-gray-500 hover:text-gray-700";
            document.getElementById('btn-surat').className = "px-6 py-2 rounded-md font-medium text-sm transition text-gray-500 hover:text-gray-700";
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            document.getElementById('btn-' + tabName).className = "px-6 py-2 rounded-md font-medium text-sm transition bg-white shadow text-emerald-600";
        }
    </script>
</body>
</html>