<?php
/**
 * Setup Modular DB & Automatic Document Migrator
 * Desa Klego Government Portal
 */

include 'config/database.php';

echo "=== MEMULAI SETUP DATABASE MODULAR DESA KLEGO ===\n\n";

// 1. Update Profil Desa dengan logo boyolali dan data aktual
$sqlProfil = "UPDATE profil_desa SET 
    nama_desa = 'Desa Klego', 
    kecamatan = 'Klego', 
    kabupaten = 'Boyolali', 
    provinsi = 'Jawa Tengah', 
    logo = 'logoboyolali.png',
    alamat = 'Jl. Raya Klego-Andong, Balai Desa Klego, Kec. Klego, Kab. Boyolali, Jawa Tengah 57385',
    telepon = '(0276) 321-456',
    email = 'desaklego@boyolali.go.id'
    WHERE id = 1";
if (mysqli_query($conn, $sqlProfil)) {
    echo "[OK] Profil Desa diupdate dengan Logo Boyolali.\n";
} else {
    echo "[ERROR] Gagal update profil: " . mysqli_error($conn) . "\n";
}

// 2. Buat Tabel Menu Navbar Ala WordPress
$sqlMenu = "CREATE TABLE IF NOT EXISTS `menu_navbar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT 0,
  `label` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
mysqli_query($conn, $sqlMenu);
echo "[OK] Tabel menu_navbar siap.\n";

// Reset dan Injeksi Data Menu Navbar
mysqli_query($conn, "TRUNCATE TABLE menu_navbar");
$menus = [
    [1, 0, 'Beranda', 'index.php', 1],
    [2, 0, 'Profil Desa', '#', 2],
    [3, 2, 'Sejarah & Visi Misi', 'page.php?slug=sejarah-visi-misi', 1],
    [4, 2, 'Struktur Pemerintahan', 'page.php?slug=struktur-pemerintahan', 2],
    [5, 2, 'WebGIS Peta & Wilayah', 'peta-desa.php', 3],
    [6, 0, 'Data & Transparansi', '#', 3],
    [7, 6, 'Data Pertanian Desa', 'data-pertanian.php', 1],
    [8, 6, 'Data Stunting Balita', 'data-stunting.php', 2],
    [9, 6, 'Direktori & Potensi UMKM', 'data-umkm.php', 3],
    [10, 6, 'Infografis APBDes 2026', 'infografis.php', 4],
    [11, 0, 'Regulasi & Aset Desa', 'dokumen.php', 4],
    [12, 11, 'Peraturan & Produk Legislasi', 'dokumen.php?kategori=Peraturan+%26+Produk+Legislasi+Desa', 1],
    [13, 11, 'Inventarisasi Aset & Pembendaharaan', 'dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi', 2],
    [14, 11, 'RPJM Desa & Perencanaan', 'dokumen.php?kategori=Rencana+Pembangunan+Jangka+Menengah+%28RPJM%29', 3],
    [15, 0, 'Pelayanan & Berita', '#', 5],
    [16, 15, 'Panduan Layanan Masyarakat', 'page.php?slug=panduan-layanan', 1],
    [17, 15, 'Berita & Agenda Desa', 'berita.php', 2],
];
foreach ($menus as $m) {
    mysqli_query($conn, "INSERT INTO menu_navbar (id, parent_id, label, url, urutan) VALUES ({$m[0]}, {$m[1]}, '{$m[2]}', '{$m[3]}', {$m[4]})");
}
echo "[OK] Menu Navbar ala WordPress berhasil diposisikan (17 item terlengkap).\n";

// 3. Buat Tabel Halaman Statis
$sqlHalaman = "CREATE TABLE IF NOT EXISTS `halaman_statis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `konten` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
mysqli_query($conn, $sqlHalaman);
echo "[OK] Tabel halaman_statis siap.\n";

