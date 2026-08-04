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
 * Fungsi helper untuk menerjemahkan menu navbar dinamis
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
    
    // 2. Fallback pencarian dari kamus nama menu umum (Beranda, Profil Desa, dsb)
    $labelMap = [
        'Beranda' => 'beranda',
        'Profil Desa' => 'profil_desa',
        'Infografis Keuangan' => 'infografis_keuangan',
        'Regulasi & Aset Desa' => 'regulasi_aset',
        'Pelayanan & Berita' => 'pelayanan_berita',
        'Pustaka Dokumen (JDIH)' => 'pustaka_hukum'
    ];
    
    $rawLabel = trim($menuItem['label'] ?? '');
    if (isset($labelMap[$rawLabel]) && isset($globalDictionary[$labelMap[$rawLabel]])) {
        $menuItem['label'] = $globalDictionary[$labelMap[$rawLabel]];
    }
    
    return $menuItem;
}
?>
