<?php
$pageTitle = "Infografis Keuangan & Demografi";
include 'config/header.php';

// Ambil data infografis dari database apabila tesedia, jika tidak gunakan standar berkualitas tinggi
$statsData = [];
$incomeLabels = $incomeValues = $expenseLabels = $expenseValues = [];
$dusunLabels = $dusunValues = $ageLabels = $ageValues = [];
$totalPendapatan = $totalBelanja = $silpa = $aset = 0;
if (isset($conn) && $conn && !mysqli_connect_error()) {
    $qInfo = @mysqli_query($conn, "SELECT * FROM infografis_statistik ORDER BY urutan ASC, id ASC");
    if ($qInfo && mysqli_num_rows($qInfo) > 0) {
        while ($row = mysqli_fetch_assoc($qInfo)) {
            $statsData[$row['kategori']][] = $row;
            if ($row['kategori'] === 'Pendapatan APBDes 2026') { $incomeLabels[] = $row['label']; $incomeValues[] = round($row['nilai'] / 1000000, 2); $totalPendapatan += $row['nilai']; }
            if ($row['kategori'] === 'Belanja APBDes 2026') { $expenseLabels[] = $row['label']; $expenseValues[] = round($row['nilai'] / 1000000, 2); $totalBelanja += $row['nilai']; }
            if ($row['label'] === 'Saldo Akhir Kas / SILPA 2025') $silpa = $row['nilai'];
            if ($row['label'] === 'Nilai Aset Tetap 2025') $aset = $row['nilai'];
        }
    }
    $qDusun = @mysqli_query($conn, "SELECT COALESCE(NULLIF(DUSUN, ''), 'Belum diisi') AS nama, COUNT(*) AS jumlah FROM penduduk GROUP BY nama ORDER BY nama");
    if ($qDusun) while ($row = mysqli_fetch_assoc($qDusun)) { $dusunLabels[] = $row['nama']; $dusunValues[] = (int) $row['jumlah']; }
    $qUsia = @mysqli_query($conn, "SELECT CASE WHEN TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) < 15 THEN '0-14 (Anak)' WHEN TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) < 30 THEN '15-29 (Pemuda)' WHEN TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) < 45 THEN '30-44 (Dewasa Muda)' WHEN TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) < 60 THEN '45-59 (Dewasa)' ELSE '60+ (Lansia)' END AS rentang, COUNT(*) AS jumlah FROM penduduk WHERE TGL_LAHIR IS NOT NULL GROUP BY rentang");
    if ($qUsia) while ($row = mysqli_fetch_assoc($qUsia)) { $ageLabels[] = $row['rentang']; $ageValues[] = (int) $row['jumlah']; }
}
?>

<!-- ================= HEADER SECTION ================= -->
<section class="bg-gradient-to-br from-[#0e3f23] via-[#165f36] to-emerald-900 text-white py-16 border-b-4 border-amber-500 shadow-lg relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 text-center max-w-4xl">
        <span class="inline-flex items-center gap-2 text-xs font-mono font-bold uppercase tracking-widest text-amber-300 bg-amber-500/20 px-4 py-1.5 rounded-full mb-4 border border-amber-400/40">
            <i class="fa-solid fa-chart-column"></i> <?= tr('Transparansi Pembendaharaan Negara & APBDes') ?>
        </span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight">
            <?= tr('Pusat Infografis Keuangan & Demografi') ?>
        </h1>
        <p class="text-emerald-100/90 text-sm sm:text-base mt-4 max-w-3xl mx-auto leading-relaxed">
            <?= tr('Penyajian data visual interaktif atas realisasi Anggaran Pendapatan dan Belanja Desa (APBDes) 2026, Laporan SILPA 2025, kekayaan aset desa, serta sebaran penduduk Desa Klego.') ?>
        </p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="#section-keuangan" class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold px-6 py-3 rounded-xl shadow-lg transition-all text-xs sm:text-sm flex items-center gap-2">
                <i class="fa-solid fa-money-bill-trend-up"></i> <?= tr('Infografis Keuangan') ?>
            </a>
            <a href="#section-demografi" class="bg-white/10 hover:bg-white/20 text-white font-bold px-6 py-3 rounded-xl border border-white/20 transition-all text-xs sm:text-sm flex items-center gap-2">
                <i class="fa-solid fa-users"></i> <?= tr('Statistik Penduduk') ?>
            </a>
        </div>
    </div>
