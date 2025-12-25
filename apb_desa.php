<?php
session_start();
include 'includes/koneksi.php';

// Cek Login (Hanya Developer & Bendahara)
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 4) { header("Location: dashboard.php"); exit; }

// LOGIC: Hitung Total
$in = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as t FROM apb_desa WHERE jenis='Pendapatan'"))['t'] ?? 0;
$out = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as t FROM apb_desa WHERE jenis='Belanja'"))['t'] ?? 0;

include 'includes/header.php'; include 'includes/sidebar.php'; include 'includes/navbar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Laporan Keuangan Desa</h3>
        <a href="dashboard.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-300">Kembali ke Dashboard</a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <h4 class="font-bold text-slate-700 mb-4">Grafik Realisasi Anggaran</h4>
        <div style="height: 300px;">
            <canvas id="keuanganChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100"><h4 class="font-bold text-slate-700">Rincian Transaksi Lengkap</h4></div>
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-gray-50 uppercase font-semibold text-xs"><tr><th class="p-4">Tahun</th><th class="p-4">Uraian</th><th class="p-4">Jenis</th><th class="p-4 text-right">Nominal</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php $q = mysqli_query($conn, "SELECT * FROM apb_desa ORDER BY id DESC"); while($row = mysqli_fetch_assoc($q)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-4"><?= $row['tahun']; ?></td>
                    <td class="p-4 font-bold text-slate-800"><?= $row['uraian']; ?></td>
                    <td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold <?= $row['jenis']=='Pendapatan'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-700' ?>"><?= $row['jenis']; ?></span></td>
                    <td class="p-4 text-right font-mono">Rp <?= number_format($row['nominal'], 0,',','.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    const ctx = document.getElementById('keuanganChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendapatan (Masuk)', 'Belanja (Keluar)'],
            datasets: [{
                label: 'Total Rupiah',
                data: [<?= $in; ?>, <?= $out; ?>],
                backgroundColor: ['#10b981', '#ef4444'],
                borderRadius: 5
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
</script>
<?php include 'includes/footer.php'; ?>