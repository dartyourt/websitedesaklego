<?php
include 'config/database.php';
require_once __DIR__ . '/config/upload_helper.php';

$slug = $_GET['slug'] ?? 'sejarah-visi-misi';
$pageData = null;
$umkmList = [];

// Ambil dari database jika aktif
if ($conn && !mysqli_connect_error()) {
    $escSlug = mysqli_real_escape_string($conn, $slug);
    $qPage = @mysqli_query($conn, "SELECT * FROM halaman_statis WHERE slug = '$escSlug'");
    if ($qPage && mysqli_num_rows($qPage) > 0) {
        $pageData = mysqli_fetch_assoc($qPage);
    }
    if ($slug === 'potensi-desa') {
        $qUmkm = @mysqli_query($conn, "SELECT * FROM umkm ORDER BY nama_usaha ASC");
        if ($qUmkm) while ($row = mysqli_fetch_assoc($qUmkm)) $umkmList[] = $row;
    }
}

// Fallback jika database belum disetup atau koneksi terhenti
if (!$pageData) {
    $defaults = [
        'sejarah-visi-misi' => [
            'judul' => 'Sejarah, Visi & Misi Desa Klego',
            'konten' => '
                <div class="space-y-6 text-slate-700 leading-relaxed text-base">
                    <div class="p-6 bg-emerald-50 border-l-4 border-emerald-700 rounded-r-2xl">
                        <h3 class="font-heading font-bold text-xl text-slate-900 mb-2">Visi Kepala Desa</h3>
                        <p class="italic text-slate-800 text-lg">"Mewujudkan Desa Klego yang Sejahtera, Mandiri, Berbudaya, dan Transparan Melalui Tata Kelola Pemerintahan yang Akuntabel dan Bersahaja."</p>
                    </div>

                    <h3 class="font-heading font-bold text-2xl text-slate-900 pt-4">Misi Pemerintah Desa</h3>
                    <ol class="list-decimal pl-6 space-y-3">
                        <li><strong>Pelayanan Terpadu & Mudah:</strong> Meningkatkan kualitas pelayanan administrasi publik bagi seluruh masyarakat Desa Klego dengan memanfaatkan kemajuan teknologi informasi.</li>
                        <li><strong>Infrastruktur Berkelanjutan:</strong> Membangun jalan antar dusun, drainase pertanian, dan fasilitas kemasyarakatan yang merata di seluruh 5 Dusun.</li>
                        <li><strong>Pemberdayaan Ekonomi & UMKM:</strong> Mengembangkan potensi ekonomi lokal berbasis pertanian modern dan memperkuat daya saing 87 pelaku UMKM kerajinan serta kuliner.</li>
                        <li><strong>Transparansi Pembendaharaan:</strong> Mengedepankan keterbukaan informasi hukum (JDIH) dan pengelolaan APBDes yang terbuka, tepat sasaran, dan akuntabel.</li>
                    </ol>

                    <h3 class="font-heading font-bold text-2xl text-slate-900 pt-6">Sejarah Singkat Desa Klego</h3>
                    <p>Desa Klego merupakan kawasan strategis yang terletak di Kecamatan Klego, Kabupaten Boyolali. Secara historis, nama Klego bermula dari kehidupan masyarakat agraris berkeadilan yang berjiwa gotong royong tinggi. Hingga masa modern, Desa Klego berkembang cepat menjadi pusat kebudayaan dan inovasi pelayanan publik yang diakui luas di Jawa Tengah.</p>
                </div>
            ',
            'updated_at' => '2026-01-10'
        ],
        'struktur-pemerintahan' => [
            'judul' => 'Struktur Pemerintahan Desa (SOTK)',
            'konten' => '
                <div class="space-y-6 text-slate-700 leading-relaxed">
                    <p class="text-base">Susunan Organisasi dan Tata Kerja (SOTK) Pemerintah Desa Klego dibentuk berdasarkan aturan ketatanegaraan Kabupaten Boyolali guna mengoptimalkan pelayan administrasi warga dan pembangunan infrastruktur desa.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full mx-auto mb-4 border-2 border-emerald-600 flex items-center justify-center text-3xl text-emerald-800 font-bold">K</div>
                            <h4 class="font-bold text-lg text-slate-900">Kepala Desa Klego</h4>
                            <span class="inline-block bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full font-semibold mt-1">Pimpinan Pemerintah Desa</span>
                        </div>
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full mx-auto mb-4 border-2 border-emerald-600 flex items-center justify-center text-3xl text-emerald-800 font-bold">S</div>
                            <h4 class="font-bold text-lg text-slate-900">Sekretaris Desa</h4>
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full font-semibold mt-1">Koordinator Administrasi</span>
                        </div>
                    </div>
                    <p class="pt-4 text-sm text-slate-500 italic">Untuk konsultasi tatap muka atau pertemuan musyawarah bersama BPD dan Perangkat Desa, Anda dapat berkunjung langsung ke Balai Desa Klego pada jam kerja.</p>
                </div>
            ',
            'updated_at' => '2026-01-12'
        ],
        'potensi-desa' => [
            'judul' => 'Potensi Agraris & Unggulan UMKM',
            'konten' => '
                <div class="space-y-6 text-slate-700 leading-relaxed">
                    <p class="text-base">Desa Klego berbangga atas kepemilikan lahan produktif seluas 312 Hektar yang menyokong ketahanan pangan daerah. Di luar itu, inovasi masyarakat telah menciptakan 87 UMKM yang aktif memproduksi barang bernilai tambah tinggi.</p>
                    <div class="p-6 bg-amber-50 rounded-2xl border border-amber-200/80">
                        <h4 class="font-bold text-slate-900 text-lg mb-2"><i class="fa-solid fa-star text-amber-500 mr-2"></i>Produk Unggulan UMKM Warga:</h4>
                        <ul class="list-disc pl-6 space-y-2 text-sm text-slate-700">
                            <li>Kerajinan Anyaman Bambu ramah lingkungan & perlengkapan dekorasi rumahtangga.</li>
                            <li>Batik Tulis bermotif khas Boyolali dikerjakan oleh kelompok perempuan desa.</li>
                            <li>Olahan Hasil Pangan Organik & keripik tradisional bermutu bersertifikat P-IRT.</li>
                        </ul>
                    </div>
                </div>
            ',
            'updated_at' => '2026-01-14'
        ],
        'panduan-layanan' => [
            'judul' => 'Panduan Layanan Administrasi Masyarakat',
            'konten' => '
                <div class="space-y-6 text-slate-700 leading-relaxed">
                    <p class="text-base font-medium text-slate-800">Seluruh pelayanan administrasi di Kantor Balai Desa Klego tidak dipungut biaya (<strong>GRATIS 100%</strong>) dan dijanjikan selesai dalam <strong>1 Hari Kerja</strong>.</p>
                    
                    <div class="space-y-4 pt-2">
                        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <h4 class="font-bold text-slate-900 flex items-center gap-2 text-base">
                                <i class="fa-solid fa-file-contract text-emerald-700"></i> Surat Keterangan Domisili / Usaha / Tidak Mampu (SKTM)
                            </h4>
                            <p class="text-xs text-slate-500 mt-2">Syarat: Membawa Fotokopi KTP, KK, serta Surat Pengantar dari Ketua RT/RW setempat.</p>
                        </div>
                        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <h4 class="font-bold text-slate-900 flex items-center gap-2 text-base">
                                <i class="fa-solid fa-id-card text-amber-600"></i> Surat Pengantar Pembuatan/Perbaikan KTP dan KK
                            </h4>
                            <p class="text-xs text-slate-500 mt-2">Syarat: Membawa blanko F1.01 atau Kartu Keluarga (KK) Lama asli bila ada perubahan anggota keluarga.</p>
                        </div>
                    </div>
                    <p class="text-sm pt-4">Layanan Buka: Senin s/d Jumat (Pukul 08.00 - 16.00 WIB) di Kantor Balai Desa.</p>
                </div>
            ',
            'updated_at' => '2026-01-15'
        ]
    ];
    
    $pageData = $defaults[$slug] ?? [
        'judul' => 'Halaman Tidak Ditemukan',
        'konten' => '<p class="text-slate-600">Mohon maaf, halaman yang Anda cari tidak tersedia atau sedang diperbarui oleh Tim Admin Desa Klego.</p>',
        'updated_at' => date('Y-m-d')
    ];
}

