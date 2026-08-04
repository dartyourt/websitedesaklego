<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    include __DIR__ . '/database.php';
}

// Inisialisasi dan Otomatis Setup Tabel Terjemahan jika belum ada
if ($conn && !mysqli_connect_error()) {
    @mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `master_bahasa` (
        `kode` varchar(10) NOT NULL,
        `nama` varchar(50) NOT NULL,
        `bendera` varchar(20) NOT NULL DEFAULT '🇮🇩',
        `is_default` tinyint(1) DEFAULT 0,
        `status` tinyint(1) DEFAULT 1,
        PRIMARY KEY (`kode`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    @mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `terjemahan_konten` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `kategori` varchar(50) NOT NULL COMMENT 'ui, infografis, dokumen, halaman, menu, berita',
        `referensi_id` int(11) DEFAULT 0,
        `kunci` varchar(100) NOT NULL,
        `kode_bahasa` varchar(10) NOT NULL,
        `teks_terjemahan` longtext NOT NULL,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `idx_trans` (`kategori`, `referensi_id`, `kunci`, `kode_bahasa`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Cek data awal master_bahasa
    $chkLang = @mysqli_query($conn, "SELECT count(*) as jml FROM master_bahasa");
    $rowL = $chkLang ? mysqli_fetch_assoc($chkLang) : ['jml' => 0];
    if ($rowL['jml'] == 0) {
        @mysqli_query($conn, "INSERT IGNORE INTO master_bahasa (kode, nama, bendera, is_default, status) VALUES 
        ('id', 'Bahasa Indonesia', '🇮🇩', 1, 1),
        ('en', 'English', '🇬🇧', 0, 1),
        ('ja', '日本語 (Japan)', '🇯🇵', 0, 1);");
        
        // SEED TERJEMAHAN LENGKAP SECARA MENYELURUH (UI, INFOGRAFIS, DOKUMEN, BERITA)
        $defaultDictionary = [
            // === 1. NAV & TOMBOL UI ===
            ['ui', 'beranda', 'en', 'Home'], ['ui', 'beranda', 'ja', 'ホーム'],
            ['ui', 'profil_desa', 'en', 'Village Profile'], ['ui', 'profil_desa', 'ja', '村の概要'],
            ['ui', 'infografis_keuangan', 'en', 'Financial Infographics'], ['ui', 'infografis_keuangan', 'ja', '財務インフォグラフィック'],
            ['ui', 'regulasi_aset', 'en', 'Regulations & Assets'], ['ui', 'regulasi_aset', 'ja', '法規と村の資産'],
            ['ui', 'pelayanan_berita', 'en', 'Services & News'], ['ui', 'pelayanan_berita', 'ja', '行政サービスとニュース'],
            ['ui', 'pustaka_hukum', 'en', 'Legal Repository (JDIH)'], ['ui', 'pustaka_hukum', 'ja', '法規アーカイブ (JDIH)'],
            ['ui', 'kembali_beranda', 'en', 'Back to Home'], ['ui', 'kembali_beranda', 'ja', 'ホームに戻る'],
            ['ui', 'unduh_file', 'en', 'Download'], ['ui', 'unduh_file', 'ja', 'ダウンロード'],
            ['ui', 'pratinjau', 'en', 'Preview'], ['ui', 'pratinjau', 'ja', 'プレビュー'],
            ['ui', 'pusat_unduhan', 'en', 'Download Center'], ['ui', 'pusat_unduhan', 'ja', 'ダウンロードセンター'],
            ['ui', 'selamat_datang', 'en', 'Welcome to the Official Portal of Klego Village Government'], ['ui', 'selamat_datang', 'ja', 'クレゴ村公式政府ポータルへようこそ'],
            ['ui', 'baca_selengkapnya', 'en', 'Read More'], ['ui', 'baca_selengkapnya', 'ja', '続きを読む'],
            ['ui', 'dipublikasikan', 'en', 'Published on'], ['ui', 'dipublikasikan', 'ja', '公開日'],
            ['ui', 'bagikan', 'en', 'Share Article'], ['ui', 'bagikan', 'ja', '共有する'],

            // === 2. INFOGRAFIS & STATISTIK ANGGARAN ===
            ['infografis', 'info_judul', 'en', 'State Treasury & Village Budget (APBDes) Transparency'], ['infografis', 'info_judul', 'ja', '国庫と村予算 (APBDes) の財務透明性'],
            ['infografis', 'info_sub', 'en', 'Real-time financial transparency reports for APBDes, SILPA 2025, and Village Assets book value.'], ['infografis', 'info_sub', 'ja', 'APBDes、2025年SILPA、村資産帳簿価額のリアルタイム財務報告書。'],
            ['infografis', 'pendapatan_apbdes', 'en', '2026 APBDes Revenue'], ['infografis', 'pendapatan_apbdes', 'ja', '2026年度 村予算歳入'],
            ['infografis', 'belanja_apbdes', 'en', '2026 APBDes Expenditure'], ['infografis', 'belanja_apbdes', 'ja', '2026年度 村予算歳出'],
            ['infografis', 'silpa_2025', 'en', '2025 Budget Surplus (SILPA)'], ['infografis', 'silpa_2025', 'ja', '2025年度 予算剰余金 (SILPA)'],
            ['infografis', 'nilai_aset', 'en', 'Book Value of Village Assets'], ['infografis', 'nilai_aset', 'ja', '村資産の帳簿価額'],
            ['infografis', 'komposisi_apbdes', 'en', '2026 Village Budget Composition'], ['infografis', 'komposisi_apbdes', 'ja', '2026年度 村予算構成グラフ'],
            ['infografis', 'sebaran_warga', 'en', 'Population Distribution across 5 Hamlets (Total: 4,823 Citizens)'], ['infografis', 'sebaran_warga', 'ja', '5つの集落ごとの人口分布 (総計: 4,823人)'],

            // === 3. PUSTAKA DOKUMEN & REGULASI ===
            ['dokumen', 'dok_judul', 'en', 'Legal Document Repository & Village Asset Book'], ['dokumen', 'dok_judul', 'ja', '法規ドキュメントアーカイブと村の資産帳簿'],
            ['dokumen', 'dok_sub', 'en', 'Explore 21 official governance archives, including Perdes APBDes 2026, Asset Registers, and 6-Year Development Plan (RPJM).'], ['dokumen', 'dok_sub', 'ja', '2026年APBDes条例、資産登録簿、6カ年計画(RPJM)を含む21の公的記録を閲覧できます。'],
            ['dokumen', 'kat_semua', 'en', 'All Categories'], ['dokumen', 'kat_semua', 'ja', 'すべてのカテゴリー'],
            ['dokumen', 'kat_legislasi', 'en', 'Regulations & Legislation'], ['dokumen', 'kat_legislasi', 'ja', '法規と条例'],
            ['dokumen', 'kat_aset', 'en', 'Assets & Treasury'], ['dokumen', 'kat_aset', 'ja', '資産と国庫報告'],
            ['dokumen', 'kat_rpjm', 'en', 'Development Plans (RPJM)'], ['dokumen', 'kat_rpjm', 'ja', '中長期発展計画 (RPJM)'],
            ['dokumen', 'col_judul', 'en', 'Document Title & Description'], ['dokumen', 'col_judul', 'ja', '文書タイトルと説明'],
            ['dokumen', 'col_kategori', 'en', 'Category'], ['dokumen', 'col_kategori', 'ja', 'カテゴリー'],
            ['dokumen', 'col_tahun', 'en', 'Year / Date Uploaded'], ['dokumen', 'col_tahun', 'ja', '年度 / アップロード日'],
            ['dokumen', 'col_aksi', 'en', 'Action'], ['dokumen', 'col_aksi', 'ja', 'アクション']
        ];
        
        foreach ($defaultDictionary as $item) {
            $kat = mysqli_real_escape_string($conn, $item[0]);
            $kunci = mysqli_real_escape_string($conn, $item[1]);
            $lang = mysqli_real_escape_string($conn, $item[2]);
            $val = mysqli_real_escape_string($conn, $item[3]);
            @mysqli_query($conn, "INSERT IGNORE INTO terjemahan_konten (kategori, referensi_id, kunci, kode_bahasa, teks_terjemahan) VALUES ('$kat', 0, '$kunci', '$lang', '$val')");
        }
    }
}

// Deteksi perubahan bahasa dari URL (?lang=en/ja/id)
if (isset($_GET['lang'])) {
    $reqLang = strtolower(trim($_GET['lang']));
    $_SESSION['lang'] = $reqLang;
}

$currentLang = $_SESSION['lang'] ?? 'id';

// Ambil semua bahasa aktif untuk dropdown switcher
$activeLanguages = [];
if ($conn && !mysqli_connect_error()) {
    $resL = @mysqli_query($conn, "SELECT * FROM master_bahasa WHERE status = 1 ORDER BY is_default DESC, kode ASC");
    if ($resL) {
        while ($rl = mysqli_fetch_assoc($resL)) {
            $activeLanguages[$rl['kode']] = $rl;
        }
    }
}

if (empty($activeLanguages)) {
    $activeLanguages = [
        'id' => ['kode' => 'id', 'nama' => 'Indonesia', 'bendera' => '🇮🇩'],
        'en' => ['kode' => 'en', 'nama' => 'English', 'bendera' => '🇬🇧'],
        'ja' => ['kode' => 'ja', 'nama' => '日本語', 'bendera' => '🇯🇵']
    ];
}

// Ambil kamus terjemahan untuk bahasa aktif (untuk semua kategori string: ui, infografis, dokumen)
$globalDictionary = [];
if ($conn && !mysqli_connect_error() && $currentLang !== 'id') {
    $resDict = @mysqli_query($conn, "SELECT kunci, teks_terjemahan FROM terjemahan_konten WHERE referensi_id = 0 AND kode_bahasa = '$currentLang'");
    if ($resDict) {
        while ($rd = mysqli_fetch_assoc($resDict)) {
            $globalDictionary[$rd['kunci']] = $rd['teks_terjemahan'];
        }
    }
}

/**
 * Fungsi helper untuk menerjemahkan teks UI, Infografis, maupun Dokumen
 */
function t($key, $defaultText = '') {
    global $currentLang, $globalDictionary;
    if ($currentLang === 'id' || empty($globalDictionary[$key])) {
        return $defaultText ?: $key;
    }
    return $globalDictionary[$key];
}

/**
 * Fungsi helper untuk menerjemahkan Halaman Statis
 */
function translatePageData($pageData, $pageId = null) {
    global $conn, $currentLang;
    if ($currentLang === 'id' || !$conn || mysqli_connect_error() || empty($pageData)) {
        return $pageData;
    }

    if (!$pageId && !empty($pageData['id'])) {
        $pageId = (int)$pageData['id'];
    } elseif (!$pageId && !empty($pageData['slug'])) {
        $slg = mysqli_real_escape_string($conn, $pageData['slug']);
        $qId = @mysqli_query($conn, "SELECT id FROM halaman_statis WHERE slug = '$slg'");
        if ($qId && $rId = mysqli_fetch_assoc($qId)) {
            $pageId = (int)$rId['id'];
        }
    }

    if ($pageId > 0) {
        $resTrans = @mysqli_query($conn, "SELECT kunci, teks_terjemahan FROM terjemahan_konten WHERE kategori = 'halaman' AND referensi_id = $pageId AND kode_bahasa = '$currentLang'");
        if ($resTrans && mysqli_num_rows($resTrans) > 0) {
            while ($rt = mysqli_fetch_assoc($resTrans)) {
                if (!empty(trim($rt['teks_terjemahan']))) {
                    $pageData[$rt['kunci']] = $rt['teks_terjemahan'];
                }
            }
        }
    }
    return $pageData;
}

/**
 * Fungsi helper untuk menerjemahkan Berita Desa
 */
function translateBeritaData($berita, $beritaId = null) {
    global $conn, $currentLang;
    if ($currentLang === 'id' || !$conn || mysqli_connect_error() || empty($berita)) {
        return $berita;
    }

    if (!$beritaId && !empty($berita['id'])) {
        $beritaId = (int)$berita['id'];
    }

    if ($beritaId > 0) {
        $resTrans = @mysqli_query($conn, "SELECT kunci, teks_terjemahan FROM terjemahan_konten WHERE kategori = 'berita' AND referensi_id = $beritaId AND kode_bahasa = '$currentLang'");
        if ($resTrans && mysqli_num_rows($resTrans) > 0) {
            while ($rt = mysqli_fetch_assoc($resTrans)) {
                if (!empty(trim($rt['teks_terjemahan']))) {
                    $berita[$rt['kunci']] = $rt['teks_terjemahan'];
                }
            }
        }
    }
    return $berita;
}

/**
 * Fungsi helper untuk menerjemahkan menu navbar dinamis & submenu
 */
function translateMenuItem($menuItem) {
    global $conn, $currentLang, $globalDictionary;
    if ($currentLang === 'id' || empty($menuItem)) {
        return $menuItem;
    }
    
    // 1. Cek terjemahan di db berdasarkan referensi_id menu
    if (!empty($menuItem['id'])) {
        $mId = (int)$menuItem['id'];
        $resTrans = @mysqli_query($conn, "SELECT teks_terjemahan FROM terjemahan_konten WHERE kategori = 'menu' AND referensi_id = $mId AND kunci = 'nama_menu' AND kode_bahasa = '$currentLang'");
        if ($resTrans && $rt = mysqli_fetch_assoc($resTrans)) {
            if (!empty(trim($rt['teks_terjemahan']))) {
                $menuItem['label'] = $rt['teks_terjemahan'];
                return $menuItem;
            }
        }
    }
    
    // 2. Gunakan Mesin Terjemahan Otomatis Universal
    $menuItem['label'] = tr($menuItem['label']);
    
    return $menuItem;
}

/**
 * Mesin Terjemahan Otomatis Universal (Kamus Lengkap Indonesia -> English / Japanese)
 * Menerjemahkan seluruh submenu, struktur infografis, beranda, dan widget tanpa perlu setup database berualang
 */
function tr($text) {
    global $currentLang, $globalDictionary;
    if ($currentLang === 'id' || empty($text)) {
        return $text;
    }
    
    $clean = trim($text);
    if (!empty($globalDictionary[$clean])) {
        return $globalDictionary[$clean];
    }
    
    static $universalDict = [
        'en' => [
            // Menu & Submenu Navbar
            'Beranda' => 'Home',
            'Profil Desa' => 'Village Profile',
            'Sejarah & Visi Misi' => 'History, Vision & Mission',
            'Sejarah, Visi & Misi' => 'History, Vision & Mission',
            'Struktur Pemerintahan' => 'Government Structure',
            'Potensi & UMKM Desa' => 'Village Potential & MSMEs',
            'Panduan Layanan Warga' => 'Citizen Service Guide',
            'Peta & Batas Wilayah' => 'Map & Area Boundaries',
            'Statistik APBDes 2025' => '2025 APBDes Statistics',
            'Statistik APBDes 2026' => '2026 APBDes Statistics',
            'Buku Bantu Aset' => 'Asset Register Book',
            'Infografis Keuangan' => 'Financial Infographics',
            'Regulasi & Aset Desa' => 'Regulations & Assets',
            'Pelayanan & Berita' => 'Services & News',
            'Pustaka Dokumen (JDIH)' => 'Legal Repository (JDIH)',
            'Pustaka Hukum' => 'Legal Repository',
            'JDIH Desa' => 'Village Legal Repo',

            // Header, Breadcrumbs & Badges
            'Informasi & Layanan Publik' => 'Public Information & Services',
            'Portal Resmi Pemerintah Desa' => 'Official Village Government Portal',
            'Diperbarui:' => 'Updated:',
            'Senin - Jumat: 08.00 - 16.00 WIB' => 'Monday - Friday: 08.00 - 16.00 WIB',
            'Pusat Unduhan' => 'Download Center',
            'Halaman Desa Lainnya' => 'Other Village Pages',
            'Transparansi Data' => 'Data Transparency',
            'Cari Peraturan & Aset Desa?' => 'Looking for Regulations & Assets?',
            'Perdes APBDes 2026, Buku Bantu Aset 2025, dan Naskah RPJM dapat Anda telusuri secara bebas.' => 'Explore 2026 APBDes Regulations, 2025 Asset Books, and RPJM Development Plans freely.',
            'Buka Pustaka Dokumen (JDIH)' => 'Open Legal Document Repository (JDIH)',

            // Infografis Keuangan & Aset (Lengkap)
            'Komposisi Pendapatan APBDes' => 'APBDes Revenue Composition',
            'Komposisi Belanja APBDes' => 'APBDes Expenditure Composition',
            'Total: Rp 1.475.000.000 (TA 2026)' => 'Total: IDR 1,475,000,000 (FY 2026)',
            'Dana Desa (APBN)' => 'Village Fund (State Budget/APBN)',
            'Alokasi Dana Desa (ADD)' => 'Village Fund Allocation (ADD)',
            'Pendapatan Asli Desa (PADes)' => 'Village Original Revenue (PADes)',
            'Bagi Hasil Pajak & Retribusi' => 'Tax & Retribution Revenue Sharing',
            'Bantuan Provinsi & Kabupaten' => 'Provincial & Regency Aid',
            'Unduh Perdes APBDes 2026 Lengkap' => 'Download Complete 2026 APBDes Regulation',
            'Alokasi Belanja & Program Kerja 2026' => '2026 Budget Expenditure Allocation & Work Programs',
            'Penggunaan dana difokuskan untuk pembangunan infrastruktur dan UMKM' => 'Funds are prioritized for infrastructure development and MSME growth',
            'Infrastruktur' => 'Infrastructure',
            'Penyelenggaraan Pemdes' => 'Gov Administration',
            'Pemberdayaan UMKM' => 'MSME Empowerment',
            'Pembinaan Warga' => 'Civic Development',
            'Alokasi Belanja 2026' => '2026 Budget Allocation',

            // Beranda & Statistik Warga
            'Transparansi Pembendaharaan Negara & Regulasi Desa' => 'State Treasury Transparency & Village Regulations',
            'Portal Resmi Pemerintahan' => 'Official Government Portal of',
            'Mewujudkan pelayanan publik terpadu yang cepat, pengungkapan data aset dan anggaran (APBDes) yang akuntabel, serta kemudahan unduh regulasi hukum bagi seluruh warga masyarakat.' => 'Delivering integrated public services, accountable budgetary (APBDes) and asset disclosures, and accessible legislative archiving for all citizens.',
            'Pustaka Hukum (JDIH)' => 'Legal Archive (JDIH)',
            'Perdes APBDes 2026 Tersedia' => '2026 APBDes Regulation Available',
            'Laporan SILPA 2025 Terbuka' => '2025 SILPA Report Open',
            'Statistik Warga & Wilayah' => 'Demographics & Area Statistics',
            'Demografi & Angka Penting' => 'Demographics & Key Figures of',
            'Total Penduduk' => 'Total Population',
            'Kepala Keluarga (KK)' => 'Households (KK)',
            'Laki-laki' => 'Male',
            'Perempuan' => 'Female',
            'Lahan & Pertanian' => 'Farmland & Agriculture',
            '5 Dusun' => '5 Hamlets',
            'Klego, Ponggok, Soka, dst.' => 'Klego, Ponggok, Soka, etc.',
            '6 RW / 18 RT' => '6 RW / 18 RT',
            'Pembagian Wilayah' => 'Territorial Divisions',
            'Total APBDes 2026' => 'Total 2026 APBDes Budget',

            // Footer & Kontak
            'Alamat & Kontak' => 'Address & Contact',
            'Pustaka & Regulasi' => 'Library & Regulations',
            'Saluran Resmi' => 'Official Channels',
            'Peraturan & Produk Legislasi (JDIH)' => 'Regulations & Legislation (JDIH)',
            'Data Aset & SILPA 2025' => '2025 Assets & SILPA Data',
            'Dokumen RPJM Desa (6 Tahun)' => 'Village RPJM Document (6 Years)',
            'Infografis Pembendaharaan Negara' => 'State Treasury Infographics',
            'Ikuti media sosial resmi kami untuk mendapatkan pengumuman terkini, kegiatan warga, dan program pembantalan sosial.' => 'Follow our official social media channels for latest announcements, civic activities, and social programs.',
            'Hak Cipta Dilindungi Undang-Undang.' => 'All Rights Reserved.',
            'Kebijakan Privasi' => 'Privacy Policy',
            'Syarat Penggunaan' => 'Terms of Use',
            'Sistem Informasi Resmi Pemerintahan' => 'Official Government Information System',
            'Portal Informasi resmi, keterbukaan pembendaharaan keuangan negara, dan pusat dokumentasi legislasi hukum Desa Klego sebagai wujud tata kelola pemerintahan yang akuntabel.' => 'Official information portal, state treasury fiscal transparency, and legal documentation repository of Klego Village as a manifestation of accountable public governance.',

            // Tambahan Infografis & Demografi
            'Pusat Infografis Keuangan & Demografi' => 'Financial & Demographic Infographics Center',
            'Transparansi Pembendaharaan Negara & APBDes' => 'State Treasury & APBDes Transparency',
            'Penyajian data visual interaktif atas realisasi Anggaran Pendapatan dan Belanja Desa (APBDes) 2026, Laporan SILPA 2025, kekayaan aset desa, serta sebaran penduduk Desa Klego.' => 'Interactive visual presentations of the 2026 Village Budget (APBDes) realization, 2025 SILPA Reports, village asset wealth, and demographic distribution of Klego Village.',
            'Statistik Penduduk' => 'Population Statistics',
            'Pendapatan APBDes 2026' => '2026 APBDes Revenue',
            'APBN & ADD Boyolali' => 'National Budget & ADD Boyolali',
            'Belanja Desa 2026' => '2026 Village Expenditure',
            'Anggaran Berimbang' => 'Balanced Budget',
            'SILPA Akhir 2025' => '2025 Ending SILPA',
            'Laporan Audit Tersedia' => 'Audit Report Available',
            'Nilai Buku Aset 2025' => '2025 Asset Book Value',
            'Tanah Kas & Infrastruktur' => 'Village Land & Infrastructure',
            '1. Pembangunan Infrastruktur & Jalan Desa (Rp 680 Juta)' => '1. Infrastructure & Village Roads Construction (IDR 680M)',
            '2. Penyelenggaraan Pemerintahan & Pelayanan (Rp 355 Juta)' => '2. Government Administration & Public Services (IDR 355M)',
            '3. Pemberdayaan & Pelatihan UMKM Warga (Rp 320 Juta)' => '3. Community MSME Empowerment & Training (IDR 320M)',
            '4. Pembinaan Kemasyarakatan (Posyandu, PKK, Karang Taruna) (Rp 120 Juta)' => '4. Community Development (Health Clinic, PKK, Youth Club) (IDR 120M)',
            'Disetujui bersama BPD Desa Klego' => 'Approved jointly with Klego Village Consultative Council (BPD)',
            'Periksa Buku Bantu Aset & SILPA &rarr;' => 'Check Asset Register & SILPA &rarr;',
            'Data Kependudukan' => 'Population Data',
            'Sebaran Wilayah & Demografi Penduduk' => 'Regional Distribution & Demographics',
            'Berdasarkan pendataan administrasi balai desa terbaru, mencerminkan sebaran warga di 5 Dusun dan piramida usia produktif.' => 'Based on the latest village office administrative census, reflecting population distribution across 5 Hamlets and productive age pyramid.',
            'Sebaran Penduduk per Dusun' => 'Population Distribution by Hamlet',
            'Total 5 Dusun di Wilayah Desa Klego' => 'Total 5 Hamlets in Klego Village',
            'Dusun Klego' => 'Klego Hamlet',
            'Dusun Ponggok' => 'Ponggok Hamlet',
            'Dusun Soka' => 'Soka Hamlet',
            'Dusun Rejosari' => 'Rejosari Hamlet',
            '1.243 Warga (25.8%)' => '1,243 Citizens (25.8%)',
            '987 Warga (20.5%)' => '987 Citizens (20.5%)',
            '876 Warga (18.2%)' => '876 Citizens (18.2%)',
            '765 Warga (15.9%)' => '765 Citizens (15.9%)',
            'Kelompok Usia & Bonus Demografi' => 'Age Groups & Demographic Dividend',
            'Dominasi usia produktif (15 - 59 tahun)' => 'Dominance of productive working age (15 - 59 yrs)',
            'Rasio Usia Produktif' => 'Productive Age Ratio',
            '64,2% Warga Usia Produktif' => '64.2% Citizens in Productive Age',
            'ADD (Boyolali)' => 'ADD Allocation',
            'PADes Asli' => 'Village Revenue (PADes)',
            'Bagi Hasil Pajak' => 'Tax Share Revenue',
            '0-14 (Anak)' => '0-14 (Children)',
            '15-29 (Pemuda)' => '15-29 (Youth)',
            '30-44 (Dewasa Muda)' => '30-44 (Young Adults)',
            '45-59 (Dewasa)' => '45-59 (Adults)',
            '60+ (Lansia)' => '60+ (Seniors)',
            'Keterbukaan Informasi & Pembendaharaan' => 'Information Openness & Treasury Transparency',
            'Pusat Data Regulasi, Aset & Perencanaan' => 'Regulatory, Asset & Planning Data Center',
            'Infografis Keuangan' => 'Financial Infographics',
            'Demografi & Angka Penting' => 'Demographics & Key Figures of',
            'Mewujudkan pemerintahan Desa Klego yang bersih dan terbuka. Seluruh berkas Peraturan Desa, Buku Bantu Aset, Laporan SILPA, dan RPJM Desa dapat diperiksa serta diunduh secara bebas oleh masyarakat.' => 'Fostering a clean and open Klego Village administration. All Village Regulations, Asset Books, SILPA Reports, and Development Plans can be freely inspected and downloaded by citizens.',
            '11 Dokumen Tersedia' => '11 Documents Available',
            'Peraturan & Produk Legislasi Desa' => 'Village Regulations & Legislative Products',
            'Pusat informasi produk hukum Desa Klego untuk mendukung transparansi pemerintahan dan memudahkan akses masyarakat terhadap regulasi yang berlaku. Meliputi Perdes APBDes 2026, Perkades APBDes, Keputusan Kepala Desa, dan produk hukum lainnya yang diperbarui secara berkala.' => 'Legal product repository of Klego Village supporting government transparency and public access to active legislation. Includes 2026 APBDes Regulations, Village Head Decrees, and other legal instruments updated regularly.',
            'Lihat & Unduh Regulasi' => 'View & Download Regulations',
            '9 Laporan Resmi' => '9 Official Reports',
            'Data Aset & Pembendaharaan Desa' => 'Asset & Village Treasury Data',
            'Pusat informasi yang memuat berbagai data penyelenggaraan pemerintahan Desa Klego sebagai wujud transparansi dan pelayanan publik. Melalui halaman ini, masyarakat dapat mengakses data seperti inventaris aset desa, buku bantu, stock opname, laporan SILPA 2025, serta CaLK 2025.' => 'Information hub detailing Klego Village governmental operations as a commitment to transparency and public service. Citizens can access asset inventories, auxiliary ledgers, stock taking, 2025 SILPA reports, and CaLK financial disclosures.',
            'Buka Inventaris & SILPA' => 'Open Inventory & SILPA',
            'Perencanaan 6 Tahun' => '6-Year Strategic Planning',
            'RPJM Desa (Rencana Pembangunan)' => 'RPJM Desa (Development Plan)',
            'RPJM Desa merupakan dokumen perencanaan pembangunan desa untuk jangka waktu 6 (enam) tahun yang menjadi pedoman dalam penyelenggaraan pemerintahan, pelaksanaan pembangunan, dan pemberdayaan masyarakat. Disusun sebagai dasar terarah dan berkelanjutan.' => 'RPJM Desa is a 6-year development planning document that serves as the blueprint for village governance, infrastructure development, and community empowerment, designed as a directed and sustainable foundation.',
            'Unduh Naskah RPJM' => 'Download RPJM Document',
            'Statistik & Keuangan Desa' => 'Village Statistics & Finances',
            'Transparansi Pengelolaan Pembendaharaan Negara & Dana Desa' => 'Transparency in State Treasury & Village Fund Management',
            'Kami menjunjung tinggi prinsip akuntabilitas dalam penggunaan Anggaran Pendapatan dan Belanja Desa (APBDes) 2026 maupun pertanggungjawaban SILPA 2025.' => 'We uphold the principle of accountability in executing the 2026 Village Budget (APBDes) and maintaining transparent SILPA 2025 reporting.',
            '70% APBDes' => '70% of APBDes',
            'SILPA Akhir Tahun 2025' => '2025 Year-End SILPA',
            'Audit Ok' => 'Audit Verified',
            'Buka Dasbor Infografis Lengkap' => 'Open Full Infographic Dashboard',
            'Distribusi Anggaran Belanja APBDes 2026' => '2026 APBDes Expenditure Allocation Distribution',
            'Berdasarkan Perdes No. 01 Tahun 2026 Desa Klego' => 'Based on Klego Village Regulation No. 01 of 2026',
            'TA 2026' => 'FY 2026',
            'Fokus Pembangunan' => 'Development Focus',
            'Infrastruktur & Jalan (54.4%)' => 'Infrastructure & Roads (54.4%)',
            'Fokus Pemberdayaan' => 'Empowerment Focus',
            'Pelatihan UMKM (25.6%)' => 'MSME Training (25.6%)',
            'Pembangunan Infrastruktur' => 'Infrastructure Construction',
            'Penyelenggaraan Pemdes' => 'Gov Administration',
            'Pemberdayaan UMKM' => 'MSME Empowerment',
            'Pembinaan Masyarakat' => 'Civic Development',
            'Alokasi Belanja (Juta Rp)' => 'Budget Allocation (Million IDR)',
            'Keunggulan & Potensi Lokal' => 'Local Strengths & Potential',
            'Potensi Agraris & Kekuatan UMKM Warga' => 'Agrarian Potential & Community MSME Strength',
            'Desa Klego dianugerahi lahan pertanian subur dan komunitas pengrajin UMKM yang aktif menggerakkan roda ekonomi desa.' => 'Klego Village is blessed with fertile farmland and an active artisan MSME community driving the local economy.',
            'Pertanian Produktif' => 'Productive Agriculture',
            'Lahan produktif seluas 312 Ha dengan komoditas utama padi, jagung, dan kedelai berkualitas tinggi dari Boyolali.' => '312 Hectares of productive land producing premium quality rice, maize, and soybeans from Boyolali.',
            '312 Ha Lahan' => '312 Ha Farmland',
            '87 UMKM Aktif' => '87 Active MSMEs',
            'Berkembangnya usaha masyarakat bidang kerajinan anyaman bambu, batik tulis lokal, dan olahan pangan tradisional.' => 'Thriving community businesses specializing in bamboo weaving, local handcrafted batik, and traditional culinary products.',
            'Kerajinan & Pangan' => 'Crafts & Culinary',
            'Kelembagaan Solid' => 'Solid Civic Institutions',
            'Didukung 12 lembaga kemasyarakatan yang aktif: BPD, LKMD, PKK, Karang Taruna, dan Gapoktan yang tanggap.' => 'Supported by 12 active civic organizations: BPD, LKMD, PKK women association, Youth Club, and responsive farmer unions.',
            '12 Lembaga Aktif' => '12 Active Institutions',
            'Infrastruktur Maju' => 'Advanced Infrastructure',
            'Pemerataan perbaikan jalan antar dusun, drainase pertanian, dan penerangan jalan umum dengan pengawasan warga.' => 'Equitable road upgrades across hamlets, agricultural drainage, and solar street lighting monitored actively by citizens.',
            'Sesuai RPJMDes' => 'Aligned with RPJMDes',
            'Informasi & Kegiatan Warga' => 'Information & Community Activities',
            'Berita Terkini & Agenda Desa' => 'Latest News & Village Agenda',
            'Lihat Semua Berita' => 'View All News',
            'Berita' => 'News',
            'Agenda Kegiatan Desa' => 'Village Activity Agenda',
            'Butuh Bantuan Layanan?' => 'Need Service Assistance?',
            'Pengurusan Surat Keterangan Domisili, Pengantar KK/KTP, dan SKTM diselesaikan dalam 1 Hari Kerja tanpa dipungut biaya (Gratis).' => 'Processing of Domicile Certificates, KK/KTP referral forms, and SKTM is completed within 1 Business Day completely Free of Charge.',
            'Lihat Panduan & Syarat' => 'View Guide & Requirements',
            'Layanan Balai Desa' => 'Village Office Services',
            'Pelayanan Administrasi Cepat & Gratis' => 'Fast & Free Administrative Services',
            'Surat Keterangan Domisili' => 'Certificate of Domicile',
            'Selesai 1 Hari Kerja' => 'Ready in 1 Business Day',
            'Syarat: Fotokopi KTP, KK, dan Pengantar Ketua RT/RW.' => 'Requirements: Copy of KTP, KK, and RT/RW Referral Letter.',
            'Pengantar KTP & KK' => 'ID Card & Family Card Referral',
            'Syarat: Blanko Formulir F1.01 atau Kartu Keluarga (KK) Lama.' => 'Requirements: Form F1.01 or Previous Family Card (KK).',
            'Surat Ket. Tidak Mampu' => 'Certificate of Underprivileged (SKTM)',
            'Syarat: Fotokopi KTP, KK, dan Pengantar Resmi RT/RW.' => 'Requirements: Copy of KTP, KK, and Official RT/RW Referral.',
            'Surat Keterangan Usaha (SKU)' => 'Business Ownership Certificate (SKU)',
            'Syarat: Fotokopi KTP, KK, Pengantar RT/RW & Bukti Usaha.' => 'Requirements: Copy of KTP, KK, RT/RW Referral & Proof of Business.',
            'WebGIS Peta & Wilayah' => 'WebGIS Maps & Territory',
            'Data & Transparansi' => 'Data & Transparency',
            'Data Pertanian Desa' => 'Village Agricultural Data',
            'Data Stunting Balita' => 'Toddler Stunting Data',
            'Direktori & Potensi UMKM' => 'MSME Directory & Potential',
            'Infografis APBDes 2026' => 'APBDes 2026 Infographics',
            'Perbandingan Pendapatan vs Belanja' => 'Revenue vs Expenditure Comparison',
            'Informasi Pembiayaan Desa 2026' => '2026 Village Financing Information'
        ],
        'ja' => [
            // Menu & Submenu Navbar
            'Beranda' => 'ホーム',
            'Profil Desa' => '村の概要',
            'Sejarah & Visi Misi' => '村の歴史・ビジョン・使命',
            'Sejarah, Visi & Misi' => '村の歴史・ビジョン・使命',
            'Struktur Pemerintahan' => '行政機構・組織図',
            'Potensi & UMKM Desa' => '村の特産品・中小企業 (UMKM)',
            'Panduan Layanan Warga' => '住民サービス窓口・ご案内',
            'Peta & Batas Wilayah' => '村の地図・区域界',
            'Statistik APBDes 2025' => '2025年度予算統計',
            'Statistik APBDes 2026' => '2026年度予算統計',
            'Buku Bantu Aset' => '村資産登録帳簿',
            'Infografis Keuangan' => '財務インフォグラフィック',
            'Regulasi & Aset Desa' => '法規と村の資産',
            'Pelayanan & Berita' => '行政サービス・ニュース',
            'Pustaka Dokumen (JDIH)' => '法規ドキュメントアーカイブ (JDIH)',
            'Pustaka Hukum' => '法規アーカイブ',
            'JDIH Desa' => '村法規アーカイブ',

            // Header, Breadcrumbs & Badges
            'Informasi & Layanan Publik' => '公共情報と行政サービス',
            'Portal Resmi Pemerintah Desa' => '村政府公式ポータル',
            'Diperbarui:' => '更新日:',
            'Senin - Jumat: 08.00 - 16.00 WIB' => '月曜〜金曜: 08.00 - 16.00 WIB',
            'Pusat Unduhan' => 'ダウンロードセンター',
            'Halaman Desa Lainnya' => 'その他の村案内ページ',
            'Transparansi Data' => 'データ透明性報告',
            'Cari Peraturan & Aset Desa?' => '条例や資産報告をお探しですか？',
            'Perdes APBDes 2026, Buku Bantu Aset 2025, dan Naskah RPJM dapat Anda telusuri secara bebas.' => '2026年度予算条例、2025年度資産帳簿、RPJM発展計画などを自由にご覧いただけます。',
            'Buka Pustaka Dokumen (JDIH)' => '法規ドキュメントアーカイブ (JDIH) を開く',

            // Infografis Keuangan & Aset (Lengkap)
            'Komposisi Pendapatan APBDes' => '歳入構成比グラフィック',
            'Komposisi Belanja APBDes' => '歳出構成比グラフィック',
            'Total: Rp 1.475.000.000 (TA 2026)' => '総額: 14億7,500万ルピア (2026年度)',
            'Dana Desa (APBN)' => '村主導資金 (国費/APBN)',
            'Alokasi Dana Desa (ADD)' => '村資金割当て (県/ADD)',
            'Pendapatan Asli Desa (PADes)' => '村独自収益 (PADes)',
            'Bagi Hasil Pajak & Retribusi' => '税収・手数料還元金',
            'Bantuan Provinsi & Kabupaten' => '州・県からの開発補助金',
            'Unduh Perdes APBDes 2026 Lengkap' => '2026年 村予算条例完全版をダウンロード',
            'Alokasi Belanja & Program Kerja 2026' => '2026年度 歳出配分・事業プログラム',
            'Penggunaan dana difokuskan untuk pembangunan infrastruktur dan UMKM' => '資金の運用はインフラ整備および地域中小企業 (UMKM) の発展に優先して充てられています',
            'Infrastruktur' => 'インフラ整備',
            'Penyelenggaraan Pemdes' => '行政運営',
            'Pemberdayaan UMKM' => 'UMKM支援',
            'Pembinaan Warga' => '市民育成',
            'Alokasi Belanja 2026' => '2026年度予算配分',

            // Beranda & Statistik Warga
            'Transparansi Pembendaharaan Negara & Regulasi Desa' => '国家財政の透明性と村の法規',
            'Portal Resmi Pemerintahan' => '公式行政ポータル',
            'Mewujudkan pelayanan publik terpadu yang cepat, pengungkapan data aset dan anggaran (APBDes) yang akuntabel, serta kemudahan unduh regulasi hukum bagi seluruh warga masyarakat.' => '迅速な統合行政サービス、信頼できる村の資産・予算（APBDes）データの公開、そして全住民向けの法規ドキュメントへの簡単なアクセスを実現します。',
            'Pustaka Hukum (JDIH)' => '法規アーカイブ (JDIH)',
            'Perdes APBDes 2026 Tersedia' => '2026年度予算条例 公開中',
            'Laporan SILPA 2025 Terbuka' => '2025年度決算報告 公開中',
            'Statistik Warga & Wilayah' => '人口統計と地域データ',
            'Demografi & Angka Penting' => '人口統計と重要指標:',
            'Total Penduduk' => '総人口',
            'Kepala Keluarga (KK)' => '世帯数 (KK)',
            'Laki-laki' => '男性',
            'Perempuan' => '女性',
            'Lahan & Pertanian' => '農地および緑地',
            '5 Dusun' => '5つの集落',
            'Klego, Ponggok, Soka, dst.' => 'クレゴ、ポンゴック、ソカ 等',
            '6 RW / 18 RT' => '6 RW / 18 RT (町内会)',
            'Pembagian Wilayah' => '行政区画構成',
            'Total APBDes 2026' => '2026年度 村予算総額',

            // Footer & Kontak
            'Alamat & Kontak' => '所在地と連絡先',
            'Pustaka & Regulasi' => 'アーカイブと法規',
            'Saluran Resmi' => '公式ソーシャルメディア',
            'Peraturan & Produk Legislasi (JDIH)' => '条例および法規製品 (JDIH)',
            'Data Aset & SILPA 2025' => '2025年度 資産・予算剰余金データ',
            'Dokumen RPJM Desa (6 Tahun)' => '中長期村落発展計画 (RPJM 6ヵ年)',
            'Infografis Pembendaharaan Negara' => '国家財務・村予算インフォグラフィック',
            'Ikuti media sosial resmi kami untuk mendapatkan pengumuman terkini, kegiatan warga, dan program pembantalan sosial.' => '最新のアナウンス、市民活動、社会支援プログラムなどの情報は、村の公式ソーシャルメディアをご覧ください。',
            'Hak Cipta Dilindungi Undang-Undang.' => '全著作権所有。',
            'Kebijakan Privasi' => 'プライバシーポリシー',
            'Syarat Penggunaan' => '利用規約',
            'Sistem Informasi Resmi Pemerintahan' => '公式政府情報システム',
            'Portal Informasi resmi, keterbukaan pembendaharaan keuangan negara, dan pusat dokumentasi legislasi hukum Desa Klego sebagai wujud tata kelola pemerintahan yang akuntabel.' => '信頼できる行政ガバナンスの表れとしてのクレゴ村公式情報ポータル、国庫財政透明性報告、及び法規ドキュメントセンター。',

            // Tambahan Infografis & Demografi
            'Pusat Infografis Keuangan & Demografi' => '財務及び人口統計インフォグラフィックセンター',
            'Transparansi Pembendaharaan Negara & APBDes' => '国庫財政及び村落予算 (APBDes) の透明性',
            'Penyajian data visual interaktif atas realisasi Anggaran Pendapatan dan Belanja Desa (APBDes) 2026, Laporan SILPA 2025, kekayaan aset desa, serta sebaran penduduk Desa Klego.' => '2026年度 村落歳入歳出予算 (APBDes) の執行状況、2025年度 決算剰余金 (SILPA) 報告、村保有資産、およびクレゴ村の人口分布に関する対話型ビジュアルデータ公開。',
            'Statistik Penduduk' => '人口統計データ',
            'Pendapatan APBDes 2026' => '2026年度 村落歳入総額',
            'APBN & ADD Boyolali' => '国家予算および県村費割当て (ADD)',
            'Belanja Desa 2026' => '2026年度 村落歳出総額',
            'Anggaran Berimbang' => '均衡予算構成',
            'SILPA Akhir 2025' => '2025年度 決算剰余金 (SILPA)',
            'Laporan Audit Tersedia' => '監査報告書 公開済',
            'Nilai Buku Aset 2025' => '2025年度 資産帳簿評価額',
            'Tanah Kas & Infrastruktur' => '村有土地及び公共インフラ',
            '1. Pembangunan Infrastruktur & Jalan Desa (Rp 680 Juta)' => '1. インフラ及び村道整備事業 (6億8,000万ルピア)',
            '2. Penyelenggaraan Pemerintahan & Pelayanan (Rp 355 Juta)' => '2. 村政運営及び公共サービス改善 (3億5,500万ルピア)',
            '3. Pemberdayaan & Pelatihan UMKM Warga (Rp 320 Juta)' => '3. 地域中小企業 (UMKM) 支援・職業訓練 (3億2,000万ルピア)',
            '4. Pembinaan Kemasyarakatan (Posyandu, PKK, Karang Taruna) (Rp 120 Juta)' => '4. コミュニティ育成 (保健所・婦人会・青年団等) (1億2,000万ルピア)',
            'Disetujui bersama BPD Desa Klego' => 'クレゴ村評議会 (BPD) との合意承認済',
            'Periksa Buku Bantu Aset & SILPA &rarr;' => '資産台帳および決算報告を確認する &rarr;',
            'Data Kependudukan' => '住民・人口統計データ',
            'Sebaran Wilayah & Demografi Penduduk' => '地域分布およびデモグラフィクス構成',
            'Berdasarkan pendataan administrasi balai desa terbaru, mencerminkan sebaran warga di 5 Dusun dan piramida usia produktif.' => '村役場の最新行政住民登録データに基づき、5つの集落への居住分布と労働生産年齢層ピラミッドを正確に反映しています。',
            'Sebaran Penduduk per Dusun' => '集落ごとの人口分布',
            'Total 5 Dusun di Wilayah Desa Klego' => 'クレゴ村全域内 5つの集落',
            'Dusun Klego' => 'クレゴ集落',
            'Dusun Ponggok' => 'ポンゴック集落',
            'Dusun Soka' => 'ソカ集落',
            'Dusun Rejosari' => 'レヨサリ集落',
            '1.243 Warga (25.8%)' => '1,243 名 (25.8%)',
            '987 Warga (20.5%)' => '987 名 (20.5%)',
            '876 Warga (18.2%)' => '876 名 (18.2%)',
            '765 Warga (15.9%)' => '765 名 (15.9%)',
            'Kelompok Usia & Bonus Demografi' => '年齢構成と人口動態の強み',
            'Dominasi usia produktif (15 - 59 tahun)' => '高い生産年齢人口比率 (15歳〜59歳)',
            'Rasio Usia Produktif' => '生産年齢人口比率',
            '64,2% Warga Usia Produktif' => '全住民の 64.2% が生産年齢層',
            'ADD (Boyolali)' => '県村費割当 (ADD)',
            'PADes Asli' => '村独自収益 (PADes)',
            'Bagi Hasil Pajak' => '税・手数料還元金',
            '0-14 (Anak)' => '0〜14歳 (児童)',
            '15-29 (Pemuda)' => '15〜29歳 (青年)',
            '30-44 (Dewasa Muda)' => '30〜44歳 (壮年前期)',
            '45-59 (Dewasa)' => '45〜59歳 (壮年後期)',
            '60+ (Lansia)' => '60歳以上 (高齢者)',
            'Keterbukaan Informasi & Pembendaharaan' => '情報開示と財政の透明性',
            'Pusat Data Regulasi, Aset & Perencanaan' => '法規、資産及び村落発展計画データセンター',
            'Infografis Keuangan' => '財務インフォグラフィック',
            'Demografi & Angka Penting' => '人口統計と重要指標:',
            'Mewujudkan pemerintahan Desa Klego yang bersih dan terbuka. Seluruh berkas Peraturan Desa, Buku Bantu Aset, Laporan SILPA, dan RPJM Desa dapat diperiksa serta diunduh secara bebas oleh masyarakat.' => 'クリーンで開かれたクレゴ村行政を実現します。村の全ての条例、資産台帳、決算剰余金報告、中長期発展計画書を全住民が自由に閲覧・ダウンロード可能です。',
            '11 Dokumen Tersedia' => '11件の公文書 公開中',
            'Peraturan & Produk Legislasi Desa' => '村の条例及び法規ドキュメント',
            'Pusat informasi produk hukum Desa Klego untuk mendukung transparansi pemerintahan dan memudahkan akses masyarakat terhadap regulasi yang berlaku. Meliputi Perdes APBDes 2026, Perkades APBDes, Keputusan Kepala Desa, dan produk hukum lainnya yang diperbarui secara berkala.' => '行政の透明性を支持し、有効な法規への市民のアクセスを容易にするためのクレゴ村法規情報センター。定期的に更新される2026年度予算条例、村長決定等を含みます。',
            'Lihat & Unduh Regulasi' => '条例の閲覧とダウンロード',
            '9 Laporan Resmi' => '9件の公式財務報告',
            'Data Aset & Pembendaharaan Desa' => '資産および村庫財政データ',
            'Pusat informasi yang memuat berbagai data penyelenggaraan pemerintahan Desa Klego sebagai wujud transparansi dan pelayanan publik. Melalui halaman ini, masyarakat dapat mengakses data seperti inventaris aset desa, buku bantu, stock opname, laporan SILPA 2025, serta CaLK 2025.' => '行政透明性と公共サービスの証として、クレゴ村の様々な行政運営データを集約した情報ハブ。村保有資産インベントリ、補助台帳、在庫確認書、2025年度決算報告や財務説明書にアクセスできます。',
            'Buka Inventaris & SILPA' => '資産台帳・決算報告を見る',
            'Perencanaan 6 Tahun' => '6ヵ年 中長期発展計画',
            'RPJM Desa (Rencana Pembangunan)' => '中長期村落発展計画 (RPJM Desa)',
            'RPJM Desa merupakan dokumen perencanaan pembangunan desa untuk jangka waktu 6 (enam) tahun yang menjadi pedoman dalam penyelenggaraan pemerintahan, pelaksanaan pembangunan, dan pemberdayaan masyarakat. Disusun sebagai dasar terarah dan berkelanjutan.' => 'RPJM Desa は、6年間の村落発展計画を定めた文書であり、行政運営、インフラ建設、地域コミュニティ強化における長期目標および持続可能な開発の指針となるものです。',
            'Unduh Naskah RPJM' => '計画書 (RPJM) をダウンロード',
            'Statistik & Keuangan Desa' => '村の統計および財政',
            'Transparansi Pengelolaan Pembendaharaan Negara & Dana Desa' => '国庫財政および村落交付金の透明な運用',
            'Kami menjunjung tinggi prinsip akuntabilitas dalam penggunaan Anggaran Pendapatan dan Belanja Desa (APBDes) 2026 maupun pertanggungjawaban SILPA 2025.' => '私たちは2026年度 村落歳入歳出予算 (APBDes) の執行及び2025年度決算剰余金の報告において、高い説明責任とアカウンタビリティを貫徹します。',
            '70% APBDes' => '村予算の 70%',
            'SILPA Akhir Tahun 2025' => '2025年度 決算剰余金 (SILPA)',
            'Audit Ok' => '監査承認済',
            'Buka Dasbor Infografis Lengkap' => '詳細なインフォグラフィックを見る',
            'Distribusi Anggaran Belanja APBDes 2026' => '2026年度 村落予算・歳出配分構成',
            'Berdasarkan Perdes No. 01 Tahun 2026 Desa Klego' => 'クレゴ村 2026年第01号 村条例に基づく',
            'TA 2026' => '2026年度',
            'Fokus Pembangunan' => 'インフラ整備事業',
            'Infrastruktur & Jalan (54.4%)' => 'インフラ及び村道整備 (54.4%)',
            'Fokus Pemberdayaan' => 'コミュニティ支援事業',
            'Pelatihan UMKM (25.6%)' => '中小企業支援・職業訓練 (25.6%)',
            'Pembangunan Infrastruktur' => 'インフラ建設整備',
            'Penyelenggaraan Pemdes' => '村政運営及び公共サービス',
            'Pemberdayaan UMKM' => '地域中小企業 (UMKM) 支援',
            'Pembinaan Masyarakat' => '市民団体・コミュニティ育成',
            'Alokasi Belanja (Juta Rp)' => '予算割当 (単位: 100万ルピア)',
            'Keunggulan & Potensi Lokal' => '地域の強みと特産資源',
            'Potensi Agraris & Kekuatan UMKM Warga' => '豊かな農業資源と活力ある地域中小企業 (UMKM)',
            'Desa Klego dianugerahi lahan pertanian subur dan komunitas pengrajin UMKM yang aktif menggerakkan roda ekonomi desa.' => 'クレゴ村は肥沃な農地と、地域経済の牽引力である熱心な中小企業職人コミュニティに恵まれています。',
            'Pertanian Produktif' => '生産性の高い農業',
            'Lahan produktif seluas 312 Ha dengan komoditas utama padi, jagung, dan kedelai berkualitas tinggi dari Boyolali.' => 'ボヨラリ県産 高品質な米、トウモロコシ、大豆を生産する312ヘクタールの広大で肥沃な農地。',
            '312 Ha Lahan' => '312ヘクタール 農地',
            '87 UMKM Aktif' => '87の活動的な中小企業',
            'Berkembangnya usaha masyarakat bidang kerajinan anyaman bambu, batik tulis lokal, dan olahan pangan tradisional.' => '竹細工、地元伝統手染めバティック、伝統的な食品加工分野における市民ビジネスの著しい発展。',
            'Kerajinan & Pangan' => '工芸品および伝統食品',
            'Kelembagaan Solid' => '確固たる市民統括組織',
            'Didukung 12 lembaga kemasyarakatan yang aktif: BPD, LKMD, PKK, Karang Taruna, dan Gapoktan yang tanggap.' => '村評議会 (BPD)、婦人会 (PKK)、青年団、各種農協など、12の活動的で強力な市民統括団体による万全なサポート体制。',
            '12 Lembaga Aktif' => '12の市民統括組織',
            'Infrastruktur Maju' => '先進的な農村インフラ',
            'Pemerataan perbaikan jalan antar dusun, drainase pertanian, dan penerangan jalan umum dengan pengawasan warga.' => '市民の積極的な監視と連携に基づいた集落間道路の平準化舗装、農業排水設備、及び太陽光外灯の充実した整備。',
            'Sesuai RPJMDes' => '村の発展計画 (RPJM) に準拠',
            'Informasi & Kegiatan Warga' => '村の最新情報と行事案内',
            'Berita Terkini & Agenda Desa' => '最新ニュースと村の公式行事日程',
            'Lihat Semua Berita' => 'すべてのニュースを見る',
            'Berita' => 'ニュース',
            'Agenda Kegiatan Desa' => '村の行事予定スケジュール',
            'Butuh Bantuan Layanan?' => '行政手続のサポートをお探しですか?',
            'Pengurusan Surat Keterangan Domisili, Pengantar KK/KTP, dan SKTM diselesaikan dalam 1 Hari Kerja tanpa dipungut biaya (Gratis).' => '居住証明書、身分証明書/世帯記載紹介書、低所得支援証明 (SKTM) などの発行手続きは、最短1営業日以内・完全無料でおこなえます。',
            'Lihat Panduan & Syarat' => '申請手順と必要書類を見る',
            'Layanan Balai Desa' => '村役場 統合窓口サービス',
            'Pelayanan Administrasi Cepat & Gratis' => '迅速＆完全無料の統合行政サービス',
            'Surat Keterangan Domisili' => '居住地証明書の発行',
            'Selesai 1 Hari Kerja' => '最短 1 営業日スピード発行',
            'Syarat: Fotokopi KTP, KK, dan Pengantar Ketua RT/RW.' => '必要書類: KTP、世帯カード(KK)写し、町内会長 (RT/RW) の紹介状。',
            'Pengantar KTP & KK' => '身分証明書 (KTP/KK) 発行手続',
            'Syarat: Blanko Formulir F1.01 atau Kartu Keluarga (KK) Lama.' => '必要書類: 申請書フォーム F1.01 または 従来の世帯カード (KK)。',
            'Surat Ket. Tidak Mampu' => '福祉支援証明 (SKTM) 発行',
            'Syarat: Fotokopi KTP, KK, dan Pengantar Resmi RT/RW.' => '必要書類: KTP、世帯カード(KK)写し、および公式な町内会 (RT/RW) の推薦状。',
            'Surat Keterangan Usaha (SKU)' => '自営業・商工営業証明書 (SKU)',
            'Syarat: Fotokopi KTP, KK, Pengantar RT/RW & Bukti Usaha.' => '必要書類: KTP、世帯カード写し、RT/RW 推薦状 および 事業実態証明書類。',
            'WebGIS Peta & Wilayah' => 'WebGIS地図と区域界',
            'Data & Transparansi' => 'データと透明性',
            'Data Pertanian Desa' => '村の農業データ',
            'Data Stunting Balita' => '幼児発育不全モニタリング',
            'Direktori & Potensi UMKM' => '中小企業 (UMKM) カタログ',
            'Infografis APBDes 2026' => '2026年度村予算インフォグラフィック',
            'Perbandingan Pendapatan vs Belanja' => '歳入と歳出の比較分析',
            'Informasi Pembiayaan Desa 2026' => '2026年度村財政調達情報'
        ]
    ];

    if (isset($universalDict[$currentLang][$clean])) {
        return $universalDict[$currentLang][$clean];
    }

    return $text;
}
?>
