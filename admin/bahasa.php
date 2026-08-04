<?php
session_start();
include '../config/database.php';
include '../config/lang_helper.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";
$tab = $_GET['tab'] ?? 'halaman';

// 1. TAMBAH BAHASA BARU
if (isset($_POST['tambah_bahasa'])) {
    $kode = strtolower(trim(mysqli_real_escape_string($conn, $_POST['kode'])));
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $bendera = mysqli_real_escape_string($conn, $_POST['bendera'] ?: '🏳️');
    
    if (!empty($kode) && !empty($nama)) {
        $ins = mysqli_query($conn, "INSERT INTO master_bahasa (kode, nama, bendera, is_default, status) VALUES ('$kode', '$nama', '$bendera', 0, 1) ON DUPLICATE KEY UPDATE nama='$nama', bendera='$bendera', status=1");
        if ($ins) {
            $msg = "Bahasa baru ($nama - $kode) berhasil ditambahkan ke dalam sistem translasi!";
        } else {
            $msg = "Gagal menambahkan bahasa: " . mysqli_error($conn);
        }
    }
}

// 2. HAPUS BAHASA (Kecuali ID)
if (isset($_GET['hapus_bahasa'])) {
    $kodeHapus = mysqli_real_escape_string($conn, $_GET['hapus_bahasa']);
    if ($kodeHapus !== 'id') {
        mysqli_query($conn, "DELETE FROM master_bahasa WHERE kode = '$kodeHapus'");
        mysqli_query($conn, "DELETE FROM terjemahan_konten WHERE kode_bahasa = '$kodeHapus'");
        header("Location: bahasa.php?tab=master&msg=" . urlencode("Bahasa dan terjemahannya berhasil dihapus."));
        exit;
    }
}