include_once 'config/lang_helper.php';
$pageData = translatePageData($pageData, $pageData['id'] ?? 0);

$pageTitle = htmlspecialchars($pageData['judul']);
include 'config/header.php';
?>

<!-- ================= PAGE BANNER ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 relative overflow-hidden shadow-md">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <nav class="flex text-xs text-emerald-200/80 mb-4 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-2">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-white transition-colors"><?= tr('Beranda') ?></a>
                </li>
                <li>&bull;</li>
                <li><span class="text-amber-300 font-semibold"><?= tr('Informasi & Layanan Publik') ?></span></li>
            </ol>
        </nav>
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-white tracking-tight">
            <?= htmlspecialchars($pageData['judul']) ?>
        </h1>
        <p class="text-xs text-emerald-200/80 mt-3 flex flex-wrap items-center gap-2.5">
            <span class="flex items-center gap-1.5"><i class="fa-regular fa-clock text-amber-400"></i> <?= tr('Diperbarui:') ?> <?= date('d M Y', strtotime($pageData['updated_at'] ?? 'now')) ?></span>
            <span class="text-emerald-400">&bull;</span>
            <span class="bg-emerald-800/90 text-emerald-100 px-3 py-1 rounded-full font-semibold text-[11px] flex items-center gap-1.5 border border-emerald-600 shadow-xs"><i class="fa-solid fa-circle-check text-amber-400"></i> <?= tr('Portal Resmi Pemerintah Desa') ?></span>
        </p>
    </div>
