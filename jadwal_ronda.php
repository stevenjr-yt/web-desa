<?php
session_start();
include 'includes/koneksi.php';

// Cek Akses: Hanya Developer (1) dan Sekdes (3)
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: dashboard.php");
    exit;
}

// LOGIC: Tambah Jadwal
if (isset($_POST['simpan_jadwal'])) {
    $kegiatan = $_POST['kegiatan']; // Misal: "Ronda RT 01"
    $jenis    = 'Ronda';            // Default Ronda
    $hari     = $_POST['hari'];
    $jam      = $_POST['jam'];
    $petugas  = $_POST['petugas_manual'];
    
    // (Opsional: Kalau mau pakai relasi PJ, ambil id_pj. Kalau simple text, abaikan relasi dulu)
    // Disini kita pakai Text Manual Petugas agar fleksibel sesuai permintaan "Atur Ronda"
    
    $q = "INSERT INTO jadwal (kegiatan, hari, jam, petugas, jenis) 
          VALUES ('$kegiatan', '$hari', '$jam', '$petugas', '$jenis')";
    
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Jadwal Berhasil Disimpan!'); window.location='jadwal_ronda.php';</script>";
    } else {
        echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
    }
}

// LOGIC: Hapus Jadwal
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM jadwal WHERE id='$id'");
    header("Location: jadwal_ronda.php");
}

include 'includes/header.php'; include 'includes/sidebar.php'; include 'includes/navbar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Manajemen Jadwal Ronda</h3>
        <button onclick="document.getElementById('formJadwal').classList.toggle('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-lg transition">
            + Tambah Jadwal
        </button>
    </div>

    <div id="formJadwal" class="hidden bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8 transition-all">
        <h4 class="font-bold text-lg mb-4 text-blue-600">Form Jadwal Siskamling</h4>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium text-gray-700">Lokasi / Pos</label>
                <input type="text" name="kegiatan" placeholder="Contoh: Pos Kamling RT 05" required class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Hari</label>
                <select name="hari" class="w-full border rounded-lg px-3 py-2 mt-1">
                    <option>Senin</option><option>Selasa</option><option>Rabu</option>
                    <option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Jaga</label>
                <input type="text" name="jam" placeholder="Contoh: 21:00 - 04:00" required class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Daftar Petugas (Warga)</label>
                <textarea name="petugas_manual" placeholder="Tulis nama warga yang bertugas, pisahkan dengan koma..." class="w-full border rounded-lg px-3 py-2 mt-1" rows="2"></textarea>
            </div>
            <div class="col-span-2">
                <button type="submit" name="simpan_jadwal" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 w-full md:w-auto">Simpan Jadwal</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-gray-50 uppercase font-semibold text-xs">
                <tr>
                    <th class="px-6 py-3">Hari & Jam</th>
                    <th class="px-6 py-3">Lokasi</th>
                    <th class="px-6 py-3">Petugas</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $q = mysqli_query($conn, "SELECT * FROM jadwal WHERE jenis='Ronda' ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($q)):
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800"><?= $row['hari']; ?></span><br>
                        <span class="text-xs"><?= $row['jam']; ?></span>
                    </td>
                    <td class="px-6 py-4"><?= $row['kegiatan']; ?></td>
                    <td class="px-6 py-4 text-xs max-w-xs truncate" title="<?= $row['petugas']; ?>">
                        <?= $row['petugas']; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Hapus jadwal ini?')" class="text-red-600 hover:text-red-900 font-bold hover:underline">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include 'includes/footer.php'; ?>