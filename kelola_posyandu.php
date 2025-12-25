<?php
session_start();
include 'includes/koneksi.php';

// Cek Akses Sekdes (3) & Dev (1)
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) { header("Location: dashboard"); exit; }

// LOGIC: Tambah Sesi Baru
if (isset($_POST['tambah_sesi'])) {
    $nama = $_POST['nama'];
    $tgl  = $_POST['tanggal'];
    $jam1 = $_POST['jam_mulai'];
    $jam2 = $_POST['jam_selesai'];
    $kuota= $_POST['kuota'];
    mysqli_query($conn, "INSERT INTO posyandu_sesi (nama_kegiatan, tanggal, jam_mulai, jam_selesai, status, kuota_maksimal) VALUES ('$nama', '$tgl', '$jam1', '$jam2', 'Buka', '$kuota')");
    header("Location: kelola_posyandu.php");
}

// LOGIC: Toggle Status (Buka/Tutup)
if (isset($_GET['toggle_id'])) {
    $id = $_GET['toggle_id'];
    $status_sekarang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM posyandu_sesi WHERE id='$id'"))['status'];
    $status_baru = ($status_sekarang == 'Buka') ? 'Tutup' : 'Buka';
    mysqli_query($conn, "UPDATE posyandu_sesi SET status='$status_baru' WHERE id='$id'");
    header("Location: kelola_posyandu.php");
}

// LOGIC: Approve Pendaftaran
if (isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    mysqli_query($conn, "UPDATE posyandu_pendaftaran SET status_approval='Disetujui' WHERE id='$id'");
    header("Location: kelola_posyandu.php");
}

include 'includes/header.php'; include 'includes/sidebar.php'; include 'includes/navbar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                <h3 class="font-bold text-emerald-600 mb-4">Buat Jadwal Posyandu Baru</h3>
                <form method="POST" class="space-y-3">
                    <input type="text" name="nama" placeholder="Nama Kegiatan (ex: Imunisasi Polio)" required class="w-full border rounded p-2">
                    <div class="flex gap-2">
                        <input type="date" name="tanggal" required class="w-full border rounded p-2">
                        <input type="number" name="kuota" placeholder="Kuota" required class="w-24 border rounded p-2">
                    </div>
                    <div class="flex gap-2">
                        <input type="time" name="jam_mulai" required class="w-full border rounded p-2">
                        <input type="time" name="jam_selesai" required class="w-full border rounded p-2">
                    </div>
                    <button type="submit" name="tambah_sesi" class="w-full bg-emerald-600 text-white py-2 rounded font-bold">Buka Pendaftaran</button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <h4 class="p-4 font-bold border-b bg-gray-50">Daftar Sesi Pelayanan</h4>
                <table class="w-full text-sm text-left">
                    <thead><tr class="border-b"><th class="p-3">Kegiatan</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead>
                    <tbody>
                        <?php $q = mysqli_query($conn, "SELECT * FROM posyandu_sesi ORDER BY id DESC"); while($r=mysqli_fetch_assoc($q)): ?>
                        <tr class="border-b">
                            <td class="p-3">
                                <b><?= $r['nama_kegiatan']; ?></b><br>
                                <span class="text-xs text-gray-500"><?= $r['tanggal']; ?> | <?= $r['jam_mulai']; ?>-<?= $r['jam_selesai']; ?></span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-bold <?= $r['status']=='Buka'?'bg-green-100 text-green-600':'bg-red-100 text-red-600' ?>">
                                    <?= $r['status']; ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <a href="?toggle_id=<?= $r['id']; ?>" class="text-blue-600 font-bold text-xs hover:underline">
                                    <?= ($r['status']=='Buka') ? 'TUTUP' : 'BUKA'; ?>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 h-fit">
            <h4 class="p-4 font-bold border-b bg-gray-50 flex justify-between">
                <span>Pendaftar Masuk</span>
                <span class="bg-red-100 text-red-600 px-2 rounded-full text-xs flex items-center">Perlu Approval</span>
            </h4>
            <table class="w-full text-sm text-left">
                <thead><tr class="border-b"><th class="p-3">Ibu & Balita</th><th class="p-3">Sesi</th><th class="p-3 text-right">Aksi</th></tr></thead>
                <tbody>
                    <?php 
                    $q2 = mysqli_query($conn, "SELECT p.*, s.nama_kegiatan FROM posyandu_pendaftaran p JOIN posyandu_sesi s ON p.id_sesi = s.id WHERE p.status_approval='Menunggu' ORDER BY p.id DESC"); 
                    if(mysqli_num_rows($q2) == 0) echo "<tr><td colspan='3' class='p-4 text-center text-gray-400'>Belum ada pendaftar baru</td></tr>";
                    while($p=mysqli_fetch_assoc($q2)): 
                    ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">
                            <div class="font-bold"><?= $p['nama_ibu']; ?></div>
                            <div class="text-xs text-gray-500">Balita: <?= $p['nama_balita']; ?> (<?= $p['usia_balita']; ?> bln)</div>
                        </td>
                        <td class="p-3 text-xs"><?= substr($p['nama_kegiatan'], 0, 15); ?>...</td>
                        <td class="p-3 text-right">
                            <a href="?approve_id=<?= $p['id']; ?>" class="bg-emerald-600 text-white px-3 py-1 rounded text-xs hover:bg-emerald-700">Approve</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>
<?php include 'includes/footer.php'; ?>