<?php
session_start();
include 'includes/koneksi.php';

// Cek Login & Role (Dev/Sekdes)
if (!isset($_SESSION['user_login']) || ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3)) {
    header("Location: dashboard");
    exit;
}

// LOGIC: Tambah Berita
if (isset($_POST['simpan_berita'])) {
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi      = mysqli_real_escape_string($conn, $_POST['isi']);
    $kategori = $_POST['kategori'];
    $slug     = strtolower(str_replace(' ', '-', $judul));
    
    // AMBIL ID DARI SESSION (Sekarang sudah aman karena login.php diperbaiki)
    $id_user  = $_SESSION['user_id']; 

    // Logic Upload Gambar
    $gambar = "default.jpg";
    if (!empty($_FILES['gambar']['name'])) {
        // Cek folder uploads, kalau ga ada, kita buat!
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $nama_file = time() . '_' . $_FILES['gambar']['name'];
        $tmp_file  = $_FILES['gambar']['tmp_name'];
        
        // Pindahkan file
        if(move_uploaded_file($tmp_file, 'uploads/' . $nama_file)){
            $gambar = $nama_file;
        } else {
            echo "<script>alert('Gagal upload gambar! Cek permission folder.');</script>";
        }
    }

    // Query INSERT
    $q = "INSERT INTO berita (judul, slug, isi, kategori, gambar, tanggal, id_user) 
          VALUES ('$judul', '$slug', '$isi', '$kategori', '$gambar', NOW(), '$id_user')";
    
    if(mysqli_query($conn, $q)) {
        echo "<script>alert('Berita Berhasil Terbit!'); window.location='berita.php';</script>";
    } else {
        // Tampilkan error SQL kalau ada masalah
        echo "<script>alert('Database Error: ".mysqli_error($conn)."');</script>";
    }
}

// LOGIC: Hapus Berita
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Hapus gambar lama (opsional, biar hemat space)
    $data_lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM berita WHERE id='$id'"));
    if($data_lama['gambar'] != 'default.jpg' && file_exists('uploads/'.$data_lama['gambar'])){
        unlink('uploads/'.$data_lama['gambar']);
    }
    
    mysqli_query($conn, "DELETE FROM berita WHERE id='$id'");
    header("Location: berita.php");
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/navbar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Kelola Berita</h3>
        <button onclick="document.getElementById('formBerita').classList.toggle('hidden')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 shadow-lg">
            + Tulis Berita
        </button>
    </div>

    <div id="formBerita" class="hidden bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Judul Berita</label>
                <input type="text" name="judul" required class="w-full border rounded px-3 py-2 mt-1">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Kategori</label>
                    <select name="kategori" class="w-full border rounded px-3 py-2 mt-1">
                        <option>Kegiatan</option><option>Pembangunan</option><option>Pengumuman</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Thumbnail</label>
                    <input type="file" name="gambar" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Isi Berita</label>
                <textarea name="isi" rows="5" required class="w-full border rounded px-3 py-2 mt-1"></textarea>
            </div>
            <button type="submit" name="simpan_berita" class="w-full bg-emerald-600 text-white py-2 rounded font-bold">Terbitkan</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-gray-100 uppercase font-bold text-xs">
                <tr>
                    <th class="p-4">Tgl</th>
                    <th class="p-4">Judul</th>
                    <th class="p-4">Gambar</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT * FROM berita ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($q)):
                ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4"><?= date('d/m/y', strtotime($row['tanggal'])); ?></td>
                    <td class="p-4 font-bold text-slate-800"><?= $row['judul']; ?></td>
                    <td class="p-4">
                        <img src="uploads/<?= $row['gambar']; ?>" class="h-10 w-16 object-cover rounded">
                    </td>
                    <td class="p-4 text-right">
                        <a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Hapus?')" class="text-red-600 font-bold">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include 'includes/footer.php'; ?>