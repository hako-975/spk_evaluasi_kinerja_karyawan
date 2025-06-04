-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Jun 2025 pada 12.46
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_evaluasi_kinerja_karyawan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_fucom`
--

CREATE TABLE `hasil_fucom` (
  `id_hasil` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `nilai_akhir` float NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_fucom`
--

INSERT INTO `hasil_fucom` (`id_hasil`, `id_karyawan`, `nilai_akhir`, `dibuat_pada`) VALUES
(5, 3, 86.1355, '2025-06-03 10:29:43'),
(6, 4, 90.009, '2025-06-03 13:25:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `foto` text NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nik`, `nama_karyawan`, `tanggal_lahir`, `jenis_kelamin`, `no_hp`, `alamat`, `foto`, `dibuat_pada`) VALUES
(3, '3674072901020001', 'Aldo Hermawan Suryana', '2001-04-08', 'laki-laki', '088298809929', 'Tangerang Selatan', '683e934a94431_1748931402_universitas-pamulang-logo-E63E1DF629-seeklogo.com.png', '2025-06-03 10:21:54'),
(4, '3674072901020002', 'Tatang Suryana', '1954-08-13', 'laki-laki', '088212356087', 'Tangerang Selatan', 'default.jpg', '2025-06-03 13:25:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kriteria_ke` int(11) NOT NULL,
  `peringkat_kepentingan` int(11) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` float NOT NULL,
  `bobot_normalisasi` float DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kriteria_ke`, `peringkat_kepentingan`, `nama_kriteria`, `bobot`, `bobot_normalisasi`, `dibuat_pada`) VALUES
(7, 1, 1, 'kehadiran', 0.15, 0.0015, '2025-06-03 10:24:39'),
(8, 2, 2, 'kedisplinan', 0.2, 0.0076, '2025-06-03 10:26:35'),
(9, 3, 3, 'Produktivitas', 0.3, 0.0252, '2025-06-03 10:27:16'),
(10, 4, 4, 'kerja sama ', 0.2, 0.126, '2025-06-03 10:28:18'),
(11, 5, 5, 'Kreativitas', 0.15, 0.8398, '2025-06-03 10:28:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log`
--

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL,
  `isi_log` text NOT NULL,
  `tgl_log` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log`
--

INSERT INTO `log` (`id_log`, `isi_log`, `tgl_log`, `id_user`) VALUES
(1, 'Siswa Andri Firman Saputra berhasil ditambahkan!', '2025-01-21 08:01:12', 1),
(2, 'SPK Ekstrakurikuler Andri Firman Saputra Berhasil ditambahkan!', '2025-01-21 08:02:40', 1),
(3, 'User admin berhasil login!', '2025-01-21 12:06:52', 1),
(4, 'User admin berhasil login!', '2025-01-23 14:50:12', 1),
(5, 'Karyawan Habib Al Huda gagal ditambahkan!', '2025-01-25 13:08:52', 1),
(6, 'Karyawan Habib Al Huda berhasil ditambahkan!', '2025-01-25 13:09:48', 1),
(7, 'Karyawan Habib Al Huda123 berhasil diubah!', '2025-01-25 13:10:09', 1),
(8, 'Karyawan Habib Al Huda123 berhasil dihapus!', '2025-01-25 13:10:14', 1),
(9, 'Kriteria tes berhasil ditambahkan!', '2025-01-25 13:26:50', 1),
(10, 'Kriteria tes123 berhasil diubah!', '2025-01-25 13:26:55', 1),
(11, 'Evaluasi Kinerja Karyawan tes123 berhasil dihapus!', '2025-01-25 13:26:58', 1),
(12, 'User admin berhasil login!', '2025-01-27 11:48:37', 1),
(13, 'Kriteria Bakat gagal diubah!', '2025-01-27 11:52:34', 1),
(14, 'Kriteria asd gagal ditambahkan!', '2025-01-27 11:53:05', 1),
(15, 'Kriteria asd berhasil ditambahkan!', '2025-01-27 11:53:17', 1),
(16, 'Evaluasi Kinerja Karyawan asd berhasil dihapus!', '2025-01-27 11:53:19', 1),
(17, 'Evaluasi Kinerja Karyawan Bakat berhasil dihapus!', '2025-01-27 11:53:21', 1),
(18, 'Evaluasi Kinerja Karyawan Kesulitan berhasil dihapus!', '2025-01-27 11:53:27', 1),
(19, 'Evaluasi Kinerja Karyawan Minat berhasil dihapus!', '2025-01-27 11:53:28', 1),
(20, 'Kriteria Prestasi123 berhasil diubah!', '2025-01-27 11:53:33', 1),
(21, 'Evaluasi Kinerja Karyawan Prestasi123 berhasil dihapus!', '2025-01-27 11:53:36', 1),
(22, 'Kriteria Kehadiran berhasil ditambahkan!', '2025-01-27 11:54:25', 1),
(23, 'Kriteria Produktivitas berhasil ditambahkan!', '2025-01-27 11:54:44', 1),
(24, 'Kriteria Kerja Sama Tim berhasil ditambahkan!', '2025-01-27 11:54:58', 1),
(25, 'Kriteria Kedisiplinan berhasil ditambahkan!', '2025-01-27 11:55:10', 1),
(26, 'Kriteria Kreativitas berhasil ditambahkan!', '2025-01-27 11:55:21', 1),
(27, 'User admin berhasil login!', '2025-01-27 16:25:44', 1),
(28, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-27 16:48:31', 1),
(29, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-27 16:48:48', 1),
(30, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-27 16:58:51', 1),
(31, 'User admin berhasil login!', '2025-01-28 05:56:54', 1),
(32, 'Kriteria Kehadiran berhasil ditambahkan!', '2025-01-28 06:43:19', 1),
(33, 'Kriteria Kreativitas berhasil ditambahkan!', '2025-01-28 06:44:48', 1),
(34, 'Kriteria Kedisiplinan berhasil ditambahkan!', '2025-01-28 06:45:14', 1),
(35, 'Kriteria Kerja Sama Tim berhasil ditambahkan!', '2025-01-28 06:45:56', 1),
(36, 'Kriteria Produktivitas berhasil ditambahkan!', '2025-01-28 06:46:43', 1),
(37, 'Evaluasi Kinerja Karyawan Produktivitas berhasil dihapus!', '2025-01-28 06:46:53', 1),
(38, 'Kriteria Produktivitas berhasil ditambahkan!', '2025-01-28 06:47:03', 1),
(39, 'Kriteria Ketelitian berhasil ditambahkan!', '2025-01-28 06:50:32', 1),
(40, 'Evaluasi Kinerja Karyawan  berhasil dihapus!', '2025-01-28 06:51:12', 1),
(41, 'Evaluasi Kinerja Karyawan Ketelitian berhasil dihapus!', '2025-01-28 06:52:11', 1),
(42, 'Kriteria Produktivitas2 gagal diubah!', '2025-01-28 07:00:08', 1),
(43, 'Kriteria Produktivitas2 berhasil diubah!', '2025-01-28 07:00:38', 1),
(44, 'Kriteria Produktivitas berhasil diubah!', '2025-01-28 07:00:48', 1),
(45, 'Kriteria Produktivitas gagal diubah!', '2025-01-28 07:04:06', 1),
(46, 'Kriteria Produktivitas berhasil diubah!', '2025-01-28 07:04:12', 1),
(47, 'Hasil Evaluasi Kinerja Karyawan  gagal dihapus!', '2025-01-28 07:09:49', 1),
(48, 'User admin berhasil login!', '2025-01-28 08:27:53', 1),
(49, 'User admin berhasil login!', '2025-01-28 09:53:01', 1),
(50, 'User admin berhasil login!', '2025-01-28 16:39:57', 1),
(51, 'Evaluasi Kinerja Karyawan Andri Firman Saputra gagal dihitung!', '2025-01-28 17:12:48', 1),
(52, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-28 17:16:49', 1),
(53, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-28 17:25:01', 1),
(54, 'Kriteria Kehadiran berhasil diubah!', '2025-01-28 17:36:49', 1),
(55, 'Kriteria Kehadiran berhasil diubah!', '2025-01-28 17:36:58', 1),
(56, 'Evaluasi Kinerja Karyawan Kehadiran berhasil dihapus!', '2025-01-28 17:37:07', 1),
(57, 'Kriteria Kehadiran berhasil ditambahkan!', '2025-01-28 17:37:28', 1),
(58, 'Hasil Evaluasi Kinerja Karyawan  gagal dihapus!', '2025-01-28 18:17:52', 1),
(59, 'Hasil Evaluasi Kinerja Karyawan Andri Firman Saputra berhasil dihapus!', '2025-01-28 18:18:42', 1),
(60, 'Evaluasi Kinerja Karyawan Andri Firman Saputra gagal dihitung!', '2025-01-28 18:19:09', 1),
(61, 'Evaluasi Kinerja Karyawan Andri Firman Saputra gagal dihitung!', '2025-01-28 18:19:22', 1),
(62, 'Evaluasi Kinerja Karyawan Andri Firman Saputra gagal dihitung!', '2025-01-28 18:19:29', 1),
(63, 'Evaluasi Kinerja Karyawan Andri Firman Saputra gagal dihitung!', '2025-01-28 18:19:34', 1),
(64, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-28 18:20:09', 1),
(65, 'User admin berhasil login!', '2025-01-31 08:07:53', 1),
(66, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-31 09:32:34', 1),
(67, 'Hasil Evaluasi Kinerja Karyawan Andri Firman Saputra berhasil dihapus!', '2025-01-31 09:33:11', 1),
(68, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-31 09:51:03', 1),
(69, 'User admin berhasil logout!', '2025-01-31 09:51:07', 1),
(70, 'User admin berhasil login!', '2025-01-31 09:51:11', 1),
(71, 'User admin berhasil login!', '2025-03-02 08:41:24', 1),
(72, 'User admin berhasil logout!', '2025-03-02 08:42:05', 1),
(73, 'User admin  berhasil login!', '2025-03-09 21:14:38', 1),
(74, 'User Admin berhasil login!', '2025-05-07 13:10:31', 1),
(75, 'User admin berhasil login!', '2025-05-14 08:01:00', 1),
(76, 'User admin berhasil login!', '2025-05-30 12:18:55', 1),
(77, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-05-30 12:19:55', 1),
(78, 'User admin berhasil login!', '2025-06-02 09:46:01', 1),
(79, 'Hasil Evaluasi Kinerja Karyawan Andri Firman Saputra berhasil dihapus!', '2025-06-02 09:48:18', 1),
(80, 'Evaluasi Kinerja Karyawan Kehadiran berhasil dihapus!', '2025-06-02 09:48:35', 1),
(81, 'Evaluasi Kinerja Karyawan Kreativitas berhasil dihapus!', '2025-06-02 09:48:39', 1),
(82, 'Evaluasi Kinerja Karyawan Kedisiplinan berhasil dihapus!', '2025-06-02 09:48:43', 1),
(83, 'Evaluasi Kinerja Karyawan Kerja Sama Tim berhasil dihapus!', '2025-06-02 09:48:46', 1),
(84, 'Evaluasi Kinerja Karyawan Produktivitas berhasil dihapus!', '2025-06-02 09:48:50', 1),
(85, 'Karyawan Andri Firman Saputra berhasil dihapus!', '2025-06-02 09:49:05', 1),
(86, 'User admin berhasil login!', '2025-06-02 09:50:21', 1),
(87, 'User admin berhasil logout!', '2025-06-02 09:59:59', 1),
(88, 'User admin berhasil login!', '2025-06-03 01:05:23', 1),
(89, 'User admin berhasil login!', '2025-06-03 01:06:20', 1),
(90, 'User admin berhasil login!', '2025-06-03 02:19:57', 1),
(91, 'User admin berhasil login!', '2025-06-03 03:19:43', 1),
(92, 'Kriteria feasd berhasil ditambahkan!', '2025-06-03 03:21:09', 1),
(93, 'Karyawan ewrwr berhasil ditambahkan!', '2025-06-03 03:21:54', 1),
(94, 'SPK Evaluasi Kinerja Karyawan ewrwr Berhasil ditambahkan!', '2025-06-03 03:22:22', 1),
(95, 'Kriteria feasd berhasil diubah!', '2025-06-03 03:23:06', 1),
(96, 'Karyawan ewrwr berhasil diubah!', '2025-06-03 03:23:19', 1),
(97, 'Evaluasi Kinerja Karyawan feasd berhasil dihapus!', '2025-06-03 03:24:05', 1),
(98, 'Kriteria kedisplinan berhasil ditambahkan!', '2025-06-03 03:24:39', 1),
(99, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 03:25:11', 1),
(100, 'Kriteria kehadiran berhasil ditambahkan!', '2025-06-03 03:26:35', 1),
(101, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 03:26:49', 1),
(102, 'Kriteria Produktivitas berhasil ditambahkan!', '2025-06-03 03:27:16', 1),
(103, 'Kriteria kerja sama  berhasil ditambahkan!', '2025-06-03 03:28:18', 1),
(104, 'Kriteria Kreativitas berhasil ditambahkan!', '2025-06-03 03:28:41', 1),
(105, 'Hasil Evaluasi Kinerja Karyawan ewrwr berhasil dihapus!', '2025-06-03 03:28:54', 1),
(106, 'SPK Evaluasi Kinerja Karyawan ewrwr Berhasil ditambahkan!', '2025-06-03 03:29:43', 1),
(107, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 03:31:16', 1),
(108, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 03:34:41', 1),
(109, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 03:35:32', 1),
(110, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 03:35:51', 1),
(111, 'Kriteria Kreativitas berhasil diubah!', '2025-06-03 03:36:13', 1),
(112, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 03:36:41', 1),
(113, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 03:36:54', 1),
(114, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 03:37:04', 1),
(115, 'User admin berhasil login!', '2025-06-03 03:51:49', 1),
(116, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 03:56:12', 1),
(117, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 03:56:39', 1),
(118, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 03:56:53', 1),
(119, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 03:57:06', 1),
(120, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 03:57:19', 1),
(121, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 03:57:42', 1),
(122, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 03:57:57', 1),
(123, 'Kriteria Kreativitas berhasil diubah!', '2025-06-03 03:58:10', 1),
(124, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 03:58:24', 1),
(125, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 03:58:49', 1),
(126, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 03:59:05', 1),
(127, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 03:59:18', 1),
(128, 'Kriteria Kreativitas berhasil diubah!', '2025-06-03 03:59:31', 1),
(129, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 04:00:11', 1),
(130, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 04:00:56', 1),
(131, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 04:01:09', 1),
(132, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 04:01:19', 1),
(133, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 04:01:31', 1),
(134, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 04:01:44', 1),
(135, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 04:02:04', 1),
(136, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 04:02:16', 1),
(137, 'User admin berhasil login!', '2025-06-03 06:06:25', 1),
(138, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 06:07:24', 1),
(139, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 06:07:38', 1),
(140, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 06:08:35', 1),
(141, 'Kriteria kerja sama  berhasil diubah!', '2025-06-03 06:08:54', 1),
(142, 'Kriteria Kreativitas berhasil diubah!', '2025-06-03 06:09:03', 1),
(143, 'User Admin berhasil login!', '2025-06-03 06:11:44', 1),
(144, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 06:12:03', 1),
(145, 'Kriteria kedisplinan berhasil diubah!', '2025-06-03 06:12:11', 1),
(146, 'Kriteria Produktivitas berhasil diubah!', '2025-06-03 06:12:25', 1),
(147, 'Kriteria Kreativitas berhasil diubah!', '2025-06-03 06:12:44', 1),
(148, 'Karyawan Aldo Hermawan Suryana berhasil diubah!', '2025-06-03 06:16:42', 1),
(149, 'Kriteria kehadiran berhasil diubah!', '2025-06-03 06:17:10', 1),
(150, 'Kriteria kehadiran gagal ditambahkan!', '2025-06-03 06:20:45', 1),
(151, 'Kriteria kehadiran gagal ditambahkan!', '2025-06-03 06:21:32', 1),
(152, 'Kriteria kehadiran berhasil ditambahkan!', '2025-06-03 06:23:19', 1),
(153, 'Evaluasi Kinerja Karyawan kehadiran berhasil dihapus!', '2025-06-03 06:23:29', 1),
(154, 'Karyawan Tatang Suryana berhasil ditambahkan!', '2025-06-03 06:25:08', 1),
(155, 'SPK Evaluasi Kinerja Karyawan Tatang Suryana Berhasil ditambahkan!', '2025-06-03 06:25:32', 1),
(156, 'Kriteria kehadiran berhasil ditambahkan!', '2025-06-03 06:26:06', 1),
(157, 'Kriteria kedisplinan berhasil ditambahkan!', '2025-06-03 06:26:36', 1),
(158, 'Kriteria Produktivitas berhasil ditambahkan!', '2025-06-03 06:27:03', 1),
(159, 'Kriteria kerja sama  berhasil ditambahkan!', '2025-06-03 06:27:29', 1),
(160, 'Kriteria Kreativitas berhasil ditambahkan!', '2025-06-03 06:28:20', 1),
(161, 'Evaluasi Kinerja Karyawan Kreativitas berhasil dihapus!', '2025-06-03 06:29:12', 1),
(162, 'Evaluasi Kinerja Karyawan kerja sama  berhasil dihapus!', '2025-06-03 06:29:18', 1),
(163, 'Evaluasi Kinerja Karyawan Produktivitas berhasil dihapus!', '2025-06-03 06:29:23', 1),
(164, 'Evaluasi Kinerja Karyawan kedisplinan berhasil dihapus!', '2025-06-03 06:29:29', 1),
(165, 'Evaluasi Kinerja Karyawan kehadiran berhasil dihapus!', '2025-06-03 06:29:33', 1),
(166, 'User admin berhasil login!', '2025-06-03 07:03:55', 1),
(167, 'User admin berhasil login!', '2025-06-03 07:33:10', 1),
(168, 'User admin berhasil login!', '2025-06-03 17:34:44', 1),
(169, 'User admin berhasil login!', '2025-06-04 10:34:01', 1),
(170, 'Karyawan Habib Al Huda berhasil ditambahkan!', '2025-06-04 10:37:58', 1),
(171, 'Karyawan Habib Al Huda berhasil diubah!', '2025-06-04 10:38:04', 1),
(172, 'Karyawan Habib Al Huda berhasil dihapus!', '2025-06-04 10:38:06', 1),
(173, 'User admin berhasil logout!', '2025-06-04 10:41:43', 1),
(174, 'User admin berhasil login!', '2025-06-04 10:45:13', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `id_hasil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_kriteria`, `nilai`, `id_hasil`) VALUES
(22, 7, 90, 5),
(23, 8, 85, 5),
(24, 9, 95, 5),
(25, 10, 90, 5),
(26, 11, 85, 5),
(27, 7, 90, 6),
(28, 8, 90, 6),
(29, 9, 90, 6),
(30, 10, 90, 6),
(31, 11, 90, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jabatan` enum('admin','petugas') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `foto` text NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `jabatan`, `nama`, `foto`, `dibuat_pada`) VALUES
(1, 'admin', '$2y$10$PDN4Md5jfPRsvJ5DJyJ.r.Bcf6mMSG.g5BBZaivJEd6padJYBerky', 'admin', 'Administrator', 'avatar.png', '2025-01-07 09:15:31');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hasil_fucom`
--
ALTER TABLE `hasil_fucom`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD UNIQUE KEY `peringkat_kepentingan` (`peringkat_kepentingan`),
  ADD UNIQUE KEY `kriteria_ke` (`kriteria_ke`);

--
-- Indeks untuk tabel `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `id_hasil` (`id_hasil`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hasil_fucom`
--
ALTER TABLE `hasil_fucom`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