</section>

<!-- ================= MAIN CONTENT SECTION ================= -->
<section class="py-14 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <!-- KOLOM KONTEN UTAMA -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm prose max-w-none">
            <?php if (!empty($pageData['foto']) && file_exists('uploads/' . $pageData['foto'])): ?>
                <div class="mb-8 rounded-2xl overflow-hidden shadow-md">
                    <img src="uploads/<?= htmlspecialchars($pageData['foto']) ?>" alt="Header Foto" class="w-full h-auto object-cover max-h-96">
                </div>
            <?php endif; ?>

            <!-- HTML CONTEN DARI DATABASE DENGAN KOREKSI PATH GAMBAR OTOMATIS -->
            <div class="content-body text-slate-800 font-normal leading-relaxed">
                <?php 
                $kontenHalaman = $pageData['konten'];
                // Otomatis perbaiki URL gambar dari path relatif (seperti ../uploads atau uploads) menjadi /desa-desa/uploads agar gambar selalu load di frontend
                $kontenHalaman = normalize_content_image_urls($kontenHalaman);
                echo $kontenHalaman; 
                ?>
            </div>

            <?php if ($slug === 'potensi-desa' && !empty($umkmList)): ?>
                <div class="mt-10 pt-8 border-t border-slate-200 not-prose">
                    <h2 class="font-heading font-extrabold text-2xl text-slate-900 mb-2">UMKM Terdata</h2>
                    <p class="text-sm text-slate-600 mb-6">Data usaha lokal yang telah tersimpan pada database desa.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <?php foreach ($umkmList as $usaha): ?>
                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                <?php if (!empty($usaha['foto']) && file_exists('uploads/umkm/' . $usaha['foto'])): ?>
                                    <img src="uploads/umkm/<?= htmlspecialchars($usaha['foto']) ?>" alt="<?= htmlspecialchars($usaha['nama_usaha']) ?>" class="h-44 w-full object-cover">
                                <?php endif; ?>
                                <div class="p-5">
                                    <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-700"><?= htmlspecialchars($usaha['jenis']) ?></p>
                                    <h3 class="mt-1 font-heading font-bold text-lg text-slate-900"><?= htmlspecialchars($usaha['nama_usaha']) ?></h3>
                                    <p class="mt-2 text-xs leading-relaxed text-slate-600"><?= htmlspecialchars($usaha['deskripsi']) ?></p>
                                    <p class="mt-3 text-xs text-slate-500"><i class="fa-solid fa-location-dot text-amber-600 mr-1"></i><?= htmlspecialchars($usaha['alamat']) ?></p>
                                    <?php if (!empty($usaha['telepon'])): ?><a class="mt-3 inline-block text-xs font-bold text-emerald-700 hover:text-emerald-900" href="tel:<?= htmlspecialchars($usaha['telepon']) ?>"><i class="fa-solid fa-phone mr-1"></i><?= htmlspecialchars($usaha['telepon']) ?></a><?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-12 pt-6 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <span class="text-xs text-slate-400 font-medium">
                    &copy; Dokumen Portal Resmi Pemerintah <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
                </span>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500">Bagikan:</span>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode(htmlspecialchars($pageData['judul']) . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center text-sm hover:bg-emerald-800 shadow transition-all">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a href="#" onclick="window.print(); return false;" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-sm hover:bg-slate-200 transition-all" title="Cetak Dokumen">
                        <i class="fa-solid fa-print"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- KOLOM SIDEBAR (LINK TERKAIT & PUSTAKA DOKUMEN) -->
        <aside class="space-y-6">
            <!-- WIDGET 1: NAVIGASI HALAMAN TERKAIT -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <h3 class="font-heading font-bold text-base text-slate-900 pb-3 border-b border-slate-100 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-bookmark text-amber-500"></i>
                    <span><?= tr('Halaman Desa Lainnya') ?></span>
                </h3>
                <ul class="space-y-2 text-sm font-medium">
                    <li>
                        <a href="page.php?slug=sejarah-visi-misi" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 transition-colors <?= $slug=='sejarah-visi-misi' ? 'bg-emerald-50 text-emerald-800 font-bold' : '' ?>">
                            <span><?= tr('Sejarah, Visi & Misi') ?></span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="page.php?slug=struktur-pemerintahan" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 transition-colors <?= $slug=='struktur-pemerintahan' ? 'bg-emerald-50 text-emerald-800 font-bold' : '' ?>">
                            <span><?= tr('Struktur Pemerintahan') ?></span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="page.php?slug=potensi-desa" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 transition-colors <?= $slug=='potensi-desa' ? 'bg-emerald-50 text-emerald-800 font-bold' : '' ?>">
                            <span><?= tr('Potensi & UMKM Desa') ?></span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="page.php?slug=panduan-layanan" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 transition-colors <?= $slug=='panduan-layanan' ? 'bg-emerald-50 text-emerald-800 font-bold' : '' ?>">
                            <span><?= tr('Panduan Layanan Warga') ?></span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- WIDGET 2: UNDUH REGULASI CEPAT -->
            <div class="bg-gradient-to-br from-slate-900 to-emerald-950 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-400 bg-amber-400/10 px-2.5 py-1 rounded"><?= tr('Transparansi Data') ?></span>
                <h3 class="font-heading font-bold text-lg text-white mt-3 mb-2">
                    <?= tr('Cari Peraturan & Aset Desa?') ?>
                </h3>
                <p class="text-xs text-emerald-200/80 leading-relaxed mb-5">
                    <?= tr('Perdes APBDes 2026, Buku Bantu Aset 2025, dan Naskah RPJM dapat Anda telusuri secara bebas.') ?>
                </p>
                <a href="dokumen.php" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-xs py-3 rounded-xl flex items-center justify-center gap-2 transition-transform transform hover:-translate-y-0.5 shadow">
                    <i class="fa-solid fa-folder-arrow-down"></i>
                    <span><?= tr('Buka Pustaka Dokumen (JDIH)') ?></span>
                </a>
            </div>
        </aside>
    </div>
</section>

<?php include 'config/footer.php'; ?>
