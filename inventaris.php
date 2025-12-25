<?php
session_start();
include 'includes/koneksi.php';

// Cek Login
if (!isset($_SESSION['user_login'])) { header("Location: login.php"); exit; }

// LOGIC TAMBAH BARANG
if (isset($_POST['tambah_barang'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $jumlah  = $_POST['jumlah'];
    $kondisi = $_POST['kondisi'];
    $tgl     = $_POST['tanggal'];
    $id_user = $_SESSION['user_id']; // Yang input (Sekdes)

    $q = "INSERT INTO inventaris (nama_barang, jumlah, kondisi, tgl_pengadaan, id_user) 
          VALUES ('$nama', '$jumlah', '$kondisi', '$tgl', '$id_user')";
          
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Barang Berhasil Ditambahkan!'); window.location='inventaris.php';</script>";
    } else {
        echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
    }
}

// LOGIC HAPUS BARANG
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM inventaris WHERE id='$id'");
    header("Location: inventaris.php");
}

include 'includes/header.php'; include 'includes/sidebar.php'; include 'includes/navbar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Inventaris Desa</h3>
        <button onclick="document.getElementById('formInventaris').classList.toggle('hidden')" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 shadow-lg transition">
            + Tambah Aset
        </button>
    </div>

    <div id="formInventaris" class="hidden bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all">
        <h4 class="font-bold text-purple-600 mb-4">Input Aset Baru</h4>
        <form method="POST" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-gray-500">Nama Barang</label>
                <input type="text" name="nama" class="w-full border p-2 rounded-lg mt-1" required placeholder="Contoh: Laptop Acer">
            </div>
            <div class="w-24">
                <label class="text-xs font-semibold text-gray-500">Jumlah</label>
                <input type="number" name="jumlah" class="w-full border p-2 rounded-lg mt-1" required>
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-gray-500">Kondisi</label>
                <select name="kondisi" class="w-full border p-2 rounded-lg mt-1">
                    <option>Baik</option>
                    <option>Rusak Ringan</option>
                    <option>Rusak Berat</option>
                </select>
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-gray-500">Tgl Pengadaan</label>
                <input type="date" name="tanggal" class="w-full border p-2 rounded-lg mt-1" required>
            </div>
            <button type="submit" name="tambah_barang" class="bg-purple-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-purple-700 transition h-[42px]">Simpan</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-gray-50 uppercase font-semibold text-xs">
                <tr>
                    <th class="p-4">Nama Barang</th>
                    <th class="p-4">Jumlah</th>
                    <th class="p-4">Kondisi</th>
                    <th class="p-4">Tgl Pengadaan</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php 
                $q = mysqli_query($conn, "SELECT * FROM inventaris ORDER BY id DESC"); 
                while($r=mysqli_fetch_assoc($q)): 
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-slate-800"><?= $r['nama_barang']; ?></td>
                    <td class="p-4"><?= $r['jumlah']; ?> Unit</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $r['kondisi']=='Baik'?'bg-green-100 text-green-700': ($r['kondisi']=='Rusak Ringan'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') ?>">
                            <?= $r['kondisi']; ?>
                        </span>
                    </td>
                    <td class="p-4"><?= date('d/m/Y', strtotime($r['tgl_pengadaan'])); ?></td>
                    <td class="p-4 text-right">
                        <a href="?hapus=<?= $r['id']; ?>" onclick="return confirm('Hapus aset ini?')" class="text-red-500 font-bold hover:underline">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include 'includes/footer.php'; ?>