// 3. SIMPAN TERJEMAHAN STRING MASSAL (UI / INFOGRAFIS / DOKUMEN)
if (isset($_POST['simpan_kamus'])) {
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    if (!empty($_POST['trans'])) {
        foreach ($_POST['trans'] as $kunci => $langs) {
            $cleanKunci = mysqli_real_escape_string($conn, $kunci);
            foreach ($langs as $kodeLang => $teks) {
                $cleanLang = mysqli_real_escape_string($conn, $kodeLang);
                $cleanTeks = mysqli_real_escape_string($conn, $teks);
                
                if (!empty($cleanTeks)) {
                    mysqli_query($conn, "INSERT INTO terjemahan_konten (kategori, referensi_id, kunci, kode_bahasa, teks_terjemahan) 
                    VALUES ('$kategori', 0, '$cleanKunci', '$cleanLang', '$cleanTeks') 
                    ON DUPLICATE KEY UPDATE teks_terjemahan = '$cleanTeks'");
                } else {
                    mysqli_query($conn, "DELETE FROM terjemahan_konten WHERE kategori='$kategori' AND referensi_id=0 AND kunci='$cleanKunci' AND kode_bahasa='$cleanLang'");
                }
            }
        }
    }
    $msg = "Kamus terjemahan ($kategori) berhasil diperbarui secara menyeluruh!";
}

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

// Ambil semua bahasa terdaftar
$listLanguages = [];
$resL = mysqli_query($conn, "SELECT * FROM master_bahasa ORDER BY is_default DESC, kode ASC");
while ($r = mysqli_fetch_assoc($resL)) {
    $listLanguages[$r['kode']] = $r;
}

// Ambil daftar Halaman Statis untuk dicek status translasinya
$pages = [];
$resP = @mysqli_query($conn, "SELECT id, judul, slug, updated_at FROM halaman_statis ORDER BY id DESC");
if ($resP) {
    while ($p = mysqli_fetch_assoc($resP)) {
        $transStatus = [];
        $pId = (int)$p['id'];
        $resTS = mysqli_query($conn, "SELECT DISTINCT kode_bahasa FROM terjemahan_konten WHERE kategori = 'halaman' AND referensi_id = $pId AND kunci = 'judul' AND teks_terjemahan != ''");
        while ($tRow = mysqli_fetch_assoc($resTS)) {
            $transStatus[] = $tRow['kode_bahasa'];
        }
        $p['translated_languages'] = $transStatus;
        $pages[] = $p;
    }
}

// Ambil daftar Berita & Artikel untuk dicek status translasinya
$newsList = [];
$resNews = @mysqli_query($conn, "SELECT id, judul, tanggal FROM berita ORDER BY id DESC LIMIT 20");
if ($resNews) {
    while ($nw = mysqli_fetch_assoc($resNews)) {
        $transStatus = [];
        $nId = (int)$nw['id'];
        $resNS = mysqli_query($conn, "SELECT DISTINCT kode_bahasa FROM terjemahan_konten WHERE kategori = 'berita' AND referensi_id = $nId AND kunci = 'judul' AND teks_terjemahan != ''");
        while ($nRow = mysqli_fetch_assoc($resNS)) {
            $transStatus[] = $nRow['kode_bahasa'];
        }
        $nw['translated_languages'] = $transStatus;
        $newsList[] = $nw;
    }
}

// Ambil seluruh data kamus (ui, infografis, dokumen) dari database saat ini
$dbTransData = [];
$resAllDB = mysqli_query($conn, "SELECT kategori, kunci, kode_bahasa, teks_terjemahan FROM terjemahan_konten WHERE referensi_id = 0");
while ($rall = mysqli_fetch_assoc($resAllDB)) {
    $dbTransData[$rall['kategori']][$rall['kunci']][$rall['kode_bahasa']] = $rall['teks_terjemahan'];
}

// Definisi kamus infografis (Label & Judul)
$infocabKeys = [
    'info_judul' => 'Judul Halaman: Transparansi Anggaran & Keuangan Desa',
    'info_sub' => 'Subjudul Laporan Keuangan Real-time APBDes & Aset',
    'pendapatan_apbdes' => 'KPI: Pendapatan APBDes 2026',
    'belanja_apbdes' => 'KPI: Belanja APBDes 2026',
    'silpa_2025' => 'KPI: SILPA APBDes 2025',
    'nilai_aset' => 'KPI: Nilai Buku Aset Desa',
    'komposisi_apbdes' => 'Grafik Komposisi APBDes TA 2026',
    'sebaran_warga' => 'Grafik Sebaran Warga per Dusun (4.823 Jiwa)'
];

// Definisi kamus pustaka dokumen JDIH
$docKeys = [
    'dok_judul' => 'Judul Halaman: Pustaka Dokumen & Aset Desa',
    'dok_sub' => 'Subjudul Arsip Regulasi 2026, SILPA, dan RPJM',
    'kat_semua' => 'Tombol Filter: Semua Kategori',
    'kat_legislasi' => 'Tombol Filter: Peraturan & Legislasi',
    'kat_aset' => 'Tombol Filter: Aset & Pembendaharaan',
    'kat_rpjm' => 'Tombol Filter: Rencana Pembangunan (RPJM)',
    'col_judul' => 'Header Tabel: Judul Dokumen & Keterangan',
    'col_kategori' => 'Header Tabel: Kategori',
    'col_tahun' => 'Header Tabel: Tahun / Tanggal Upload',
    'col_aksi' => 'Header Tabel: Aksi Unduhan'
];

// Definisi kamus UI & Menu Navbar
$uiKeys = [
    'beranda' => 'Menu: Beranda',
    'profil_desa' => 'Menu: Profil Desa',
    'infografis_keuangan' => 'Menu: Infografis Keuangan',
    'regulasi_aset' => 'Menu: Regulasi & Aset Desa',
    'pelayanan_berita' => 'Menu: Pelayanan & Berita',
    'pustaka_hukum' => 'Menu: Pustaka Dokumen (JDIH)',
    'pusat_unduhan' => 'Tombol Navbar: Pusat Unduhan',
    'kembali_beranda' => 'Tombol: Kembali ke Beranda',
    'unduh_file' => 'Tombol: Unduh Berkas',
    'pratinjau' => 'Tombol: Pratinjau',
    'selamat_datang' => 'Hero Banner: Selamat Datang di Portal Resmi...',
    'baca_selengkapnya' => 'Tombol Berita: Baca Selengkapnya',
    'dipublikasikan' => 'Label: Dipublikasikan pada',
    'bagikan' => 'Tombol: Bagikan Berita'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Terjemahan Menyeluruh (Human Translator Hub) - Admin Klego</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <!-- HEADER -->
    <header class="bg-[#165f36] text-white shadow-lg border-b-4 border-amber-500 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <a href="index.php" class="w-10 h-10 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors shadow">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight flex items-center gap-2">
                        <i class="fa-solid fa-language text-amber-300 text-xl"></i>
                        <span>Pusat Terjemahan Menyeluruh (Total Localization Hub)</span>
                    </h1>
                    <p class="text-[11px] text-emerald-200">Terjemahkan Halaman Statis, Berita Desa, Infografis Anggaran, dan Pustaka JDIH secara mudah</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="bg-emerald-900/80 border border-emerald-700 text-amber-300 text-xs font-bold px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow">
                    <i class="fa-solid fa-globe"></i> <?= count($listLanguages) ?> Bahasa Aktif
                </span>
                <a href="../index.php?lang=en" target="_blank" class="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow transition-transform transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-external-link-alt"></i> Web (English)
                </a>
                <a href="../index.php?lang=ja" target="_blank" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow transition-transform transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-external-link-alt"></i> Web (日本語)
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        <?php if (!empty($msg)): ?>
            <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3.5 rounded-2xl mb-6 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                <span class="text-sm font-semibold"><?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>

        <!-- TABS KATEGORI TRANSLATION LENGKAP -->
        <div class="flex flex-wrap items-center gap-2 mb-8 border-b-2 border-slate-200 pb-3">
            <a href="bahasa.php?tab=halaman" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all <?= $tab == 'halaman' ? 'bg-[#165f36] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-300' ?>">
                <i class="fa-solid fa-file-lines <?= $tab == 'halaman' ? 'text-amber-300' : 'text-emerald-700' ?>"></i>
                <span>1. Halaman Statis</span>
            </a>
            <a href="bahasa.php?tab=berita" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all <?= $tab == 'berita' ? 'bg-[#165f36] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-300' ?>">
                <i class="fa-solid fa-newspaper <?= $tab == 'berita' ? 'text-amber-300' : 'text-blue-600' ?>"></i>
                <span>2. Berita Desa</span>
            </a>
            <a href="bahasa.php?tab=infografis" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all <?= $tab == 'infografis' ? 'bg-[#165f36] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-300' ?>">
                <i class="fa-solid fa-chart-column <?= $tab == 'infografis' ? 'text-amber-300' : 'text-teal-700' ?>"></i>
                <span>3. Infografis Keuangan</span>
            </a>
            <a href="bahasa.php?tab=dokumen" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all <?= $tab == 'dokumen' ? 'bg-[#165f36] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-300' ?>">
                <i class="fa-solid fa-file-invoice <?= $tab == 'dokumen' ? 'text-amber-300' : 'text-amber-700' ?>"></i>
                <span>4. Pustaka JDIH & Aset</span>
            </a>
            <a href="bahasa.php?tab=ui" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all <?= $tab == 'ui' ? 'bg-[#165f36] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-300' ?>">
                <i class="fa-solid fa-comment-dots <?= $tab == 'ui' ? 'text-amber-300' : 'text-purple-600' ?>"></i>
                <span>5. Menu Navbar & UI</span>
            </a>
            <a href="bahasa.php?tab=master" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all <?= $tab == 'master' ? 'bg-[#165f36] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-300' ?>">
                <i class="fa-solid fa-gear <?= $tab == 'master' ? 'text-amber-300' : 'text-slate-600' ?>"></i>
                <span>6. Kelola Daftar Bahasa</span>
            </a>
        </div>

        <!-- ================= TAB 1: HALAMAN STATIS ================= -->
        <?php if ($tab == 'halaman'): ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h2 class="font-bold text-xl text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-file-pen text-emerald-700"></i> Studio Terjemahan Halaman Statis
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Pilih tombol bahasa asing untuk masuk ke <b>Studio Terjemahan Berdampingan (Side-by-Side Editor)</b>.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <?php foreach ($pages as $p): ?>
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-emerald-300 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-emerald-800 uppercase bg-emerald-100 px-2.5 py-0.5 rounded">Halaman Statis</span>
                                <h3 class="font-extrabold text-slate-900 text-base mt-1.5"><?= htmlspecialchars($p['judul']) ?></h3>
                                <span class="text-xs font-mono text-slate-400">slug: <?= htmlspecialchars($p['slug']) ?></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                    <?php if ($lCode === 'id') continue; ?>
                                    <?php
                                    $isDone = in_array($lCode, $p['translated_languages']);
                                    $btnStyle = $isDone ? "bg-emerald-600 text-white border-emerald-700" : "bg-amber-100 text-amber-900 border-amber-300";
                                    ?>
                                    <a href="terjemahan-editor.php?kategori=halaman&id=<?= $p['id'] ?>&lang=<?= $lCode ?>" class="px-3.5 py-2 rounded-xl border text-xs font-bold <?= $btnStyle ?> flex items-center gap-1.5 hover:opacity-90">
                                        <span><?= htmlspecialchars($lInfo['bendera']) ?> <?= htmlspecialchars($lInfo['nama']) ?></span>
                                        <span class="text-[10px] ml-1">[<?= $isDone ? '✔' : '⏳' ?>]</span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 2: BERITA DESA ================= -->
        <?php if ($tab == 'berita'): ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h2 class="font-bold text-xl text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-newspaper text-blue-600"></i> Studio Terjemahan Berita & Artikel Desa
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Terjemahkan judul serta isi berita desa ke dalam bahasa Inggris atau Jepang agar turis & pembaca internasional memahami kabar desa Anda.</p>
                    </div>
                </div>

                <?php if (empty($newsList)): ?>
                    <p class="text-slate-400 text-sm italic py-8 text-center">Belum ada artikel berita tersimpan di sistem.</p>
                <?php else: ?>
                    <div class="space-y-3.5">
                        <?php foreach ($newsList as $nw): ?>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <span class="text-[10px] font-mono text-slate-400 font-bold"><?= htmlspecialchars($nw['tanggal']) ?></span>
                                    <h3 class="font-bold text-slate-900 text-base"><?= htmlspecialchars($nw['judul']) ?></h3>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                        <?php if ($lCode === 'id') continue; ?>
                                        <?php
                                        $isDone = in_array($lCode, $nw['translated_languages']);
                                        $btnStyle = $isDone ? "bg-blue-600 text-white border-blue-700" : "bg-amber-100 text-amber-900 border-amber-300";
                                        ?>
                                        <a href="terjemahan-editor.php?kategori=berita&id=<?= $nw['id'] ?>&lang=<?= $lCode ?>" class="px-3.5 py-2 rounded-xl border text-xs font-bold <?= $btnStyle ?> flex items-center gap-1.5 hover:opacity-90">
                                            <span><?= htmlspecialchars($lInfo['bendera']) ?> <?= htmlspecialchars($lInfo['nama']) ?></span>
                                            <span class="text-[10px] ml-1">[<?= $isDone ? '✔' : '⏳' ?>]</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 3: INFOGRAFIS & STATISTIK KEUANGAN ================= -->
        <?php if ($tab == 'infografis'): ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <form action="bahasa.php?tab=infografis" method="POST">
                    <input type="hidden" name="kategori" value="infografis">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="font-bold text-xl text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-chart-column text-teal-700"></i> Kamus Terjemahan Infografis & Keuangan
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">Pastikan istilah keuangan, judul grafik, dan KPI anggaran di halaman infografis.php diterjemahkan dengan jelas.</p>
                        </div>
                        <button type="submit" name="simpan_kamus" class="bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow transition">
                            <i class="fa-solid fa-floppy-disk mr-1 text-amber-300"></i> Simpan Terjemahan Infografis
                        </button>
                    </div>

                    <table class="w-full border-collapse border border-slate-200 rounded-2xl overflow-hidden text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-xs text-left">
                            <tr>
                                <th class="p-4 border border-slate-200 w-1/4">🇮🇩 Label Infografis (Asli)</th>
                                <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                    <?php if ($lCode === 'id') continue; ?>
                                    <th class="p-4 border border-slate-200"><?= htmlspecialchars($lInfo['bendera']) ?> Terjemahan <?= htmlspecialchars($lInfo['nama']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($infocabKeys as $key => $lbl): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 border border-slate-200 font-bold bg-emerald-50/30 text-slate-800">
                                        <?= htmlspecialchars($lbl) ?>
                                        <span class="block text-[10px] font-mono text-slate-400 mt-0.5">key: <?= $key ?></span>
                                    </td>
                                    <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                        <?php if ($lCode === 'id') continue; ?>
                                        <?php $val = $dbTransData['infografis'][$key][$lCode] ?? ''; ?>
                                        <td class="p-3 border border-slate-200">
                                            <input type="text" name="trans[<?= $key ?>][<?= $lCode ?>]" value="<?= htmlspecialchars($val) ?>" placeholder="Terjemahan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-medium text-slate-800 text-sm">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 4: PUSTAKA DOKUMEN & REGULASI ================= -->
        <?php if ($tab == 'dokumen'): ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <form action="bahasa.php?tab=dokumen" method="POST">
                    <input type="hidden" name="kategori" value="dokumen">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="font-bold text-xl text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-file-invoice text-amber-700"></i> Kamus Terjemahan Pustaka JDIH & Aset
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">Terjemahan untuk judul, tombol filter kategori regulasi, dan tabel dokumen publik.</p>
                        </div>
                        <button type="submit" name="simpan_kamus" class="bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow transition">
                            <i class="fa-solid fa-floppy-disk mr-1 text-amber-300"></i> Simpan Terjemahan Dokumen
                        </button>
                    </div>

                    <table class="w-full border-collapse border border-slate-200 rounded-2xl overflow-hidden text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-xs text-left">
                            <tr>
                                <th class="p-4 border border-slate-200 w-1/4">🇮🇩 Label Dokumen (Asli)</th>
                                <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                    <?php if ($lCode === 'id') continue; ?>
                                    <th class="p-4 border border-slate-200"><?= htmlspecialchars($lInfo['bendera']) ?> Terjemahan <?= htmlspecialchars($lInfo['nama']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($docKeys as $key => $lbl): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 border border-slate-200 font-bold bg-emerald-50/30 text-slate-800">
                                        <?= htmlspecialchars($lbl) ?>
                                        <span class="block text-[10px] font-mono text-slate-400 mt-0.5">key: <?= $key ?></span>
                                    </td>
                                    <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                        <?php if ($lCode === 'id') continue; ?>
                                        <?php $val = $dbTransData['dokumen'][$key][$lCode] ?? ''; ?>
                                        <td class="p-3 border border-slate-200">
                                            <input type="text" name="trans[<?= $key ?>][<?= $lCode ?>]" value="<?= htmlspecialchars($val) ?>" placeholder="Terjemahan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-medium text-slate-800 text-sm">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 5: KAMUS UI & NAVBAR ================= -->
        <?php if ($tab == 'ui'): ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <form action="bahasa.php?tab=ui" method="POST">
                    <input type="hidden" name="kategori" value="ui">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="font-bold text-xl text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-language text-purple-600"></i> Kamus Label Tombol & Menu Antarmuka (UI)
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">Ubah kata-kata instrumen web seperti tombol dan menu di seluruh website.</p>
                        </div>
                        <button type="submit" name="simpan_kamus" class="bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow transition">
                            <i class="fa-solid fa-floppy-disk mr-1 text-amber-300"></i> Simpan Terjemahan UI
                        </button>
                    </div>

                    <table class="w-full border-collapse border border-slate-200 rounded-2xl overflow-hidden text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-xs text-left">
                            <tr>
                                <th class="p-4 border border-slate-200 w-1/4">🇮🇩 Teks UI (Indonesia)</th>
                                <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                    <?php if ($lCode === 'id') continue; ?>
                                    <th class="p-4 border border-slate-200"><?= htmlspecialchars($lInfo['bendera']) ?> Terjemahan <?= htmlspecialchars($lInfo['nama']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($uiKeys as $key => $lbl): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 border border-slate-200 font-bold bg-emerald-50/30 text-slate-800">
                                        <?= htmlspecialchars($lbl) ?>
                                        <span class="block text-[10px] font-mono text-slate-400 mt-0.5">key: <?= $key ?></span>
                                    </td>
                                    <?php foreach ($listLanguages as $lCode => $lInfo): ?>
                                        <?php if ($lCode === 'id') continue; ?>
                                        <?php $val = $dbTransData['ui'][$key][$lCode] ?? ''; ?>
                                        <td class="p-3 border border-slate-200">
                                            <input type="text" name="trans[<?= $key ?>][<?= $lCode ?>]" value="<?= htmlspecialchars($val) ?>" placeholder="Terjemahan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-medium text-slate-800 text-sm">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 6: KELOLA DAFTAR BAHASA ================= -->
        <?php if ($tab == 'master'): ?>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                        <h2 class="font-bold text-lg text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-plus-circle text-emerald-700"></i> Tambah Bahasa Baru Ke Website
                        </h2>
                        <form action="bahasa.php?tab=master" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Bahasa <span class="text-rose-500">*</span></label>
                                <input type="text" name="kode" required placeholder="Contoh: ar (Arab), fr (Prancis), ko (Korea)" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm uppercase font-bold text-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Bahasa <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama" required placeholder="Contoh: العربية (Arabic) / Deutsch" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-bold text-sm text-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ikon Bendera / Emoji</label>
                                <input type="text" name="bendera" placeholder="Contoh: 🇸🇦, 🇫🇷, 🇩🇪, 🇰🇷" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-bold text-lg text-center">
                            </div>
                            <button type="submit" name="tambah_bahasa" class="w-full bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold py-3.5 px-6 rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
                                <i class="fa-solid fa-circle-plus text-amber-300 text-lg"></i>
                                <span>Daftarkan Bahasa Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                            <h2 class="font-bold text-lg text-slate-900">Daftar Bahasa di Sistem Website</h2>
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200"><?= count($listLanguages) ?> Bahasa</span>
                        </div>
                        <div class="space-y-3.5">
                            <?php foreach ($listLanguages as $cLang => $infLang): ?>
                                <div class="p-4 rounded-2xl <?= $infLang['is_default'] ? 'bg-emerald-50 border-2 border-emerald-400' : 'bg-slate-50 border border-slate-200' ?> flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <span class="text-3xl p-2 rounded-xl bg-white shadow-xs border border-slate-200"><?= htmlspecialchars($infLang['bendera']) ?></span>
                                        <div>
                                            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                                <?= htmlspecialchars($infLang['nama']) ?>
                                                <?php if ($infLang['is_default']): ?>
                                                    <span class="bg-emerald-700 text-amber-300 text-[10px] px-2 py-0.5 rounded-full uppercase font-bold">Default</span>
                                                <?php endif; ?>
                                            </h3>
                                            <span class="text-xs font-mono text-slate-500">Kode: <code>?lang=<?= htmlspecialchars($cLang) ?></code></span>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if (!$infLang['is_default']): ?>
                                            <a href="bahasa.php?tab=master&hapus_bahasa=<?= htmlspecialchars($cLang) ?>" onclick="return confirm('Yakin ingin menghapus bahasa ini?')" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </main>
</body>
</html>
