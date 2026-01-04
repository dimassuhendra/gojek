-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 04:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kepegawaian`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('Hadir','Alpa','Izin/Cuti') DEFAULT 'Hadir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_pegawai`, `tanggal`, `jam_masuk`, `jam_keluar`, `status`) VALUES
(1, 11, '2026-01-04', '07:00:00', '18:14:33', 'Hadir'),
(2, 2, '2026-01-04', '18:14:49', '18:15:16', 'Hadir'),
(3, 5, '2026-01-04', NULL, NULL, 'Alpa'),
(4, 3, '2026-01-04', '18:18:43', NULL, 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `cuti_izin`
--

CREATE TABLE `cuti_izin` (
  `id_cuti` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `jenis` enum('Cuti','Izin','Sakit') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status_pengajuan` enum('Pending','Disetujui','Ditolak') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuti_izin`
--

INSERT INTO `cuti_izin` (`id_cuti`, `id_pegawai`, `tgl_mulai`, `tgl_selesai`, `jenis`, `keterangan`, `status_pengajuan`) VALUES
(1, 2, '2024-01-10', '2024-01-12', 'Cuti', 'Acara Keluarga', 'Disetujui'),
(2, 4, '2024-01-15', '2024-01-15', 'Sakit', 'Demam Tinggi', 'Disetujui'),
(3, 5, '2024-02-01', '2024-02-03', 'Izin', 'Urusan Pernikahan', 'Pending'),
(4, 8, '2024-02-10', '2024-02-12', 'Cuti', 'Liburan', 'Ditolak'),
(5, 11, '2026-01-05', '2026-01-06', 'Cuti', 'Nikahan keluarga', 'Disetujui');

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `id_gaji` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `total_gaji` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gaji`
--

INSERT INTO `gaji` (`id_gaji`, `id_pegawai`, `bulan`, `tahun`, `total_gaji`, `tgl_bayar`) VALUES
(1, 1, 'Januari', '2024', 13500000, '2024-01-31'),
(2, 2, 'Januari', '2024', 9000000, '2024-01-31'),
(3, 3, 'Januari', '2024', 9000000, '2024-01-31'),
(4, 4, 'Januari', '2024', 5500000, '2024-01-31'),
(5, 5, 'Januari', '2024', 6700000, '2024-01-31'),
(6, 6, 'Januari', '2024', 9000000, '2024-01-31'),
(7, 7, 'Januari', '2024', 5000000, '2024-01-31'),
(8, 8, 'Januari', '2024', 6700000, '2024-01-31'),
(9, 9, 'Januari', '2024', 5500000, '2024-01-31'),
(10, 10, 'Januari', '2024', 5000000, '2024-01-31'),
(11, 11, 'Januari', '2026', 5000000, '2026-01-04');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL,
  `gaji_pokok` int(11) NOT NULL,
  `tunjangan_makan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `gaji_pokok`, `tunjangan_makan`) VALUES
(1, 'Manager IT', 12000000, 1500000),
(2, 'Software Engineer', 8000000, 1000000),
(3, 'Staff HRD', 5000000, 500000),
(4, 'Marketing', 6000000, 700000),
(5, 'Admin Gudang', 4500000, 500000);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `id_jabatan` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nip`, `nama`, `alamat`, `telepon`, `id_jabatan`, `password`) VALUES
(1, '2024001', 'Andi Wijaya', 'Jl. Merdeka No. 1', '081234567890', 1, 'password123'),
(2, '2024002', 'Budi Santoso', 'Jl. Mawar No. 12', '081234567891', 2, 'password123'),
(3, '2024003', 'Citra Lestari', 'Jl. Melati No. 5', '081234567892', 2, 'password123'),
(4, '2024004', 'Dedi Kurniawan', 'Jl. Anggrek No. 8', '081234567893', 3, 'password123'),
(5, '2024005', 'Eka Putri', 'Jl. Dahlia No. 3', '081234567894', 4, 'password123'),
(6, '2024006', 'Fajar Ramadhan', 'Jl. Kamboja No. 21', '081234567895', 2, 'password123'),
(7, '2024007', 'Gita Permata', 'Jl. Tulip No. 9', '081234567896', 5, 'password123'),
(8, '2024008', 'Hadi Sucipto', 'Jl. Kenanga No. 15', '081234567897', 4, 'password123'),
(9, '2024009', 'Indah Sari', 'Jl. Matahari No. 7', '081234567898', 3, 'password123'),
(10, '2024010', 'Joko Susilo', 'Jl. Teratai No. 4', '081234567899', 5, 'password123'),
(11, '2024011', 'Dimas Suhendra', NULL, '085780809099', 5, '$2y$10$W367TmJPx0rEvdrxPU2u.ex4L.4ykpUxddyn9G2rMjOHBzZTwXB.K');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `cuti_izin`
--
ALTER TABLE `cuti_izin`
  ADD PRIMARY KEY (`id_cuti`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id_gaji`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cuti_izin`
--
ALTER TABLE `cuti_izin`
  MODIFY `id_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id_gaji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE;

--
-- Constraints for table `cuti_izin`
--
ALTER TABLE `cuti_izin`
  ADD CONSTRAINT `cuti_izin_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE;

--
-- Constraints for table `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `gaji_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
