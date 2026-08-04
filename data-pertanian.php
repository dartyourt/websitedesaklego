<?php
$pageTitle = "Data Pertanian & Ketahanan Pangan";
include 'config/header.php';
?>

<!-- ================= BANNER HERO ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 relative overflow-hidden shadow-md">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fbbf24_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <nav class="flex text-xs text-emerald-200/80 mb-4 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-2">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-white transition-colors"><?= tr('Beranda') ?></a>
                </li>
                <li>&bull;</li>
                <li><span class="text-amber-300 font-semibold"><?= tr('Data & Keterbukaan Publik') ?></span></li>
            </ol>
        </nav>
        <div class="max-w-3xl">
            <span class="text-xs font-mono uppercase tracking-widest bg-amber-400/20 text-amber-300 px-3 py-1 rounded-full border border-amber-400/40 inline-block mb-3">
                <?= tr('Portal Informasi Agraris') ?>
            </span>
            <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight leading-tight">
                <?= tr('Data Pertanian & Pemetaan Lahan Desa Klego') ?>
            </h1>
            <p class="text-emerald-100 text-sm sm:text-base mt-4 leading-relaxed">
                <?= tr('Pusat informasi terpadu yang mendorong keterbukaan data bagi seluruh warga. Memantau perkembangan pertanian desa, mencakup alokasi pupuk, kelembagaan petani, pemetaan lahan produktif 312 Ha, serta tingkat produktivitas komoditas pangan.') ?>
            </p>
        </div>
    </div>
</section>

<!-- ================= STATISTIK AGREGAT PERTANIAN ================= -->
<section class="py-12 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- CARD 1: LUAS LAHAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">312 Ha</p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Luas Lahan Sawah & Ladang') ?></p>
                </div>
            </div>

            <!-- CARD 2: KELOMPOK TANI -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-people-carry-box"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">8 <?= tr('Unit') ?></p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Kelompok Tani (Poktan)') ?></p>
                </div>
            </div>

            <!-- CARD 3: GAPOKTAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-blue-700 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-handshake-angle"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">1 <?= tr('Gapoktan') ?></p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Gabungan Poktan Tani Makmur') ?></p>
                </div>
            </div>

            <!-- CARD 4: PRODUKSI PANEN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-teal-700 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">1.850 <?= tr('Ton') ?></p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Est. Hasil Panen Padi 2025/2026') ?></p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= GRAFIK PRODUKTIVITAS & PEMETAAN LAHAN ================= -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- KOLOM GRAFIK PRODUKTIVITAS (7 KOLOM) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-6 border-b border-slate-100 mb-6">
                <div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full"><?= tr('Produktivitas Komoditas') ?></span>
                    <h2 class="font-heading font-bold text-2xl text-slate-900 mt-2"><?= tr('Hasil Panen Per Komoditas (Ton / Tahun)') ?></h2>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg"><?= tr('Data Musim Tanam 2025/2026') ?></span>
            </div>
            
            <div class="h-80 w-full">
                <canvas id="agricultureChart"></canvas>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-3 gap-4 text-center text-xs font-medium text-slate-600">
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="block text-slate-400"><?= tr('Komoditas Utama') ?></span>
                    <strong class="text-slate-900 font-bold text-sm"><?= tr('Padi / Gabah') ?></strong>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="block text-slate-400"><?= tr('Komoditas Kedua') ?></span>
                    <strong class="text-amber-700 font-bold text-sm"><?= tr('Jagung Hibrida') ?></strong>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="block text-slate-400"><?= tr('Komoditas Ketiga') ?></span>
                    <strong class="text-emerald-700 font-bold text-sm"><?= tr('Kedelai & Palawija') ?></strong>
                </div>
            </div>
        </div>

        <!-- KOLOM PEMETAAN LAHAN (5 KOLOM) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-gradient-to-br from-slate-900 to-emerald-950 text-white rounded-3xl p-8 shadow-xl relative overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-900 flex items-center justify-center text-2xl mb-4 font-black">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <h3 class="font-heading font-bold text-xl sm:text-2xl text-white"><?= tr('Pemetaan Penggunaan Lahan 312 Ha') ?></h3>
                <p class="text-emerald-100/80 text-sm mt-2 leading-relaxed">
                    <?= tr('Lahan di Desa Klego dikelola secara berkelanjutan dengan pemetaan zonasi yang mendukung irigasi teknis maupun tadah hujan.') ?>
                </p>
                <div class="space-y-3 pt-6">
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-emerald-300"><?= tr('Lahan Sawah Irigasi Teknis & Semi Teknis') ?></span>
                            <span>210 Ha (67%)</span>
                        </div>
                        <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-2.5 rounded-full" style="width: 67%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-amber-300"><?= tr('Lahan Tegalan / Ladang Palawija') ?></span>
                            <span>72 Ha (23%)</span>
                        </div>
                        <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-2.5 rounded-full" style="width: 23%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-teal-300"><?= tr('Perkebunan Rakyat & Hijauan Ternak') ?></span>
                            <span>30 Ha (10%)</span>
                        </div>
                        <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-teal-400 h-2.5 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <a href="peta-desa.php" class="inline-flex items-center gap-2 text-xs font-bold bg-amber-400 text-slate-900 py-3 px-5 rounded-xl hover:bg-amber-300 transition-colors shadow">
                        <i class="fa-solid fa-location-arrow"></i>
                        <span><?= tr('Buka WebGIS Peta Desa') ?></span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ================= DATA PUPUK & KELEMBAGAAN PETANI ================= -->
