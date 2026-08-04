<?php
session_start();
include '../config/database.php';
include '../config/lang_helper.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";
$itemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$langCode = isset($_GET['lang']) ? mysqli_real_escape_string($conn, strtolower(trim($_GET['lang']))) : 'en';
$kategori = isset($_GET['kategori']) && $_GET['kategori'] === 'berita' ? 'berita' : 'halaman';

// Pastikan bahasa tujuan terdaftar
$targetLang = $activeLanguages[$langCode] ?? null;
if (!$targetLang || $langCode === 'id') {
    header("Location: bahasa.php?msg=" . urlencode("Pilihlah bahasa asing untuk diterjemahkan."));
    exit;
}

// Ambil data asli dari halaman_statis atau berita
if ($kategori === 'berita') {
    $qOrig = mysqli_query($conn, "SELECT id, judul, isi as konten FROM berita WHERE id = $itemId");
    $backTab = 'berita';
    $lblJenis = 'Berita / Artikel Desa';
} else {
    $qOrig = mysqli_query($conn, "SELECT id, judul, konten FROM halaman_statis WHERE id = $itemId");
    $backTab = 'halaman';
    $lblJenis = 'Halaman Statis Desa';
}

$pageOrig = $qOrig ? mysqli_fetch_assoc($qOrig) : null;
if (!$pageOrig) {
    header("Location: bahasa.php?tab=$backTab&msg=" . urlencode("Konten tidak ditemukan."));
    exit;
}