// Masukkan contoh halaman WordPress-style
$pages = [
    [
        'Sejarah & Visi Misi Desa Klego',
        'sejarah-visi-misi',
        '<h3>Visi Kepala Desa</h3><p><em>"Mewujudkan Desa Klego yang Sejahtera, Mandiri, Berbudaya, dan Transparan Melalui Tata Kelola Pemerintahan yang Akuntabel."</em></p><h3>Misi Desa</h3><ol><li>Meningkatkan kualitas pelayanan administrasi publik bagi seluruh masyarakat Desa Klego dengan memanfaatkan teknologi informasi.</li><li>Membangun infrastruktur jalan dan fasilitas kemasyarakatan yang merata dan berkelanjutan di seluruh dusun.</li><li>Mengembangkan potensi ekonomi lokal berbasis pertanian modern dan pemberdayaan pelaku UMKM.</li><li>Mengedepankan keterbukaan informasi publik dan pengelolaan anggaran yang transparan dan tepat sasaran.</li></ol><h3>Sejarah Singkat Desa Klego</h3><p>Desa Klego terletak di wilayah strategis Kabupaten Boyolali. Sejak lampau, desa ini dikenal sebagai pusat pertumbuhan ekonomi lokal dan kawasan agraris berkeadilan, yang kini bertransformasi menjadi pemerintahan modern berdaya saing tinggi.</p>'
    ],
    [
        'Struktur Pemerintah Desa Klego',
        'struktur-pemerintahan',
        '<h3>Susunan Organisasi dan Tata Kerja (SOTK) Pemdes Klego</h3><p>Pemerintah Desa Klego berorientasi pada pelayanan cepat dan terpadu. Kepala Desa dan jajaran perangkatnya senantiasa hadir untuk menjamin kebutuhan administrasi serta kemajuan pembangunan di 5 Dusun, 6 RW, dan 18 RT yang ada di Desa Klego.</p><p>Untuk rincian pejabat lengkap beserta kedudukan maupun status tugasan, masyarakat dapat berkonsultasi langsung melalui Kantor Balai Desa pada hari kerja.</p>'
    ],
    [
        'Potensi & UMKM Desa Klego',
        'potensi-desa',
        '<h3>Keunggulan Pertanian dan Produk UMKM</h3><p>Desa Klego dianugerahi lahan pertanian produktif seluas lebih dari 312 Hektar yang menghasilkan komoditas utama pangan bernilai tinggi. Selain dari sektor agraris, tumbuhnya 87 pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) menjadi penyokong ekonomi berdaya saing, meliputi:</p><ul><li>Kerajinan Anyaman Bambu & Kayu bernilai ekspor.</li><li>Olahan Hasil Pangan Lokal dan Tradisional.</li><li>Batik Tulis dan Konveksi Masyarakat.</li></ul>'
    ],
    [
        'Panduan Layanan Administrasi Masyarakat',
        'panduan-layanan',
        '<h3>Layanan Mudah & Cepat di Balai Desa Klego</h3><p>Pemerintah Desa memfasilitasi pengurusan surat keterangan secara cepat (selesainya 1 Hari Kerja). Berikut prasyarat dokumen bagi masyarakat:</p><ul><li><strong>Surat Keterangan Domisili / Usaha / Tidak Mampu:</strong> Membawa Fotokopi KTP, KK, dan Surat Pengantar dari Ketua RT/RW setempat.</li><li><strong>Surat Pengantar KTP / Kartu Keluarga (KK):</strong> Membawa blanko F1.01 atau KK lama asli yang hendak diperbarui.</li></ul><p>Layanan buka hari Senin s/d Jumat pukul 08.00 - 16.00 WIB di Kantor Balai Desa.</p>'
    ]
];
foreach ($pages as $p) {
    $judul = mysqli_real_escape_string($conn, $p[0]);
    $slug = mysqli_real_escape_string($conn, $p[1]);
    $konten = mysqli_real_escape_string($conn, $p[2]);
    $check = mysqli_query($conn, "SELECT id FROM halaman_statis WHERE slug='$slug'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO halaman_statis (judul, slug, konten) VALUES ('$judul', '$slug', '$konten')");
    } else {
        mysqli_query($conn, "UPDATE halaman_statis SET judul='$judul', konten='$konten' WHERE slug='$slug'");
    }
}
echo "[OK] Halaman Statis Kustom diisikan.\n";

// 4. Buat Tabel Dokumen Publik & Migrator Berkas dari Folder Satria
$sqlDokumen = "CREATE TABLE IF NOT EXISTS `dokumen_publik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(150) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(20) DEFAULT NULL,
  `file_size` int(11) DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `unduhan` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
mysqli_query($conn, $sqlDokumen);
echo "[OK] Tabel dokumen_publik siap.\n";

// Impor Folder Sumber Data Satria
$dataRoot = __DIR__ . '/data/Sumber Data Satria';
if (is_dir($dataRoot)) {
    $subdirs = scandir($dataRoot);
    $countDoc = 0;
    foreach ($subdirs as $dir) {
        if ($dir === '.' || $dir === '..') continue;
        $dirPath = $dataRoot . '/' . $dir;
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);
            foreach ($files as $f) {
                if ($f === '.' || $f === '..') continue;
                $filePath = $dirPath . '/' . $f;
                if (is_file($filePath)) {
                    $relPath = 'data/Sumber Data Satria/' . $dir . '/' . $f;
                    $title = pathinfo($f, PATHINFO_FILENAME);
                    $title = str_replace(['.docx', '.doc', '_', '-'], [' ', ' ', ' ', ' '], $title);
                    $title = trim(preg_replace('/\s+/', ' ', $title));
                    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                    $size = filesize($filePath);
                    $tgl = '2026-01-15';
                    if (strpos($f, '2025') !== false) {
                        $tgl = '2025-12-31';
                    }
                    
                    $titleEsc = mysqli_real_escape_string($conn, $title);
                    $dirEsc = mysqli_real_escape_string($conn, $dir);
                    $relEsc = mysqli_real_escape_string($conn, $relPath);
                    
                    $chkDoc = mysqli_query($conn, "SELECT id FROM dokumen_publik WHERE file_path='$relEsc'");
                    if (mysqli_num_rows($chkDoc) == 0) {
                        mysqli_query($conn, "INSERT INTO dokumen_publik (judul, kategori, file_path, file_type, file_size, keterangan, tanggal) VALUES ('$titleEsc', '$dirEsc', '$relEsc', '$ext', '$size', 'Dokumen resmi Pemerintahan Desa Klego dalam kategori $dirEsc.', '$tgl')");
                        $countDoc++;
                    } else {
                        mysqli_query($conn, "UPDATE dokumen_publik SET judul='$titleEsc', kategori='$dirEsc', file_size='$size', tanggal='$tgl' WHERE file_path='$relEsc'");
                    }
                }
            }
        }
    }
    echo "[OK] Berhasil mengimpor/memperbarui $countDoc dokumen baru dari Sumber Data Satria.\n";
} else {
    echo "[WARNING] Folder $dataRoot tidak ditemukan.\n";
}

