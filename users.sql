-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 15 Jan 2026 pada 07.37
-- Versi server: 8.4.3
-- Versi PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `uigm_polban`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit_prodi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gedung` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Gedung default untuk user ini',
  `role` enum('admin_pusat','admin_unit','super_admin','user','pengelola_tps') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `unit_id` int UNSIGNED DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `full_name`, `unit_prodi`, `gedung`, `role`, `unit_id`, `status_aktif`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'superadmin@polban.ac.id', 'superadmin123', 'Super Administrator', NULL, NULL, NULL, 'super_admin', NULL, 1, '2026-01-05 06:47:56', '2026-01-02 08:00:00', '2026-01-05 06:47:56'),
(2, 'admin', 'adminpusat@polban.ac.id', 'admin12345678', 'Admin Pusat UIGM', NULL, NULL, NULL, 'admin_pusat', NULL, 1, '2026-01-13 02:28:06', '2026-01-02 08:00:00', '2026-01-13 02:28:06'),
(3, 'user1', 'user1@polban.ac.id', 'user12345', 'John Doe', NULL, NULL, NULL, 'user', 4, 1, '2026-01-07 02:37:26', '2026-01-02 08:00:00', '2026-01-07 02:37:26'),
(12, 'admin_test', 'admin.test@polban.ac.id', 'admintest123', 'Admin Test POLBAN', NULL, NULL, NULL, 'admin_pusat', NULL, 1, '2026-01-13 02:27:52', '2026-01-06 09:50:57', '2026-01-13 02:27:52'),
(13, 'madsky', 'madski@gmail.com', 'password12345', 'Ahmad Hidayat', NULL, NULL, NULL, 'user', 4, 1, '2026-01-06 04:23:01', '2026-01-06 04:22:44', '2026-01-06 04:23:01'),
(14, 'pengelolatps', 'nabilalrhozaq@polban.ac.id', 'password12345', 'M Nabil Alrhozaq', NULL, NULL, NULL, 'user', 1, 1, NULL, '2026-01-06 05:16:04', '2026-01-06 05:16:04'),
(16, 'tpsbaru', 'info@polban.ac.id', 'tps12345', 'petugas', NULL, NULL, NULL, '', 1, 1, '2026-01-07 02:12:08', '2026-01-06 06:45:35', '2026-01-07 02:12:08');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `role` (`role`),
  ADD KEY `idx_users_role_status` (`role`,`status_aktif`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