// PROSES SIMPAN TERJEMAHAN MANUAL HUMAN TRANSLATOR
if (isset($_POST['simpan_terjemahan'])) {
    $transJudul = mysqli_real_escape_string($conn, trim($_POST['trans_judul']));
    $transKonten = mysqli_real_escape_string($conn, trim($_POST['trans_konten']));
    
    mysqli_query($conn, "INSERT INTO terjemahan_konten (kategori, referensi_id, kunci, kode_bahasa, teks_terjemahan) 
        VALUES ('$kategori', $itemId, 'judul', '$langCode', '$transJudul')
        ON DUPLICATE KEY UPDATE teks_terjemahan = '$transJudul'");
        
    mysqli_query($conn, "INSERT INTO terjemahan_konten (kategori, referensi_id, kunci, kode_bahasa, teks_terjemahan) 
        VALUES ('$kategori', $itemId, 'konten', '$langCode', '$transKonten')
        ON DUPLICATE KEY UPDATE teks_terjemahan = '$transKonten'");

    $msg = "Berhasil menyimpang terjemahan manual ($lblJenis) untuk bahasa " . htmlspecialchars($targetLang['nama']) . "!";
}

// Ambil data terjemahan saat ini dari database
$transJudulVal = "";
$transKontenVal = "";
$qTrans = mysqli_query($conn, "SELECT kunci, teks_terjemahan FROM terjemahan_konten WHERE kategori = '$kategori' AND referensi_id = $itemId AND kode_bahasa = '$langCode'");
while ($rowT = mysqli_fetch_assoc($qTrans)) {
    if ($rowT['kunci'] == 'judul') $transJudulVal = $rowT['teks_terjemahan'];
    if ($rowT['kunci'] == 'konten') $transKontenVal = $rowT['teks_terjemahan'];
}

$isFirstTime = empty($transKontenVal) && empty($transJudulVal);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Terjemahan: <?= htmlspecialchars($targetLang['nama']) ?> - Admin Klego</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <style>
        .content-reference h1, .content-reference h2, .content-reference h3 { font-weight: 800; color: #165f36; margin-top: 1em; margin-bottom: 0.5em; }
        .content-reference h1 { font-size: 1.5rem; }
        .content-reference h2 { font-size: 1.35rem; }
        .content-reference h3 { font-size: 1.2rem; }
        .content-reference ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1em; }
        .content-reference ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1em; }
        .content-reference p { margin-bottom: 1em; line-height: 1.7; }
        .content-reference blockquote { border-left: 4px solid #c4891f; background: #fffbeb; padding: 10px 16px; font-style: italic; margin-bottom: 1em; }
        .content-reference img { max-width: 100%; border-radius: 8px; margin: 10px auto; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <header class="bg-[#165f36] text-white shadow-lg border-b-4 border-amber-500 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <a href="bahasa.php?tab=<?= $backTab ?>" class="w-10 h-10 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors shadow">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight flex items-center gap-2">
                        <span>Studio Human Translation</span>
                        <span class="text-amber-300 font-extrabold">&bull; <?= htmlspecialchars($targetLang['bendera']) ?> <?= htmlspecialchars($targetLang['nama']) ?></span>
                    </h1>
                    <p class="text-[11px] text-emerald-200">Kategori: <?= htmlspecialchars($lblJenis) ?> &mdash; Referensi Indonesia di sebelah kiri</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="copyFromIndonesian()" class="bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-extrabold px-4 py-2 rounded-xl shadow border border-amber-300 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-copy text-amber-700"></i> Salin Struktur dari Indonesia
                </button>
                <button type="submit" form="formTranslation" name="simpan_terjemahan" class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-extrabold text-sm px-6 py-2 rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Terjemahan (<?= strtoupper($langCode) ?>)
                </button>
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- KOLOM KIRI: REFERENSI ASLI (INDONESIA) -->
            <div class="lg:col-span-5 flex flex-col space-y-4">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex-1">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <span class="text-xs font-extrabold bg-emerald-100 text-emerald-900 px-3 py-1 rounded-full flex items-center gap-1.5 border border-emerald-200">
                            🇮🇩 Teks Asli (Indonesia - Referensi)
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono">ID #<?= $pageOrig['id'] ?> (<?= strtoupper($kategori) ?>)</span>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Asli (Indonesia)</label>
                        <div id="origTitleText" class="p-3 bg-slate-50 rounded-xl font-extrabold text-slate-900 border border-slate-200 text-base mb-6">
                            <?= htmlspecialchars($pageOrig['judul']) ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Isi Konten (Indonesia)</label>
                        <div id="origContentText" class="p-5 bg-slate-50 rounded-2xl border border-slate-200 content-reference max-h-[650px] overflow-y-auto text-slate-700 text-sm">
                            <?= $pageOrig['konten'] ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: WORKSPACE TERJEMAHAN -->
            <div class="lg:col-span-7 flex flex-col">
                <form id="formTranslation" action="terjemahan-editor.php?kategori=<?= $kategori ?>&id=<?= $itemId ?>&lang=<?= $langCode ?>" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-amber-400 shadow-xl flex-1 flex flex-col">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <span class="text-xs font-extrabold bg-amber-100 text-amber-900 px-3.5 py-1 rounded-full flex items-center gap-2 border border-amber-300 text-sm">
                            <?= htmlspecialchars($targetLang['bendera']) ?> Studio: <b><?= htmlspecialchars($targetLang['nama']) ?></b>
                        </span>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg">
                            <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i> Editor Bebas Kode HTML
                        </span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Judul Terjemahan (Dalam <?= htmlspecialchars($targetLang['nama']) ?>) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="inputTransJudul" name="trans_judul" 
                               value="<?= htmlspecialchars($transJudulVal ?: ($isFirstTime ? $pageOrig['judul'] : '')) ?>" 
                               placeholder="Ketik judul terjemahan di sini..." 
                               required 
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-600 font-bold text-lg text-slate-900 shadow-xs">
                    </div>

                    <div class="flex-1 flex flex-col mb-6">
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase">
                                Isi Konten Terjemahan (Dalam <?= htmlspecialchars($targetLang['nama']) ?>)
                            </label>
                        </div>
                        <textarea id="editorTransKonten" name="trans_konten" class="w-full rounded-xl border border-slate-300 p-4 h-[550px]">
                            <?= htmlspecialchars($transKontenVal ?: ($isFirstTime ? $pageOrig['konten'] : '')) ?>
                        </textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <a href="bahasa.php?tab=<?= $backTab ?>" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold text-sm transition-colors">Batal</a>
                        <button type="submit" name="simpan_terjemahan" class="bg-[#165f36] hover:bg-[#0e3f23] text-white font-extrabold text-sm px-8 py-3.5 rounded-xl shadow-lg transition-all flex items-center gap-2 transform hover:scale-105">
                            <i class="fa-solid fa-circle-check text-amber-300"></i>
                            <span>Simpan Terjemahan (<?= strtoupper($langCode) ?>)</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        tinymce.init({
            selector: '#editorTransKonten',
            height: 580,
            menubar: false,
            plugins: 'lists link table code',
            toolbar: 'blocks | bold italic underline | bullist numlist | alignleft aligncenter alignright | link table | removeformat code',
            content_style: `
                body { font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.8; color: #1e293b; padding: 15px; }
                h1, h2, h3, h4 { color: #165f36; font-weight: 700; font-family: 'Outfit', sans-serif; }
                ul { list-style-type: disc; padding-left: 2rem; margin-bottom: 1rem; }
                ol { list-style-type: decimal; padding-left: 2rem; margin-bottom: 1rem; }
                blockquote { border-left: 4px solid #c4891f; background-color: #fffbeb; padding: 12px 18px; font-style: italic; }
            `,
            convert_urls: false,
            relative_urls: false,
            setup: function(editor) {
                editor.on('change', function() { editor.save(); });
            }
        });

        function copyFromIndonesian() {
            if (!confirm('Apakah Anda ingin menyalin struktur dari teks Indonesia ke editor ini?')) return;
            var origTitle = <?= json_encode($pageOrig['judul']) ?>;
            var origContent = <?= json_encode($pageOrig['konten']) ?>;
            
            document.getElementById('inputTransJudul').value = origTitle;
            if (tinymce.get('editorTransKonten')) {
                tinymce.get('editorTransKonten').setContent(origContent);
            } else {
                document.getElementById('editorTransKonten').value = origContent;
            }
            alert('Struktur berhasil disalin!');
        }
    </script>
</body>
</html>
