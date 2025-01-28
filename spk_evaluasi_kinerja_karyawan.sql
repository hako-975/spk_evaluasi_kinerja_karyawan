-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jan 2025 pada 19.20
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
(1, 1, 57.641, '2025-01-29 01:20:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
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

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `tanggal_lahir`, `jenis_kelamin`, `no_hp`, `alamat`, `foto`, `dibuat_pada`) VALUES
(1, 'Andri Firman Saputra', '2002-01-29', 'laki-laki', '087808675313', 'pocis', '678f5448e9d26_1737446472_65483768.jpeg', '2025-01-21 15:01:12');

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
(1, 2, 1, 'Produktivitas', 1, 0.3291, '2025-01-28 13:47:03'),
(2, 3, 2, 'Kerja Sama Tim', 1.5, 0.2194, '2025-01-28 13:45:56'),
(3, 4, 3, 'Kedisiplinan', 1.2, 0.1829, '2025-01-28 13:45:14'),
(4, 5, 4, 'Kreativitas', 1.3, 0.1407, '2025-01-28 13:44:48'),
(5, 1, 5, 'Kehadiran', 1.1, 0.1279, '2025-01-29 00:37:28');

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
(64, 'SPK Evaluasi Kinerja Karyawan Andri Firman Saputra Berhasil ditambahkan!', '2025-01-28 18:20:09', 1);

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
(1, 5, 80, 1),
(2, 1, 50, 1),
(3, 2, 54, 1),
(4, 3, 66, 1),
(5, 4, 50, 1);

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
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
