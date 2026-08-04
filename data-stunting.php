<?php
$pageTitle = "Data Pencegahan & Pemantauan Stunting";
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
                <li><span class="text-amber-300 font-semibold"><?= tr('Kesehatan & Kesejahteraan Warga') ?></span></li>
            </ol>
        </nav>
        <div class="max-w-3xl">
            <span class="text-xs font-mono uppercase tracking-widest bg-amber-400/20 text-amber-300 px-3 py-1 rounded-full border border-amber-400/40 inline-block mb-3">
                <?= tr('Portal Kesehatan Balita') ?>
            </span>
            <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight leading-tight">
                <?= tr('Data Pemantauan Risiko Stunting Desa Klego') ?>
            </h1>
            <p class="text-emerald-100 text-sm sm:text-base mt-4 leading-relaxed">
                <?= tr('Menyajikan informasi hasil penimbangan bulanan dan klasifikasi status gizi balita di seluruh Posyandu Desa Klego sebagai bentuk akuntabilitas publik dan upaya percepatan terwujudnya Klego Zero Stunting.') ?>
            </p>
        </div>
    </div>
</section>

<!-- ================= STATISTIK KESEHATAN BALITA ================= -->
<section class="py-12 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- CARD 1 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-baby"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">485 <?= tr('Anak') ?></p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Total Balita Terdata (0-59 Bulan)') ?></p>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="bg-white rounded-2xl p-6 border border-amber-300/80 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-amber-500 text-slate-900 flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-amber-700">28 <?= tr('Anak') ?></p>
                    <p class="text-xs font-semibold text-slate-600"><?= tr('Balita Berisiko Stunting (5.7%)') ?></p>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-house-medical-circle-check"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">8 <?= tr('Posyandu') ?></p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Pos Pelayanan Terpadu Aktif') ?></p>
                </div>
            </div>

            <!-- CARD 4 -->
            <div class="bg-white rounded-2xl p-6 border border-emerald-300/80 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow">
                    <i class="fa-solid fa-chart-line-down"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-emerald-800">Target 2026</p>
                    <p class="text-xs font-semibold text-slate-500"><?= tr('Menuju Klego Zero Stunting') ?></p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= GRAFIK SEBARAN RISIKO PER DUSUN & DATA RINCI ================= -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <!-- GRAFIK SEBARAN (7 KOLOM) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full"><?= tr('Distribusi Per Wilayah') ?></span>
                    <h2 class="font-heading font-bold text-2xl text-slate-900 mt-2"><?= tr('Sebaran Balita Berisiko Stunting Per Dusun') ?></h2>
                </div>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-lg"><?= tr('Update Penimbangan Terakhir') ?></span>
            </div>
            
            <div class="h-80 w-full">
                <canvas id="stuntingChart"></canvas>
            </div>

            <p class="text-xs text-slate-500 mt-6 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <strong class="text-slate-800 font-bold"><i class="fa-solid fa-circle-info text-amber-500 mr-1.5"></i><?= tr('Catatan Evaluasi Kesehatan:') ?></strong>
                <?= tr('Angka prevalensi stunting di Desa Klego terus menunjukkan grafik penurunan signifikan berkat sinergi Bidan Desa, Kader Posyandu, dan pengalokasian Dana Desa untuk Pemberian Makanan Tambahan (PMT) bergizi tinggi.') ?>
            </p>
        </div>

        <!-- TABEL RINCIAN POSYANDU (5 KOLOM) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <h3 class="font-heading font-bold text-xl text-slate-900 pb-4 border-b border-slate-100 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-emerald-700"></i>
                <span><?= tr('Daftar Posyandu & Kasus Risiko') ?></span>
            </h3>

            <div class="space-y-3.5">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Posyandu Mawar (Dukuh Klego)</h4>
                        <p class="text-[11px] text-slate-500">Total Balita Ditimbang: 112 Anak</p>
                    </div>
                    <span class="bg-amber-100 text-amber-900 text-xs px-3 py-1 rounded-full font-bold">8 Berisiko</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Posyandu Melati (Dukuh Ponggok)</h4>
                        <p class="text-[11px] text-slate-500">Total Balita Ditimbang: 95 Anak</p>
                    </div>
                    <span class="bg-amber-100 text-amber-900 text-xs px-3 py-1 rounded-full font-bold">6 Berisiko</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Posyandu Dahlia (Dukuh Soka)</h4>
                        <p class="text-[11px] text-slate-500">Total Balita Ditimbang: 104 Anak</p>
                    </div>
                    <span class="bg-amber-100 text-amber-900 text-xs px-3 py-1 rounded-full font-bold">7 Berisiko</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Posyandu Angkrek (Dukuh Ngembat)</h4>
                        <p class="text-[11px] text-slate-500">Total Balita Ditimbang: 98 Anak</p>
                    </div>
                    <span class="bg-amber-100 text-amber-900 text-xs px-3 py-1 rounded-full font-bold">5 Berisiko</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Posyandu Kenanga (Karanganyar)</h4>
                        <p class="text-[11px] text-slate-500">Total Balita Ditimbang: 76 Anak</p>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full font-bold">2 Berisiko</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <a href="dokumen.php" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1.5">
                    <span><?= tr('Unduh Laporan Lengkap Rekapitulasi Stunting') ?></span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ================= PROGRAM INTERVENSI PENCEGAHAN ================= -->
