-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2024 at 05:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `nama_pengguna` varchar(255) NOT NULL,
  `id_pelanggan` varchar(255) NOT NULL,
  `jenis_pengaduan` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `kordinat` varchar(255) NOT NULL,
  `kordinat_pindah` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `nama_pengguna`, `id_pelanggan`, `jenis_pengaduan`, `no_hp`, `email`, `alamat`, `kordinat`, `kordinat_pindah`, `status`) VALUES
(12, 'Afin Ramadhan', '1122334455', 'UPGRADE SPEED', '081274956302', 'afinr95@gmail.com', 'Tarok Dipo, Kec. Guguk Panjang, Kota Bukittinggi, Sumatera Barat 26138', '-0.2278293, 100.5810876', '-', 'Belum Di Proses'),
(13, 'Cindy Annisa Putri', '2255443311', 'PINDAH ALAMAT', '0085164886785', 'cindyannisa03@gmail.com', 'Bukittinggi', ' -0.2143607, 100.6212747', '-0.1925789, 100.6396074', 'Belum Di Proses'),
(14, 'Shafira Ramadhina', '88664422', 'MIGRASI PRODUK', '089856453456', 'shafiraramadhina2002@gmail.com', 'pintu Kabun', '-0.2002652, 100.5884493', '-', 'Belum Di Proses');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