</section>

<!-- ================= RINGKASAN ANGKA PEMBENDAHARAAN (KPI CARDS) ================= -->
<section class="py-10 max-w-7xl mx-auto px-4 sm:px-6 -mt-10 relative z-20" id="section-keuangan">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- KPI 1: TOTAL PENDAPATAN APBDES -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover-card-animate flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-md">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider"><?= tr('Pendapatan APBDes 2026') ?></span>
                <p class="text-2xl font-extrabold text-slate-900 font-heading">Rp <?= number_format($totalPendapatan / 1000000000, 2, ',', '.') ?> M</p>
                <span class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-600"></i> <?= tr('APBN & ADD Boyolali') ?>
                </span>
            </div>
        </div>

        <!-- KPI 2: TOTAL BELANJA APBDES -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover-card-animate flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-700 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-md">
                <i class="fa-solid fa-comments-dollar"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider"><?= tr('Belanja Desa 2026') ?></span>
                <p class="text-2xl font-extrabold text-slate-900 font-heading">Rp <?= number_format($totalBelanja / 1000000000, 2, ',', '.') ?> M</p>
                <span class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                    <i class="fa-solid fa-balance-scale text-blue-600"></i> <?= tr('Anggaran Berimbang') ?>
                </span>
            </div>
        </div>

        <!-- KPI 3: SILPA 2025 -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover-card-animate flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-md">
                <i class="fa-solid fa-piggy-bank"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider"><?= tr('SILPA Akhir 2025') ?></span>
                <p class="text-2xl font-extrabold text-slate-900 font-heading">Rp <?= number_format($silpa / 1000000, 2, ',', '.') ?> Juta</p>
                <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1 mt-0.5">
                    <i class="fa-solid fa-check-circle"></i> <?= tr('Laporan Audit Tersedia') ?>
                </span>
            </div>
        </div>

        <!-- KPI 4: NILAI BUKU ASET DESA -->
        <div class="bg-gradient-to-tr from-emerald-800 to-teal-700 text-white rounded-2xl p-6 shadow-xl hover-card-animate flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-400 text-slate-900 flex items-center justify-center text-2xl flex-shrink-0 shadow-md font-bold">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-amber-300 uppercase tracking-wider"><?= tr('Nilai Buku Aset 2025') ?></span>
                <p class="text-2xl font-extrabold text-white font-heading">Rp <?= number_format($aset / 1000000000, 2, ',', '.') ?> M</p>
                <span class="text-[11px] text-emerald-100 flex items-center gap-1 mt-0.5">
                    <i class="fa-solid fa-shield-halved text-amber-400"></i> <?= tr('Tanah Kas & Infrastruktur') ?>
                </span>
            </div>
        </div>

    </div>
</section>

