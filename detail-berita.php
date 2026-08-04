<?php
include 'config/database.php';

// Cek apakah ada id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$query = @mysqli_query($conn, "SELECT * FROM berita WHERE id = $id");
$berita = $query ? mysqli_fetch_assoc($query) : null;

if (!$berita) {
    echo "Berita tidak ditemukan.";
    exit;
}

include_once 'config/lang_helper.php';
$berita = translateBeritaData($berita, $id);

$pageTitle = htmlspecialchars($berita['judul']);
include 'config/header.php';
?>

<!-- ================= PAGE BANNER ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 relative overflow-hidden shadow-md">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
        <nav class="flex text-xs text-emerald-200/80 mb-4 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="index.php" class="hover:text-white transition-colors">Beranda</a></li>
                <li>&bull;</li>
                <li><a href="berita.php" class="hover:text-white transition-colors">Berita Desa</a></li>
                <li>&bull;</li>
                <li><span class="text-amber-300">Detail Artikel</span></li>
            </ol>
        </nav>
        <h1 class="font-heading font-extrabold text-2xl sm:text-4xl text-white tracking-tight leading-tight">
            <?= htmlspecialchars($berita['judul']); ?>
        </h1>
        <p class="text-xs text-emerald-200/70 mt-4 flex items-center gap-3">
            <span class="bg-amber-500 text-slate-900 px-2.5 py-0.5 rounded font-extrabold text-[10px]">Kabar Desa</span>
            <span class="flex items-center gap-1.5">
                <i class="fa-regular fa-calendar text-amber-400"></i> Dipublikasikan: <?= date('d M Y', strtotime($berita['tanggal'])); ?>
            </span>
        </p>
    </div>
</section>

<!-- ================= MAIN CONTENT SECTION ================= -->
<section class="py-14 max-w-4xl mx-auto px-4 sm:px-6">
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-sm">
        <a href="index.php" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 px-4 py-2 rounded-xl mb-8 transition-all border border-emerald-200">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <?php if (!empty($berita['foto'])): ?>
            <?php 
            // Cek lokasi file apakah di uploads/berita/ atau di uploads/
            $fotoPath = file_exists('uploads/berita/' . $berita['foto']) ? 'uploads/berita/' . $berita['foto'] : 'uploads/' . $berita['foto'];
            ?>
            <div class="mb-10 rounded-2xl overflow-hidden shadow-lg border border-slate-200">
                <img src="<?= htmlspecialchars($fotoPath); ?>" alt="<?= htmlspecialchars($berita['judul']); ?>" class="w-full h-auto object-cover max-h-[500px]">
            </div>
        <?php endif; ?>

        <!-- RENDERING KONTEN BERITA DENGAN WYSIWYG STYLES (.content-body) DAN AUTO-KOREKSI URL GAMBAR -->
        <div class="content-body text-slate-800 font-normal leading-relaxed">
            <?php 
            $kontenBerita = $berita['isi'];
            // Koreksi otomatis url gambar jika berupa path relatif agar tepat sasaran ke /desa-desa/uploads/
            $kontenBerita = preg_replace('/(src=")(?:\.\.\/)*uploads\//i', '$1/desa-desa/uploads/', $kontenBerita);
            echo $kontenBerita; 
            ?>
        </div>

        <div class="mt-12 pt-6 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <span class="text-xs text-slate-400 font-medium">
                &copy; Informasi Resmi Pemerintah <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
            </span>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500">Bagikan Berita:</span>
                <a href="https://api.whatsapp.com/send?text=<?= urlencode(htmlspecialchars($berita['judul']) . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-sm hover:bg-emerald-800 shadow transition-all">
                    <i class="fa-brands fa-whatsapp text-base"></i>
                </a>
                <a href="#" onclick="window.print(); return false;" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm hover:bg-slate-200 transition-all" title="Cetak Berita">
                    <i class="fa-solid fa-print text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>
