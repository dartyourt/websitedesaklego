<?php
include 'config/database.php';
include_once 'config/lang_helper.php';
require_once __DIR__ . '/config/upload_helper.php';

$pageTitle = t('pelayanan_berita', "Berita & Agenda Desa");
include 'config/header.php';

// Ambil semua berita dari database
$newsList = [];
if (isset($conn) && $conn && !mysqli_connect_error()) {
    $res = @mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row = translateBeritaData($row, $row['id'] ?? 0);
            $newsList[] = $row;
        }
    }
}
?>

<!-- ================= PAGE BANNER ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 relative overflow-hidden shadow-md">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 text-center max-w-4xl">
        <span class="inline-block text-xs font-mono font-bold uppercase tracking-widest text-amber-300 bg-black/20 px-4 py-1.5 rounded-full mb-4 border border-amber-400/40">
            <i class="fa-solid fa-newspaper mr-1.5"></i> Kabar & Informasi Terkini
        </span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight">
            <?= t('pelayanan_berita', 'Berita, Pelayanan & Agenda Desa') ?>
        </h1>
        <p class="text-emerald-100/90 text-sm sm:text-base mt-4 max-w-2xl mx-auto leading-relaxed">
            Ikuti perkembangan terbaru mengenai program pembangunan, kegiatan kemasyarakatan, serta pelayanan publik di Pemerintah <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>.
        </p>
    </div>
</section>

<!-- ================= DAFTAR BERITA KANVAS ================= -->
<section class="py-14 max-w-7xl mx-auto px-4 sm:px-6 min-h-[550px]">
    <?php if (empty($newsList)): ?>
        <!-- EMPTY STATE / DUMMY PREVIEW -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-xl mx-auto my-8">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 border border-emerald-200 shadow-inner">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="font-heading font-bold text-lg text-slate-800">Belum Ada Artikel Publik</h3>
            <p class="text-xs text-slate-500 mt-1.5 mb-6 leading-relaxed">
                Saat ini belum ada berita atau pengumuman yang diunggah ke portal resmi ini. Kunjungi kembali dalam waktu dekat untuk update terbaru!
            </p>
            <a href="index.php" class="bg-[#165f36] text-white font-bold text-xs px-6 py-3 rounded-xl inline-block hover:bg-[#0e3f23] transition-all shadow-md">
                <i class="fa-solid fa-arrow-left mr-1 text-amber-300"></i> <?= t('kembali_beranda', 'Kembali ke Beranda') ?>
            </a>
        </div>
    <?php else: ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($newsList as $b): ?>
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                    <!-- FOTO THUMBNAIL -->
                    <div class="h-52 bg-slate-100 relative overflow-hidden">
                        <?php 
                        $foto = !empty($b['foto']) ? $b['foto'] : '';
                        $fotoPath = !empty($foto) ? resolve_uploaded_image($foto) : '';
                        ?>
                        <?php if (!empty($fotoPath)): ?>
                            <img src="<?= htmlspecialchars($fotoPath) ?>" alt="<?= htmlspecialchars($b['judul']) ?>" onerror="this.onerror=null; this.src='assets/img/utama.jpg';" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-tr from-emerald-900 to-[#165f36] flex items-center justify-center text-amber-300 text-5xl">
                                <i class="fa-regular fa-newspaper opacity-40"></i>
                            </div>
                        <?php endif; ?>

                        <div class="absolute top-3 left-3 bg-amber-500 text-slate-900 font-extrabold text-[10px] px-3 py-1 rounded-full shadow">
                            Kabar Desa
                        </div>
                    </div>

                    <!-- KONTEN CARD -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[11px] font-bold font-mono text-slate-400 flex items-center gap-1.5 mb-2.5">
                                <i class="fa-regular fa-calendar-days text-emerald-600"></i>
                                <?= isset($b['tanggal']) ? date('d M Y', strtotime($b['tanggal'])) : '01 Jan 2026' ?>
                            </span>
                            <h3 class="font-heading font-extrabold text-xl text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                                <a href="detail-berita.php?id=<?= $b['id'] ?>"><?= htmlspecialchars($b['judul']) ?></a>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mt-2.5">
                                <?= clean_preview_text($b['isi'] ?? '', 160) ?>
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-400">Pemerintah Desa</span>
                            <a href="detail-berita.php?id=<?= $b['id'] ?>" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3.5 py-2 rounded-xl transition-all border border-emerald-200">
                                <span><?= t('baca_selengkapnya', 'Baca Selengkapnya') ?></span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-amber-600"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'config/footer.php'; ?>
