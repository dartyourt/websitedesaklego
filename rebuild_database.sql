-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: desa_klego
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin','Administrator');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `berita`
--

DROP TABLE IF EXISTS `berita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `berita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `foto` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `berita`
--

LOCK TABLES `berita` WRITE;
/*!40000 ALTER TABLE `berita` DISABLE KEYS */;
/*!40000 ALTER TABLE `berita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keuangan`
--

DROP TABLE IF EXISTS `keuangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keuangan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` varchar(10) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `kelompok` varchar(100) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `jumlah` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keuangan`
--

LOCK TABLES `keuangan` WRITE;
/*!40000 ALTER TABLE `keuangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `keuangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pejabat`
--

DROP TABLE IF EXISTS `pejabat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pejabat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `nip` varchar(50) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pejabat`
--

LOCK TABLES `pejabat` WRITE;
/*!40000 ALTER TABLE `pejabat` DISABLE KEYS */;
/*!40000 ALTER TABLE `pejabat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penduduk`
--

DROP TABLE IF EXISTS `penduduk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NIK` varchar(16) NOT NULL,
  `NO_KK` varchar(16) NOT NULL,
  `NAMA_LGKP` varchar(255) NOT NULL,
  `JENIS_KELAMIN` varchar(10) NOT NULL,
  `TMPT_LAHIR` varchar(100) DEFAULT NULL,
  `TGL_LAHIR` date DEFAULT NULL,
  `USIA` varchar(10) DEFAULT NULL,
  `DUSUN` varchar(100) DEFAULT NULL,
  `RT` varchar(5) DEFAULT NULL,
  `RW` varchar(5) DEFAULT NULL,
  `SHDK` varchar(50) DEFAULT NULL,
  `STATUS_KAWIN` varchar(50) DEFAULT NULL,
  `PENDIDIKAN` varchar(100) DEFAULT NULL,
  `AGAMA` varchar(50) DEFAULT NULL,
  `PEKERJAAN` varchar(100) DEFAULT NULL,
  `NO_AKTA_LAHIR` varchar(100) DEFAULT NULL,
  `NO_AKTA_KAWIN` varchar(100) DEFAULT NULL,
  `NO_AKTA_CERAI` varchar(100) DEFAULT NULL,
  `NAMA_AYAH` varchar(255) DEFAULT NULL,
  `NAMA_IBU` varchar(255) DEFAULT NULL,
  `BANTUAN` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penduduk`
--

LOCK TABLES `penduduk` WRITE;
/*!40000 ALTER TABLE `penduduk` DISABLE KEYS */;
/*!40000 ALTER TABLE `penduduk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penduduk_dihapus`
--

DROP TABLE IF EXISTS `penduduk_dihapus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penduduk_dihapus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NIK` varchar(16) NOT NULL,
  `NO_KK` varchar(16) NOT NULL,
  `NAMA_LGKP` varchar(255) NOT NULL,
  `JENIS_KELAMIN` varchar(10) NOT NULL,
  `TMPT_LAHIR` varchar(100) DEFAULT NULL,
  `TGL_LAHIR` date DEFAULT NULL,
  `USIA` varchar(10) DEFAULT NULL,
  `DUSUN` varchar(100) DEFAULT NULL,
  `RT` varchar(5) DEFAULT NULL,
  `RW` varchar(5) DEFAULT NULL,
  `SHDK` varchar(50) DEFAULT NULL,
  `STATUS_KAWIN` varchar(50) DEFAULT NULL,
  `PENDIDIKAN` varchar(100) DEFAULT NULL,
  `AGAMA` varchar(50) DEFAULT NULL,
  `PEKERJAAN` varchar(100) DEFAULT NULL,
  `NO_AKTA_LAHIR` varchar(100) DEFAULT NULL,
  `NO_AKTA_KAWIN` varchar(100) DEFAULT NULL,
  `NO_AKTA_CERAI` varchar(100) DEFAULT NULL,
  `NAMA_AYAH` varchar(255) DEFAULT NULL,
  `NAMA_IBU` varchar(255) DEFAULT NULL,
  `BANTUAN` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penduduk_dihapus`
--

LOCK TABLES `penduduk_dihapus` WRITE;
/*!40000 ALTER TABLE `penduduk_dihapus` DISABLE KEYS */;
/*!40000 ALTER TABLE `penduduk_dihapus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perangkat`
--

DROP TABLE IF EXISTS `perangkat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perangkat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perangkat`
--

LOCK TABLES `perangkat` WRITE;
/*!40000 ALTER TABLE `perangkat` DISABLE KEYS */;
/*!40000 ALTER TABLE `perangkat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profil_desa`
--

DROP TABLE IF EXISTS `profil_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profil_desa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_desa` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profil_desa`
--

LOCK TABLES `profil_desa` WRITE;
/*!40000 ALTER TABLE `profil_desa` DISABLE KEYS */;
INSERT INTO `profil_desa` VALUES (1,'Desa Makmur','Jl. Raya Makmur No. 1','Kecamatan Sejahtera','Kabupaten Bahagia','Provinsi Damai','12345','081234567890','info@desamakmur.go.id','logo_desa_1784000913.png');
/*!40000 ALTER TABLE `profil_desa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sk_dom_bade`
--

DROP TABLE IF EXISTS `sk_dom_bade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sk_dom_bade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sk_dom_bade`
--

LOCK TABLES `sk_dom_bade` WRITE;
/*!40000 ALTER TABLE `sk_dom_bade` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_dom_bade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sk_dom_luar_bade`
--

DROP TABLE IF EXISTS `sk_dom_luar_bade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sk_dom_luar_bade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_manual` varchar(255) DEFAULT NULL,
  `nik_manual` varchar(20) DEFAULT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `jk` varchar(20) DEFAULT NULL,
  `tmpt_lahir` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_surat` varchar(100) DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `RW` varchar(10) DEFAULT NULL,
  `sejak_tinggal` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sk_dom_luar_bade`
--

LOCK TABLES `sk_dom_luar_bade` WRITE;
/*!40000 ALTER TABLE `sk_dom_luar_bade` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_dom_luar_bade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sktm`
--

DROP TABLE IF EXISTS `sktm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sktm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sktm`
--

LOCK TABLES `sktm` WRITE;
/*!40000 ALTER TABLE `sktm` DISABLE KEYS */;
/*!40000 ALTER TABLE `sktm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sku`
--

DROP TABLE IF EXISTS `sku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sku`
--

LOCK TABLES `sku` WRITE;
/*!40000 ALTER TABLE `sku` DISABLE KEYS */;
/*!40000 ALTER TABLE `sku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sp_ktp`
--

DROP TABLE IF EXISTS `sp_ktp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sp_ktp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sp_ktp`
--

LOCK TABLES `sp_ktp` WRITE;
/*!40000 ALTER TABLE `sp_ktp` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_ktp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surat_pengantar`
--

DROP TABLE IF EXISTS `surat_pengantar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surat_pengantar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surat_pengantar`
--

LOCK TABLES `surat_pengantar` WRITE;
/*!40000 ALTER TABLE `surat_pengantar` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_pengantar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surat_umum`
--

DROP TABLE IF EXISTS `surat_umum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surat_umum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `pejabat` varchar(100) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `ttd_camat` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surat_umum`
--

LOCK TABLES `surat_umum` WRITE;
/*!40000 ALTER TABLE `surat_umum` DISABLE KEYS */;
INSERT INTO `surat_umum` VALUES (1,'3309156707120002','ada','awda','adawd\r\nNama tersebut di atas benar-benar warga Desa kami,berkelakuan dan beradat istiadat baik.','sekdes','2026-07-14',0),(2,'3309155609110001','ada','ada','Nama tersebut di atas benar-benar warga Desa kami,berkelakuan dan beradat istiadat baik.','kepala','2026-07-14',0);
/*!40000 ALTER TABLE `surat_umum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wilayah_dusun`
--
DROP TABLE IF EXISTS `wilayah_dusun`;
CREATE TABLE `wilayah_dusun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `wilayah_dusun` WRITE;
INSERT INTO `wilayah_dusun` (`id`, `nama`) VALUES
(1, 'KLEGO'),
(2, 'KARANGANYAR'),
(3, 'NGEMBAT'),
(4, 'KEDOKAN'),
(5, 'KLALINGAN');
UNLOCK TABLES;

--
-- Table structure for table `wilayah_rw`
--
DROP TABLE IF EXISTS `wilayah_rw`;
CREATE TABLE `wilayah_rw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dusun_id` int(11) NOT NULL,
  `rw` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `wilayah_rw` WRITE;
INSERT INTO `wilayah_rw` (`id`, `dusun_id`, `rw`) VALUES
(1, 1, '001'),
(2, 2, '002'),
(3, 3, '003'),
(4, 4, '004'),
(5, 5, '005');
UNLOCK TABLES;

--
-- Table structure for table `wilayah_rt`
--
DROP TABLE IF EXISTS `wilayah_rt`;
CREATE TABLE `wilayah_rt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rw_id` int(11) NOT NULL,
  `rt` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `wilayah_rt` WRITE;
INSERT INTO `wilayah_rt` (`id`, `rw_id`, `rt`) VALUES
(1, 1, '001'), (2, 1, '002'), (3, 1, '003'), (4, 1, '004'), (5, 1, '005'), (6, 1, '006'),
(7, 2, '007'), (8, 2, '008'), (9, 2, '009'), (10, 2, '010'), (11, 2, '011'),
(12, 3, '012'), (13, 3, '013'), (14, 3, '014'),
(15, 4, '015'), (16, 4, '016'), (17, 4, '017'), (18, 4, '018'), (19, 4, '019'), (20, 4, '020'), (21, 4, '021'),
(22, 5, '022'), (23, 5, '023'), (24, 5, '024');
UNLOCK TABLES;

--
-- Table structure for table `surat_template`
--
DROP TABLE IF EXISTS `surat_template`;
CREATE TABLE `surat_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_surat` varchar(255) NOT NULL,
  `kode_surat` varchar(50) DEFAULT NULL,
  `konten_html` longtext NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure and dump for table `agenda_desa`
--
DROP TABLE IF EXISTS `agenda_desa`;
CREATE TABLE `agenda_desa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` varchar(100) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `agenda_desa` (`judul`, `tanggal`, `waktu`, `lokasi`) VALUES
('Posyandu Balita & Pemeriksaan Lansia Rutin', '2026-08-18', '08.00 WIB - Selesai', 'Balai Desa Klego'),
('Rapat Koordinasi Rutin Pengurus RT & RW', '2026-08-22', '19.30 WIB - Selesai', 'Aula Balai Desa Klego'),
('Gotong Royong Kebersihan Lingkungan & Drainase', '2026-08-25', '06.30 WIB - Selesai', 'Seluruh Wilayah 5 Dusun Desa Klego');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-14 11:07:46