<section class="py-16 bg-gradient-to-br from-emerald-900 to-slate-950 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-mono uppercase tracking-widest bg-amber-400/20 text-amber-300 px-3 py-1 rounded-full border border-amber-400/40">
                <?= tr('Aksi Nyata Pemdes Klego') ?>
            </span>
            <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-white mt-3">
                <?= tr('Program Intervensi & Penurunan Stunting') ?>
            </h2>
            <p class="text-sm text-emerald-100 mt-2">
                <?= tr('Empat pilar utama yang diusung Pemerintah Desa Klego dalam menjamin pertumbuhan balita sehat secara menyeluruh.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/15 hover:bg-white/15 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-900 flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-white mb-2"><?= tr('Pemberian Makanan Tambahan (PMT)') ?></h3>
                <p class="text-xs text-emerald-100/80 leading-relaxed">
                    <?= tr('Distribusi paket susu, telur, bubur kacang hijau, dan olahan ikan segar yang dianggarkan secara rutin melalui Dana Desa untuk balita dan bumil KEK.') ?>
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/15 hover:bg-white/15 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-slate-900 flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-white mb-2"><?= tr('Pendampingan Bidan & Kader') ?></h3>
                <p class="text-xs text-emerald-100/80 leading-relaxed">
                    <?= tr('Kunjungan jemput bola ke rumah warga (home visit) bagi balita berisiko serta pengukuran tinggi & berat badan secara akuntabel menggunakan alat antropometri digital.') ?>
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/15 hover:bg-white/15 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-blue-500 text-white flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-faucet-drip"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-white mb-2"><?= tr('Sanitasi & Akses Air Bersih') ?></h3>
                <p class="text-xs text-emerald-100/80 leading-relaxed">
                    <?= tr('Pembangunan Jamban Sehat Keluarga (Jambanisasi) dan peningkatan instalasi sarana air bersih warga untuk memutus mata rantai infeksi saluran pencernaan penyebab stunting.') ?>
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/15 hover:bg-white/15 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-teal-400 text-slate-900 flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-white mb-2"><?= tr('Edukasi Gizi & Parenting') ?></h3>
                <p class="text-xs text-emerald-100/80 leading-relaxed">
                    <?= tr('Penyuluhan kelas ibu hamil dan sekolah parenting bagi pasangan usia subur agar memahami pola asuh berkualitas dan ASI eksklusif 6 bulan pertama.') ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- SCRIPT CHART STUNTING -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stuntingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Dukuh Klego', 'Dukuh Ponggok', 'Dukuh Soka', 'Dukuh Ngembat', 'Karanganyar'],
                datasets: [{
                    label: '<?= tr("Balita Berisiko (Anak)") ?>',
                    data: [8, 6, 7, 5, 2],
                    backgroundColor: [
                        '#c4891f',
                        '#c4891f',
                        '#c4891f',
                        '#c4891f',
                        '#10b981'
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
                                return context.raw + ' Balita Berisiko';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 2, callback: function(val) { return val + ' Anak'; } }
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