<section class="py-16 bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100/80 px-3 py-1 rounded-full border border-emerald-300">
                <?= tr('Transparansi Sarana Produksi') ?>
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
                <?= tr('Data Alokasi Pupuk & Kelembagaan Petani') ?>
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2">
                <?= tr('Keterbukaan distribusi pupuk bersubsidi dan direktori kelompok tani yang aktif mengelola laju pertanian Desa Klego.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            
            <!-- TABEL KETERSEDIAAN & ALOKASI PUPUK -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <h3 class="font-heading font-bold text-xl text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-packing text-amber-600"></i>
                        <span><?= tr('Alokasi Pupuk Bersubsidi TA 2026') ?></span>
                    </h3>
                    <span class="text-xs font-bold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full"><?= tr('Terverifikasi') ?></span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs">
                            <tr>
                                <th class="py-3 px-4 rounded-l-xl"><?= tr('Jenis Pupuk') ?></th>
                                <th class="py-3 px-4"><?= tr('Alokasi Tahunan') ?></th>
                                <th class="py-3 px-4 rounded-r-xl"><?= tr('Status Penyerapan') ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr>
                                <td class="py-3 px-4 font-bold text-slate-800">Urea (Subsidi)</td>
                                <td class="py-3 px-4">65.000 kg (65 Ton)</td>
                                <td class="py-3 px-4"><span class="bg-emerald-100 text-emerald-800 text-[11px] px-2.5 py-0.5 rounded-full font-bold">Terdistribusi Sesuai E-RDKK</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-slate-800">NPK Phonska</td>
                                <td class="py-3 px-4">48.500 kg (48.5 Ton)</td>
                                <td class="py-3 px-4"><span class="bg-emerald-100 text-emerald-800 text-[11px] px-2.5 py-0.5 rounded-full font-bold">Terdistribusi Sesuai E-RDKK</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-slate-800">NPK Formula Khusus</td>
                                <td class="py-3 px-4">12.000 kg (12 Ton)</td>
                                <td class="py-3 px-4"><span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-0.5 rounded-full font-bold">Stok Gudang Pengecer Ok</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-slate-800">Pupuk Organik Granul</td>
                                <td class="py-3 px-4">35.000 kg (35 Ton)</td>
                                <td class="py-3 px-4"><span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-0.5 rounded-full font-bold">Program Kemandirian Desa</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-slate-400 mt-4 italic">
                    *<?= tr('Penebusan pupuk bersubsidi menggunakan Kartu Tani atau identitas terdaftar dalam E-RDKK di agen resmi yang ditunjuk dinas terkait.') ?>
                </p>
            </div>

            <!-- DIREKTORI KELEMBAGAAN PETANI -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <h3 class="font-heading font-bold text-xl text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-users-line text-emerald-700"></i>
                        <span><?= tr('Daftar Kelembagaan Kelompok Tani') ?></span>
                    </h3>
                    <span class="text-xs font-bold px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full"><?= tr('5 Dusun') ?></span>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800 bg-amber-200 px-2 py-0.5 rounded"><?= tr('Koordinator Induk') ?></span>
                            <h4 class="font-heading font-bold text-slate-900 text-base mt-1">Gapoktan "Tani Makmur"</h4>
                            <p class="text-xs text-slate-600"><?= tr('Ketua: Bpk. H. Siswanto | Menanungi seluruh kelompok tani di Desa Klego.') ?></p>
                        </div>
                        <span class="text-xs font-bold text-emerald-800 bg-white px-3 py-1.5 rounded-xl border border-emerald-300">Aktif & Binaan Dinas</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <h5 class="font-bold text-slate-800 text-sm">Poktan Ngudi Rukun</h5>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-location-dot text-amber-500 mr-1"></i>Dukuh Klego (42 Anggota)</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <h5 class="font-bold text-slate-800 text-sm">Poktan Tani Mulyo</h5>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-location-dot text-amber-500 mr-1"></i>Dukuh Ponggok (38 Anggota)</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <h5 class="font-bold text-slate-800 text-sm">Poktan Suka Maju</h5>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-location-dot text-amber-500 mr-1"></i>Dukuh Soka (45 Anggota)</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <h5 class="font-bold text-slate-800 text-sm">Poktan Sumber Rejeki</h5>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-location-dot text-amber-500 mr-1"></i>Dukuh Ngembat (50 Anggota)</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================= STRATEGI PENGEMBANGAN KE DEPAN ================= -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-3xl mx-auto mb-12">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
            <?= tr('Visi Masa Depan') ?>
        </span>
        <h2 class="font-heading font-bold text-3xl text-slate-900 mt-2">
            <?= tr('Strategi & Arah Pengembangan Pertanian') ?>
        </h2>
        <p class="text-sm text-slate-600 mt-2">
            <?= tr('Langkah konkrit Pemerintah Desa dan Gapoktan dalam meningkatkan kelayakan hidup petani dan modernisasi persawahan.') ?>
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- STRATEGI 1 -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover-card-animate">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">
                <i class="fa-solid fa-droplet"></i>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900 mb-3"><?= tr('Modernisasi Jaringan Irigasi') ?></h3>
            <p class="text-sm text-slate-600 leading-relaxed">
                <?= tr('Peningkatan saluran irigasi teknis beratap dan pembangunan sumur bor pompa tenaga surya (solar water pump) untuk mencukupi kebutuhan air saat musim kemarau di lahan tadah hujan.') ?>
            </p>
        </div>

        <!-- STRATEGI 2 -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover-card-animate">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl mb-6">
                <i class="fa-solid fa-tractor"></i>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900 mb-3"><?= tr('Mekanisasi & Alat Pertanian') ?></h3>
            <p class="text-sm text-slate-600 leading-relaxed">
                <?= tr('Penguatan bantuan alat mesin pertanian (alsintan) seperti traktor roda empat, combine harvester (mesin panen padi otomatis), dan drone penyulut pupuk guna menekan biaya angkut & panen.') ?>
            </p>
        </div>

        <!-- STRATEGI 3 -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover-card-animate">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl mb-6">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900 mb-3"><?= tr('Transisi Pertanian Organik & Pemasaran') ?></h3>
            <p class="text-sm text-slate-600 leading-relaxed">
                <?= tr('Pelatihan pembuatan pupuk organik cair (POC) mandiri guna mengurangi ketergantungan pupuk kimia, serta integrasi penjualan hasil panen dengan BUMDes agar harga gabah stabil dan menguntungkan.') ?>
            </p>
        </div>
    </div>
</section>

<!-- SCRIPT CHART PRODUKTIVITAS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('agricultureChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['<?= tr("Padi / Gabah") ?>', '<?= tr("Jagung Hibrida") ?>', '<?= tr("Kedelai & Palawija") ?>', '<?= tr("Sayur & Buah") ?>'],
                datasets: [{
                    label: '<?= tr("Produksi (Ton)") ?>',
                    data: [1850, 620, 310, 185],
                    backgroundColor: [
                        '#165f36',
                        '#c4891f',
                        '#2e9e5b',
                        '#0284c7'
                    ],
                    borderRadius: 12,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' Ton';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: function(val) { return val + ' Ton'; } }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>

<?php include 'config/footer.php'; ?>