<!-- ================= GRAFIK APBDES 2026 ================= -->
<section class="py-12 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- CHART 1: KOMPOSISI PENDAPATAN -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Komposisi Pendapatan APBDes') ?></h3>
                        <p class="text-xs text-slate-500"><?= tr('Total: Rp 1.475.000.000 (TA 2026)') ?></p>
                    </div>
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                </div>

                <div class="h-64 w-full">
                    <canvas id="incomeChart"></canvas>
                </div>

                <div class="mt-6 space-y-2 text-xs">
                    <div class="flex justify-between items-center p-2 rounded-lg bg-slate-50">
                        <span class="flex items-center gap-2 font-semibold text-slate-700">
                            <span class="w-3 h-3 rounded-full bg-[#165f36] inline-block"></span> <?= tr('Dana Desa (APBN)') ?>
                        </span>
                        <span class="font-bold text-slate-900">Rp 875 Juta (59,3%)</span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-slate-50">
                        <span class="flex items-center gap-2 font-semibold text-slate-700">
                            <span class="w-3 h-3 rounded-full bg-[#2e9e5b] inline-block"></span> <?= tr('Alokasi Dana Desa (ADD)') ?>
                        </span>
                        <span class="font-bold text-slate-900">Rp 350 Juta (23,7%)</span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-slate-50">
                        <span class="flex items-center gap-2 font-semibold text-slate-700">
                            <span class="w-3 h-3 rounded-full bg-[#c4891f] inline-block"></span> <?= tr('Pendapatan Asli Desa (PADes)') ?>
                        </span>
                        <span class="font-bold text-slate-900">Rp 185 Juta (12,5%)</span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-slate-50">
                        <span class="flex items-center gap-2 font-semibold text-slate-700">
                            <span class="w-3 h-3 rounded-full bg-[#fbbf24] inline-block"></span> <?= tr('Bagi Hasil Pajak & Retribusi') ?>
                        </span>
                        <span class="font-bold text-slate-900">Rp 65 Juta (4,5%)</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 mt-6">
                <a href="dokumen.php?kategori=Peraturan+%26+Produk+Legislasi+Desa" class="w-full text-center block bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs py-3 rounded-xl transition-colors">
                    <i class="fa-solid fa-file-pdf text-rose-600 mr-1.5"></i> <?= tr('Unduh Perdes APBDes 2026 Lengkap') ?>
                </a>
            </div>
        </div>

        <!-- CHART 2: DISTRIBUSI BELANJA -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Alokasi Belanja & Program Kerja 2026') ?></h3>
                        <p class="text-xs text-slate-500"><?= tr('Penggunaan dana difokuskan untuk pembangunan infrastruktur dan UMKM') ?></p>
                    </div>
                    <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-chart-column"></i>
                    </span>
                </div>

                <div class="h-80 w-full">
                    <canvas id="expenseChart"></canvas>
                </div>

                <!-- PROGRESS BAR RINCIAN -->
                <div class="mt-8 space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800"><?= tr('1. Pembangunan Infrastruktur & Jalan Desa (Rp 680 Juta)') ?></span>
                            <span class="text-emerald-700">46.1%</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[#165f36] to-[#2e9e5b] rounded-full" style="width: 46.1%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800"><?= tr('2. Penyelenggaraan Pemerintahan & Pelayanan (Rp 355 Juta)') ?></span>
                            <span class="text-amber-700">24.0%</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-600 to-amber-400 rounded-full" style="width: 24.0%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800"><?= tr('3. Pemberdayaan & Pelatihan UMKM Warga (Rp 320 Juta)') ?></span>
                            <span class="text-blue-700">21.6%</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-700 to-teal-500 rounded-full" style="width: 21.6%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800"><?= tr('4. Pembinaan Kemasyarakatan (Posyandu, PKK, Karang Taruna) (Rp 120 Juta)') ?></span>
                            <span class="text-purple-700">8.3%</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-600 rounded-full" style="width: 8.3%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5 font-medium">
                    <i class="fa-solid fa-building-flag text-emerald-700"></i> <?= tr('Disetujui bersama BPD Desa Klego') ?>
                </span>
                <a href="dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi" class="text-emerald-700 font-bold hover:underline">
                    <?= tr('Periksa Buku Bantu Aset & SILPA &rarr;') ?>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ================= PERBANDINGAN APBDES & PEMBIAYAAN DESA ================= -->
<section class="py-12 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- DIAGRAM BATANG PERBANDINGAN PENDAPATAN & BELANJA (6 KOLOM) -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full"><?= tr('Analisis Keseimbangan') ?></span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 mt-1"><?= tr('Perbandingan Pendapatan vs Belanja') ?></h3>
                    </div>
                    <i class="fa-solid fa-scale-balanced text-3xl text-amber-500 opacity-80"></i>
                </div>
                
                <div class="h-72 w-full">
                    <canvas id="comparisonChart"></canvas>
                </div>

                <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 leading-relaxed">
                    <strong class="text-slate-800 font-bold"><i class="fa-solid fa-circle-check text-emerald-700 mr-1.5"></i><?= tr('Anggaran Berimbang & Sehat:') ?></strong>
                    <?= tr('Selisih antara total pendapatan dan total belanja desa akan ditutup sepenuhnya melalui pemanfaatan SILPA tahun sebelumnya yang tercatat dalam pos Pembiayaan Netto.') ?>
                </div>
            </div>

            <!-- RINCIAN PEMBIAYAAN DESA TA 2026 (6 KOLOM) -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <span class="text-[10px] font-bold text-purple-800 bg-purple-100 px-2.5 py-0.5 rounded-full"><?= tr('Struktur Pembiayaan') ?></span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 mt-1"><?= tr('Informasi Pembiayaan Desa 2026') ?></h3>
                    </div>
                    <i class="fa-solid fa-vault text-3xl text-purple-600 opacity-80"></i>
                </div>

                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    <?= tr('Pembiayaan Desa meliputi penerimaan bersumber dari Sisa Lebih Perhitungan Anggaran (SILPA) dan pengeluaran yang ditujukan untuk investasi modal desa.') ?>
                </p>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-purple-50 border border-purple-200/70 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-purple-900 block"><?= tr('Penerimaan Pembiayaan') ?></span>
                            <span class="text-[11px] text-slate-500"><?= tr('Pemanfaatan SILPA Tahun 2025') ?></span>
                        </div>
                        <span class="text-base sm:text-lg font-black text-purple-900">Rp 350.088.352</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/70 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-rose-900 block"><?= tr('Pengeluaran Pembiayaan') ?></span>
                            <span class="text-[11px] text-slate-500"><?= tr('Penyertaan Modal & Cadangan Desa') ?></span>
                        </div>
                        <span class="text-base sm:text-lg font-black text-rose-800">Rp 34.880.000</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-900 to-slate-900 text-white flex items-center justify-between shadow">
                        <div>
                            <span class="text-xs font-bold text-amber-300 block"><?= tr('Pembiayaan Netto (Bersih)') ?></span>
                            <span class="text-[11px] text-emerald-200"><?= tr('Penerimaan dikurangi Pengeluaran') ?></span>
                        </div>
                        <span class="text-base sm:text-lg font-black text-white">Rp 315.208.352</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                    <a href="dokumen.php" class="text-xs font-bold text-purple-700 hover:text-purple-900 inline-flex items-center gap-1.5">
                        <span><?= tr('Unduh Dokumen Nota Keuangan APBDes') ?></span>
                        <i class="fa-solid fa-file-pdf text-[11px] text-rose-600"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= STATISTIK DEMOGRAFI KEPENDUDUKAN ================= -->
<section class="py-16 bg-slate-100 border-t border-slate-200" id="section-demografi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-200/60 px-3.5 py-1.5 rounded-full border border-emerald-300">
                <?= tr('Data Kependudukan') ?>
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
                <?= tr('Sebaran Wilayah & Demografi Penduduk') ?>
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2">
                <?= tr('Berdasarkan pendataan administrasi balai desa terbaru, mencerminkan sebaran warga di 5 Dusun dan piramida usia produktif.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- DEMOGRAFI DUSUN (PIE/POLAR) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Sebaran Penduduk per Dusun') ?></h3>
                        <p class="text-xs text-slate-500"><?= tr('Total 5 Dusun di Wilayah Desa Klego') ?></p>
                    </div>
                    <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-map-marked-alt"></i>
                    </span>
                </div>

                <div class="h-72 w-full">
                    <canvas id="dusunChart"></canvas>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-6 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-semibold text-slate-700"><?= tr('Dusun Klego') ?></span>
                        <span class="font-bold text-emerald-700"><?= tr('1.243 Warga (25.8%)') ?></span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-semibold text-slate-700"><?= tr('Dusun Ponggok') ?></span>
                        <span class="font-bold text-emerald-700"><?= tr('987 Warga (20.5%)') ?></span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-semibold text-slate-700"><?= tr('Dusun Soka') ?></span>
                        <span class="font-bold text-emerald-700"><?= tr('876 Warga (18.2%)') ?></span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-semibold text-slate-700"><?= tr('Dusun Rejosari') ?></span>
                        <span class="font-bold text-emerald-700"><?= tr('765 Warga (15.9%)') ?></span>
                    </div>
                </div>
            </div>

            <!-- DEMOGRAFI USIA (BAR HORIZONTAL) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Kelompok Usia & Bonus Demografi') ?></h3>
                        <p class="text-xs text-slate-500"><?= tr('Dominasi usia produktif (15 - 59 tahun)') ?></p>
                    </div>
                    <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-clock"></i>
                    </span>
                </div>

                <div class="h-72 w-full">
                    <canvas id="ageChart"></canvas>
                </div>

                <div class="mt-6 p-4 rounded-2xl bg-gradient-to-r from-emerald-800 to-teal-700 text-white flex items-center justify-between shadow">
                    <div>
                        <p class="text-xs text-emerald-200 uppercase tracking-wide font-bold"><?= tr('Rasio Usia Produktif') ?></p>
                        <p class="text-base sm:text-lg font-black text-amber-300"><?= tr('64,2% Warga Usia Produktif') ?></p>
                    </div>
                    <i class="fa-solid fa-award text-3xl text-amber-400 opacity-80"></i>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CHART JS INTERACTIVITY INITIALIZATION -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Income Chart
    const ctxInc = document.getElementById('incomeChart');
    if (ctxInc) {
        new Chart(ctxInc, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($incomeLabels, JSON_UNESCAPED_UNICODE) ?>,
                datasets: [{
                    data: <?= json_encode($incomeValues) ?>,
                    backgroundColor: ['#165f36', '#2e9e5b', '#c4891f', '#fbbf24'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ' Rp ' + ctx.raw + ' Juta'; }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // 2. Expense Chart
    const ctxExp = document.getElementById('expenseChart');
    if (ctxExp) {
        new Chart(ctxExp, {
            type: 'bar',
            data: {
                labels: <?= json_encode($expenseLabels, JSON_UNESCAPED_UNICODE) ?>,
                datasets: [{
                    label: '<?= tr("Alokasi Belanja 2026") ?>',
                    data: <?= json_encode($expenseValues) ?>,
                    backgroundColor: ['#165f36', '#c4891f', '#2e9e5b', '#6366f1'],
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
                            label: function(ctx) { return ' Rp ' + ctx.raw + ' Juta'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: function(val) { return 'Rp ' + val + ' Jt'; } }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 3. Dusun Chart
    const ctxDus = document.getElementById('dusunChart');
    if (ctxDus) {
        new Chart(ctxDus, {
            type: 'pie',
            data: {
                labels: <?= json_encode($dusunLabels, JSON_UNESCAPED_UNICODE) ?>,
                datasets: [{
                    data: <?= json_encode($dusunValues) ?>,
                    backgroundColor: ['#165f36', '#10b981', '#c4891f', '#06b6d4', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }

    // 4. Age Chart (Horizontal)
    const ctxAge = document.getElementById('ageChart');
    if (ctxAge) {
        new Chart(ctxAge, {
            type: 'bar',
            data: {
                labels: <?= json_encode($ageLabels, JSON_UNESCAPED_UNICODE) ?>,
                datasets: [{
                    label: '<?= tr("Statistik Penduduk") ?>',
                    data: <?= json_encode($ageValues) ?>,
                    backgroundColor: ['#8ecba5', '#2e9e5b', '#165f36', '#c4891f', '#d97706'],
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // 5. Comparison Chart (Pendapatan vs Belanja vs Pembiayaan)
    const ctxComp = document.getElementById('comparisonChart');
    if (ctxComp) {
        new Chart(ctxComp, {
            type: 'bar',
            data: {
                labels: ['<?= tr("Total Pendapatan") ?>', '<?= tr("Total Belanja") ?>', '<?= tr("Pembiayaan Netto") ?>'],
                datasets: [{
                    label: '<?= tr("Anggaran APBDes 2026 (Juta Rp)") ?>',
                    data: [1800.3, 1815.4, 315.2],
                    backgroundColor: [
                        '#165f36',
                        '#2563eb',
                        '#9333ea'
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
                            label: function(ctx) { return ' Rp ' + ctx.raw + ' Juta'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: function(val) { return 'Rp ' + val + ' Jt'; } }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>

<?php include 'config/footer.php'; ?>
