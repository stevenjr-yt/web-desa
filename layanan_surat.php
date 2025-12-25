<?php
session_start();
include 'includes/koneksi.php';

// Cek Akses: Sekdes(3) & Dev(1)
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) { header("Location: dashboard.php"); exit; }

include 'includes/header.php'; include 'includes/sidebar.php'; include 'includes/navbar.php';
?>

<main class="p-6 bg-gray-50 flex-1 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Layanan Administrasi Surat</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-orange-200 mb-8">
        <h4 class="font-bold text-orange-600 mb-4 flex items-center gap-2">📩 Surat Masuk (Perlu Verifikasi)</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-orange-50 text-orange-800"><tr><th class="p-3">Tgl</th><th class="p-3">Pemohon</th><th class="p-3">Jenis Surat</th><th class="p-3">Keperluan</th><th class="p-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y">
                    <?php 
                    $q_pending = mysqli_query($conn, "SELECT * FROM surat WHERE status='Pending' ORDER BY id ASC");
                    if(mysqli_num_rows($q_pending) == 0) echo "<tr><td colspan='5' class='p-4 text-center italic text-gray-400'>Tidak ada surat baru.</td></tr>";
                    while($p = mysqli_fetch_assoc($q_pending)):
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-3"><?= $p['tanggal']; ?></td>
                        <td class="p-3 font-bold"><?= $p['nama_pemohon']; ?><br><span class="text-xs font-normal text-gray-500"><?= $p['nik_pemohon']; ?></span></td>
                        <td class="p-3"><?= $p['jenis_surat']; ?></td>
                        <td class="p-3 italic text-gray-500"><?= $p['keterangan']; ?></td>
                        <td class="p-3 text-right">
                            <a href="approve_surat.php?id=<?= $p['id']; ?>&aksi=verifikasi" onclick="return confirm('Teruskan ke Kades?')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Teruskan →</a>
                            <a href="approve_surat.php?id=<?= $p['id']; ?>&aksi=tolak" onclick="return confirm('Tolak surat ini?')" class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs hover:bg-red-200 ml-2">Tolak</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h4 class="font-bold text-slate-700 mb-4">🗄️ Log Arsip Surat</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-gray-100"><tr><th class="p-3">Tgl</th><th class="p-3">Pemohon</th><th class="p-3">Jenis</th><th class="p-3">Status Terakhir</th><th class="p-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y">
                    <?php 
                    // LOGIC HAPUS LOG
                    if(isset($_GET['hapus_log'])){
                        $id_log = $_GET['hapus_log'];
                        mysqli_query($conn, "DELETE FROM surat WHERE id='$id_log'");
                        echo "<script>window.location='layanan_surat.php';</script>";
                    }

                    $q_log = mysqli_query($conn, "SELECT * FROM surat WHERE status != 'Pending' ORDER BY id DESC LIMIT 20");
                    while($l = mysqli_fetch_assoc($q_log)):
                    ?>
                    <tr>
                        <td class="p-3"><?= $l['tanggal']; ?></td>
                        <td class="p-3"><?= $l['nama_pemohon']; ?></td>
                        <td class="p-3"><?= $l['jenis_surat']; ?></td>
                        <td class="p-3">
                            <?php 
                            if($l['status'] == 'Verifikasi Sekdes') echo '<span class="text-blue-600 font-bold text-xs">Menunggu TTD Kades</span>';
                            elseif($l['status'] == 'Siap Diambil') echo '<span class="text-green-600 font-bold text-xs">Selesai/Diambil</span>';
                            else echo '<span class="text-red-600 font-bold text-xs">Ditolak</span>';
                            ?>
                        </td>
                        <td class="p-3 text-right">
                            <a href="?hapus_log=<?= $l['id']; ?>" onclick="return confirm('Hapus arsip ini permanen?')" class="text-red-500 hover:text-red-700 text-xs font-bold border border-red-200 px-2 py-1 rounded hover:bg-red-50">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>