// 5. Buat Tabel Infografis Keuangan & Statistik Pembendaharaan Negara
$sqlInfogra = "CREATE TABLE IF NOT EXISTS `infografis_statistik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) NOT NULL,
  `label` varchar(150) NOT NULL,
  `nilai` double DEFAULT 0,
  `satuan` varchar(50) DEFAULT '',
  `tahun` varchar(10) DEFAULT '2026',
  `warna` varchar(50) DEFAULT '#165f36',
  `urutan` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
mysqli_query($conn, $sqlInfogra);
echo "[OK] Tabel infografis_statistik siap.\n";

$chkInfogra = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM infografis_statistik");
$rowInfo = mysqli_fetch_assoc($chkInfogra);
if ($rowInfo['cnt'] == 0) {
    // Data resmi dari sumber XLSX Naila (APBDesa 2026)
    $stats = [
        // PENDAPATAN DESA 2026 (Sumber: Pendapatan Desa 2026_Diagram Lingkaran.xlsx)
        ['Pendapatan APBDes 2026', 'Pendapatan Asli Desa',               357550000,  'Rp', '2026', '#165f36', 1],
        ['Pendapatan APBDes 2026', 'Pendapatan Transfer',                1440238000, 'Rp', '2026', '#2e9e5b', 2],
        ['Pendapatan APBDes 2026', 'Lain-lain Pendapatan Yang Sah',      2500000,    'Rp', '2026', '#c4891f', 3],

        // BELANJA DESA 2026 (Sumber: Belanja Desa 2026_Diagram Lingkaran.xlsx)
        ['Belanja APBDes 2026', 'Penyelenggaraan Pemerintahan Desa',       907361852, 'Rp', '2026', '#1e40af', 1],
        ['Belanja APBDes 2026', 'Pelaksanaan Pembangunan Desa',            805142500, 'Rp', '2026', '#10b981', 2],
        ['Belanja APBDes 2026', 'Pembinaan Kemasyarakatan Desa',           268541000, 'Rp', '2026', '#f59e0b', 3],
        ['Belanja APBDes 2026', 'Pemberdayaan Masyarakat Desa',            22141000,  'Rp', '2026', '#8ecba5', 4],
        ['Belanja APBDes 2026', 'Penanggulangan Bencana, Darurat & Mendesak', 112310000, 'Rp', '2026', '#ef4444', 5],

        // PEMBIAYAAN 2026 (Sumber: Pembiayaan 2026_Diagram Batang.xlsx)
        ['Pembiayaan APBDes 2026', 'Penerimaan Pembiayaan',    350088352,   'Rp', '2026', '#7c3aed', 1],
        ['Pembiayaan APBDes 2026', 'Pengeluaran Pembiayaan',   34880000,    'Rp', '2026', '#be123c', 2],

        // SILPA & ASET 2025 (Referensi)
        ['SILPA & Aset 2025', 'Saldo Akhir Kas / SILPA 2025',            350276263,   'Rp', '2025', '#047857', 1],
        ['SILPA & Aset 2025', 'Nilai Aset Tetap 2025',                   37240430950, 'Rp', '2025', '#d97706', 2],
        ['SILPA & Aset 2025', 'Lahan Pertanian & Kas Desa',              312,         'Hektar', '2025', '#059669', 3]
    ];
    foreach ($stats as $st) {
        mysqli_query($conn, "INSERT INTO infografis_statistik (kategori, label, nilai, satuan, tahun, warna, urutan) VALUES ('{$st[0]}', '{$st[1]}', {$st[2]}, '{$st[3]}', '{$st[4]}', '{$st[5]}', {$st[6]})");
    }
    echo "[OK] Data Infografis APBDes 2026 (sumber XLSX Naila) berhasil ditanamkan.\n";
}

echo "\n=== SETUP SELESAI DENGAN SUKSES! ===\n";
?>
