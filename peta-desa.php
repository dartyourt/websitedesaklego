<?php
$pageTitle = "WebGIS & Pemetaan Wilayah Desa";
include 'config/header.php';
?>

<!-- ================= BANNER HERO ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 relative overflow-hidden shadow-md">
    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fbbf24_1px,transparent_1px)] [background-size:20px_20px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <nav class="flex text-xs text-emerald-200/80 mb-4 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-2">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-white transition-colors"><?= tr('Beranda') ?></a>
                </li>
                <li>&bull;</li>
                <li><span class="text-amber-300 font-semibold"><?= tr('Profil & Geografis Desa') ?></span></li>
            </ol>
        </nav>
        <div class="max-w-3xl">
            <span class="text-xs font-mono uppercase tracking-widest bg-amber-400/20 text-amber-300 px-3 py-1 rounded-full border border-amber-400/40 inline-block mb-3">
                <?= tr('Sistem Informasi Geografis (WebGIS)') ?>
            </span>
            <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight leading-tight">
                <?= tr('Peta Interaktif & Fasilitas Umum Desa Klego') ?>
            </h1>
            <p class="text-emerald-100 text-sm sm:text-base mt-4 leading-relaxed">
                <?= tr('Jelajahi batas administrasi 5 Dusun (6 RW / 18 RT), jaringan jalan raya, lokasi balai desa, posyandu, sekolah, masjid, dan sarana umum lainnya secara interaktif melalui WebGIS modern.') ?>
            </p>
        </div>
    </div>
</section>

<!-- ================= INFORMASI GEOGRAFIS & TOMBOL SCREEN ================= -->
<section class="py-8 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-emerald-600 animate-pulse"></span>
            <span class="text-xs font-bold text-slate-700"><?= tr('Peta Interaktif Aktif - Sinkornisasi Data WebGIS') ?></span>
        </div>
        <div class="flex items-center gap-3">
            <a href="assets/peta_webgis.html" target="_blank" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-emerald-950 text-white font-bold text-xs py-2.5 px-5 rounded-xl shadow transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-up-right-from-square text-amber-400"></i>
                <span><?= tr('Buka Layar Penuh (Full Screen)') ?></span>
            </a>
        </div>
    </div>
</section>

<!-- ================= KONTEN IFRAME WEBGIS ================= -->
<section class="py-10 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="bg-white rounded-3xl p-3 sm:p-4 border border-slate-200 shadow-xl overflow-hidden">
        <div class="w-full h-[650px] rounded-2xl overflow-hidden border border-slate-100 relative bg-slate-900">
            <iframe src="assets/peta_webgis.html" class="w-full h-full border-0" allowfullscreen loading="lazy" title="Peta WebGIS Desa Klego"></iframe>
        </div>
    </div>
</section>

<!-- ================= RINCIAN PEMBAGIAN WILAYAH DUSUN ================= -->
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-300">
                <?= tr('Struktur Wilayah Administrasi') ?>
            </span>
            <h2 class="font-heading font-bold text-3xl text-slate-900 mt-2">
                <?= tr('Rincian Wilayah 5 Dusun & Titik Penting') ?>
            </h2>
            <p class="text-sm text-slate-600 mt-1">
                <?= tr('Pembagian wilayah administrasi yang menopang pusat pelayanan masyarakat di Ibu Kota Kecamatan Klego.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black">01</span>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Dukuh Klego (Krajan)</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    <?= tr('Pusat pemerintahan desa dan kegiatan ekonomi. Di sini berlokasi Kantor Balai Desa Klego, fasilitas kesehatan, sekolah dasar, dan pusat perdagangan ruko setempat.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[11px] font-bold text-slate-500">
                    <span><?= tr('Wilayah: RW 01 & RW 02') ?></span>
                    <span class="text-emerald-700">4 RT Terdata</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-black">02</span>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Dukuh Ponggok</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    <?= tr('Kawasan agraris yang asri dengan hamparan sawah produktif yang terintegrasi dengan saluran irigasi teknis dan sentra kegiatan Posyandu Melati.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[11px] font-bold text-slate-500">
                    <span><?= tr('Wilayah: RW 03') ?></span>
                    <span class="text-emerald-700">3 RT Terdata</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-black">03</span>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Dukuh Soka</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    <?= tr('Perkampungan warga yang kental dengan budaya gotong royong dan tradisi lokal. Memiliki kelompok tani aktif serta sarana olahraga pemuda desa.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[11px] font-bold text-slate-500">
                    <span><?= tr('Wilayah: RW 04') ?></span>
                    <span class="text-emerald-700">4 RT Terdata</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-black">04</span>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Dukuh Ngembat</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    <?= tr('Gerbang penghubung jalur raya Karanggede-Gemolong yang dinamis, dilintasi angkutan umum dan bertumbuh pesatnya kuliner dan gerai UMKM makanan.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[11px] font-bold text-slate-500">
                    <span><?= tr('Wilayah: RW 05') ?></span>
                    <span class="text-emerald-700">4 RT Terdata</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-black">05</span>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Karanganyar & Kedokan</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    <?= tr('Zonasi perkebunan dan mukim yang berkontribusi pada penyediaan pangan lokal serta sentra kerajinan olahan snack dan kuliner hajatan desa.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[11px] font-bold text-slate-500">
                    <span><?= tr('Wilayah: RW 06') ?></span>
                    <span class="text-emerald-700">3 RT Terdata</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-900 to-slate-900 text-white rounded-3xl p-6 flex flex-col justify-between shadow-lg">
                <div>
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-400 bg-amber-400/10 px-2.5 py-1 rounded"><?= tr('Data Terpadu') ?></span>
                    <h3 class="font-heading font-bold text-lg text-white mt-2"><?= tr('Butuh Data Geospasial / Batas Wilayah?') ?></h3>
                    <p class="text-xs text-emerald-200/80 mt-2 leading-relaxed">
                        <?= tr('Seluruh data spasial dan buku profil wilayah Desa Klego dapat diunduh pada portal Regulasi & Aset Desa.') ?>
                    </p>
                </div>
                <a href="dokumen.php" class="mt-4 inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold text-xs py-2.5 px-4 rounded-xl transition-all shadow">
                    <i class="fa-solid fa-folder-arrow-down"></i>
                    <span><?= tr('Buka Pustaka Dokumen') ?></span>
                </a>
            </div>

        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>
