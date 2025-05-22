-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2025 at 08:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_buku`
--

CREATE TABLE `data_buku` (
  `kode_buku` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `no_buku` int NOT NULL,
  `judul_buku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_terbit` int NOT NULL,
  `penulis` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `penerbit` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_halaman` int NOT NULL,
  `harga` int NOT NULL,
  `gambar_buku` text COLLATE utf8mb4_general_ci NOT NULL,
  `stok` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_buku`
--

INSERT INTO `data_buku` (`kode_buku`, `no_buku`, `judul_buku`, `tahun_terbit`, `penulis`, `penerbit`, `jumlah_halaman`, `harga`, `gambar_buku`, `stok`) VALUES
('senja-004', 4, 'senja dan gerimis', 2024, 'edy', 'Lampung ', 95, 25000, 'uploads/682d280bb208e_edy.jpg', 9);

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `kode_buku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_peminjam` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali_target` date DEFAULT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `kode_buku`, `nama_peminjam`, `tanggal_pinjam`, `tanggal_kembali_target`, `tanggal_kembali_aktual`, `status`) VALUES
(1, 'senja-004', 'eko', '2025-05-21', NULL, '2025-05-21', 'dikembalikan'),
(2, 'senja-004', 'eko', '2025-05-21', NULL, '2025-05-21', 'dikembalikan'),
(3, 'senja-004', 'eko', '2025-05-21', '2025-05-28', '2025-05-21', 'dikembalikan'),
(4, 'senja-004', 'joko', '2025-05-21', '2025-05-28', '2025-05-21', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','admin','superadmin') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'user', 'user', 'user'),
(2, 'admin', 'admin', 'admin'),
(3, 'superadmin', 'superadmin', 'superadmin'),
(4, 'DAPA', 'TUKAM', 'superadmin'),
(5, 'BIMA', 'EGI', 'user'),
(6, 'superadmin', 'superadmin', 'superadmin'),
(7, 'ndol', 'ndnd', 'admin'),
(8, 'superadmin', 'superadmin', 'superadmin'),
(9, 'superadmin', 'superadmin', 'superadmin'),
(10, 'anjay', 'mabar', 'superadmin'),
(11, 'anjay', 'mabar', 'superadmin'),
(12, 'anjay', 'mabar', 'superadmin'),
(13, 'anjay', 'mabar', 'user'),
(14, 'admin', 'admin', 'admin'),
(15, 'eko', 'pepeq', 'user'),
(16, 'herisus', 'pp', 'admin'),
(17, 'dexa', 'xa', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_buku`
--
ALTER TABLE `data_buku`
  ADD PRIMARY KEY (`kode_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
