<?php
$pageTitle = "Beranda Utama";
include 'config/header.php';

// Coba ambil statistik penduduk asli jika database terhubung dan ada datanya
$statPenduduk = 4823;
$statKK = 1456;
$statLaki = 2411;
$statPerempuan = 2412;

if (isset($conn) && $conn && !mysqli_connect_error()) {
    $qTotal = @mysqli_query($conn, "SELECT COUNT(*) as total FROM penduduk");
    if ($qTotal && $r = mysqli_fetch_assoc($qTotal)) {
        if ($r['total'] > 0) {
            $statPenduduk = $r['total'];
            $rKK = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT NO_KK) as kk FROM penduduk"));
            $statKK = $rKK['kk'] ?? 1456;
            $rL = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as l FROM penduduk WHERE JENIS_KELAMIN='Laki-laki' OR JENIS_KELAMIN='L'"));
            $statLaki = $rL['l'] ?? 2411;
            $statPerempuan = $statPenduduk - $statLaki;
        }
    }
}
?>

<!-- ================= HERO SECTION (DESIGN IDEAS INSPIRED) ================= -->
<section class="relative hero-gradient text-white overflow-hidden py-24 sm:py-32 border-b-8 border-amber-500 shadow-xl">
    <!-- BACKGROUND DECORATIONS -->
    <div class="absolute inset-0 hero-pattern opacity-25"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-0 bottom-0 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="max-w-3xl space-y-6 text-center lg:text-left">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-semibold tracking-wide shadow-inner backdrop-blur-md">
                <i class="fa-solid fa-square-poll-vertical text-amber-400 animate-pulse"></i>
                <span>Transparansi Pembendaharaan Negara & Regulasi Desa</span>
            </div>

            <h1 class="font-heading font-extrabold text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-[1.15]">
                Portal Resmi Pemerintahan <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-200 to-yellow-400">
                    <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
                </span>
            </h1>

            <p class="text-base sm:text-lg text-emerald-100 font-normal leading-relaxed max-w-2xl">
                Mewujudkan pelayanan publik terpadu yang cepat, pengungkapan data aset dan anggaran (APBDes) yang akuntabel, serta kemudahan unduh regulasi hukum bagi seluruh warga masyarakat.
            </p>

            <!-- CALL TO ACTION BUTTONS -->
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                <a href="dokumen.php" 
                   class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold px-7 py-3.5 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2.5">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Pustaka Hukum (JDIH)</span>
                </a>
                <a href="infografis.php" 
                   class="bg-emerald-900/80 hover:bg-emerald-800 text-emerald-100 border border-emerald-600/60 font-semibold px-6 py-3.5 rounded-xl shadow-md hover:text-white transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-amber-400"></i>
                    <span>Infografis Keuangan</span>
                </a>
            </div>

            <div class="pt-6 flex items-center justify-center lg:justify-start gap-6 text-xs text-emerald-200/80">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i> Perdes APBDes 2026 Tersedia
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i> Laporan SILPA 2025 Terbuka
                </span>
            </div>

        </div>
    </div>
</section>

<!-- ================= STATISTIK DEMOGRAFI & WILAYAH ================= -->
<section class="py-12 bg-white border-b border-slate-200 shadow-sm relative -mt-8 z-20 max-w-7xl mx-auto rounded-3xl mx-4 sm:mx-auto px-6 shadow-xl">
    <div class="text-center mb-8">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
            Statistik Warga & Wilayah
        </span>
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mt-2">
            Demografi & Angka Penting <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
        </h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <!-- CARD 1: TOTAL PENDUDUK -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statPenduduk, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium">Total Penduduk</p>
            </div>
        </div>

        <!-- CARD 2: KEPALA KELUARGA -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-amber-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-house-chimney-user"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statKK, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium">Kepala Keluarga (KK)</p>
            </div>
        </div>

        <!-- CARD 3: LAKI-LAKI -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-blue-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-person"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statLaki, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium">Laki-laki</p>
            </div>
        </div>

        <!-- CARD 4: PEREMPUAN -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-person-dress"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statPerempuan, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium">Perempuan</p>
            </div>
        </div>

        <!-- CARD 5: LUAS WILAYAH -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-teal-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-map-location"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">312 Ha</p>
                <p class="text-xs text-slate-500 font-medium">Lahan & Pertanian</p>
            </div>
        </div>

        <!-- CARD 6: DUSUN -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-indigo-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-map-pin"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">5 Dusun</p>
                <p class="text-xs text-slate-500 font-medium">Klego, Ponggok, Soka, dst.</p>
            </div>
        </div>

        <!-- CARD 7: RT / RW -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-purple-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-sitemap"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">6 RW / 18 RT</p>
                <p class="text-xs text-slate-500 font-medium">Pembagian Wilayah</p>
            </div>
        </div>

        <!-- CARD 8: APBDES 2026 -->
        <div class="bg-gradient-to-br from-emerald-800 to-emerald-900 text-white rounded-2xl p-5 flex items-center gap-4 shadow-lg transform hover:-translate-y-1 transition-all duration-200 cursor-pointer" onclick="window.location='infografis.php'">
            <div class="w-12 h-12 rounded-xl bg-amber-500 text-slate-900 flex items-center justify-center text-xl flex-shrink-0 font-extrabold shadow">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <p class="text-xl sm:text-2xl font-black text-amber-300">Rp 1,25 M</p>
                <p class="text-xs text-emerald-100 font-medium">Total APBDes 2026</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= PUSAT INFORMASI & KETERBUKAAN DATA (FOLDER SATRIA) ================= -->
<section class="py-20 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100/80 px-3 py-1.5 rounded-full border border-emerald-300">
            Keterbukaan Informasi & Pembendaharaan
        </span>
        <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
            Pusat Data Regulasi, Aset & Perencanaan
        </h2>
        <p class="text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
            Mewujudkan pemerintahan Desa Klego yang bersih dan terbuka. Seluruh berkas Peraturan Desa, Buku Bantu Aset, Laporan SILPA, dan RPJM Desa dapat diperiksa serta diunduh secara bebas oleh masyarakat.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- KATEGORI 1: PERATURAN & PRODUK LEGISLASI -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover-card-animate flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-full pointer-events-none group-hover:bg-emerald-500/10 transition-colors"></div>
            <div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-2xl mb-6 shadow-md">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full">11 Dokumen Tersedia</span>
                <h3 class="font-heading font-bold text-xl text-slate-900 mt-4 mb-3">
                    Peraturan & Produk Legislasi Desa
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    Pusat informasi produk hukum Desa Klego untuk mendukung transparansi pemerintahan dan memudahkan akses masyarakat terhadap regulasi yang berlaku. Meliputi Perdes APBDes 2026, Perkades APBDes, Keputusan Kepala Desa, dan produk hukum lainnya yang diperbarui secara berkala.
                </p>
            </div>
            <div class="pt-6 border-t border-slate-100 mt-2">
                <a href="dokumen.php?kategori=Peraturan+%26+Produk+Legislasi+Desa" class="w-full bg-slate-900 hover:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow">
                    <span>Lihat & Unduh Regulasi</span>
                    <i class="fa-solid fa-arrow-right text-amber-400"></i>
                </a>
            </div>
        </div>

        <!-- KATEGORI 2: DATA ASET & PENGEMBANGAN DESA -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover-card-animate flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-bl-full pointer-events-none group-hover:bg-amber-500/10 transition-colors"></div>
            <div>
                <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl mb-6 shadow-md">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full">9 Laporan Resmi</span>
                <h3 class="font-heading font-bold text-xl text-slate-900 mt-4 mb-3">
                    Data Aset & Pembendaharaan Desa
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    Pusat informasi yang memuat berbagai data penyelenggaraan pemerintahan Desa Klego sebagai wujud transparansi dan pelayanan publik. Melalui halaman ini, masyarakat dapat mengakses data seperti inventaris aset desa, buku bantu, stock opname, laporan SILPA 2025, serta CaLK 2025.
                </p>
            </div>
            <div class="pt-6 border-t border-slate-100 mt-2">
                <a href="dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow">
                    <span>Buka Inventaris & SILPA</span>
                    <i class="fa-solid fa-arrow-right text-amber-300"></i>
                </a>
            </div>
        </div>

        <!-- KATEGORI 3: RPJM DESA PERUBAHAN -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover-card-animate flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full pointer-events-none group-hover:bg-blue-500/10 transition-colors"></div>
            <div>
                <div class="w-14 h-14 rounded-2xl bg-blue-800 text-white flex items-center justify-center text-2xl mb-6 shadow-md">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full">Perencanaan 6 Tahun</span>
                <h3 class="font-heading font-bold text-xl text-slate-900 mt-4 mb-3">
                    RPJM Desa (Rencana Pembangunan)
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    RPJM Desa merupakan dokumen perencanaan pembangunan desa untuk jangka waktu 6 (enam) tahun yang menjadi pedoman dalam penyelenggaraan pemerintahan, pelaksanaan pembangunan, dan pemberdayaan masyarakat. Disusun sebagai dasar terarah dan berkelanjutan.
                </p>
            </div>
            <div class="pt-6 border-t border-slate-100 mt-2">
                <a href="dokumen.php?kategori=Rencana+Pembangunan+Jangka+Menengah+%28RPJM%29" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow">
                    <span>Unduh Naskah RPJM</span>
                    <i class="fa-solid fa-arrow-right text-amber-400"></i>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ================= INFOGRAFIS PEMBENDAHARAAN & APBDES (PREVIEW) ================= -->
<section class="py-16 bg-gradient-to-br from-slate-900 to-emerald-950 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-6">
                <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 bg-amber-400/10 px-3 py-1.5 rounded-full border border-amber-500/30">
                    Statistik & Keuangan Desa
                </span>
                <h2 class="font-heading font-extrabold text-3xl sm:text-4xl leading-tight text-white">
                    Transparansi Pengelolaan Pembendaharaan Negara & Dana Desa
                </h2>
                <p class="text-emerald-100/80 text-sm sm:text-base leading-relaxed">
                    Kami menjunjung tinggi prinsip akuntabilitas dalam penggunaan Anggaran Pendapatan dan Belanja Desa (APBDes) 2026 maupun pertanggungjawaban SILPA 2025.
                </p>
                
                <div class="space-y-3 pt-2">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-emerald-200 font-medium">Dana Desa (APBN)</p>
                            <p class="text-lg font-bold text-white">Rp 875.000.000</p>
                        </div>
                        <span class="bg-emerald-600 text-white text-xs px-2.5 py-1 rounded-full font-bold">70% APBDes</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-amber-200 font-medium">SILPA Akhir Tahun 2025</p>
                            <p class="text-lg font-bold text-white">Rp 98.450.000</p>
                        </div>
                        <span class="bg-amber-500 text-slate-900 text-xs px-2.5 py-1 rounded-full font-black">Audit Ok</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="infografis.php" class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-7 py-3.5 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <span>Buka Dasbor Infografis Lengkap</span>
                        <i class="fa-solid fa-chart-line"></i>
                    </a>
                </div>
            </div>

            <!-- PREVIEW CHART CONTAINER -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl text-slate-800 border border-slate-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-heading font-bold text-lg text-slate-900">Distribusi Anggaran Belanja APBDes 2026</h3>
                            <p class="text-xs text-slate-500">Berdasarkan Perdes No. 01 Tahun 2026 Desa Klego</p>
                        </div>
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-300">TA 2026</span>
                    </div>
                    
                    <div class="h-64 sm:h-72 w-full">
                        <canvas id="homeBudgetChart"></canvas>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-2 gap-4 text-center text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Fokus Pembangunan</span>
                            <span class="font-bold text-slate-800 text-sm">Infrastruktur & Jalan (54.4%)</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Fokus Pemberdayaan</span>
                            <span class="font-bold text-emerald-700 text-sm">Pelatihan UMKM (25.6%)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- JS PREVIEW CHART -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('homeBudgetChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pembangunan Infrastruktur', 'Penyelenggaraan Pemdes', 'Pemberdayaan UMKM', 'Pembinaan Masyarakat'],
                datasets: [{
                    label: 'Alokasi Belanja (Juta Rp)',
                    data: [680, 355, 320, 120],
                    backgroundColor: [
                        '#165f36',
                        '#c4891f',
                        '#2e9e5b',
                        '#3b82f6'
                    ],
                    borderRadius: 8,
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
                                return 'Rp ' + context.raw + ' Juta';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: function(val) { return 'Rp ' + val + ' Jt'; } }
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

<!-- ================= POTENSI DESA & UMKM ================= -->
<section class="py-20 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-100/80 px-3 py-1 rounded-full border border-amber-300">
                Keunggulan & Potensi Lokal
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
                Potensi Agraris & Kekuatan UMKM Warga
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2">
                Desa Klego dianugerahi lahan pertanian subur dan komunitas pengrajin UMKM yang aktif menggerakkan roda ekonomi desa.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- POTENSI 1: PERTANIAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900">Pertanian Produktif</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Lahan produktif seluas 312 Ha dengan komoditas utama padi, jagung, dan kedelai berkualitas tinggi dari Boyolali.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700">312 Ha Lahan</span>
                    <i class="fa-solid fa-seedling text-amber-500"></i>
                </div>
            </div>

            <!-- POTENSI 2: UMKM -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900">87 UMKM Aktif</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Berkembangnya usaha masyarakat bidang kerajinan anyaman bambu, batik tulis lokal, dan olahan pangan tradisional.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700">Kerajinan & Pangan</span>
                    <i class="fa-solid fa-hand-holding-dollar text-emerald-600"></i>
                </div>
            </div>

            <!-- POTENSI 3: KELEMBAGAAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900">Kelembagaan Solid</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Didukung 12 lembaga kemasyarakatan yang aktif: BPD, LKMD, PKK, Karang Taruna, dan Gapoktan yang tanggap.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-teal-700">12 Lembaga Aktif</span>
                    <i class="fa-solid fa-people-group text-blue-500"></i>
                </div>
            </div>

            <!-- POTENSI 4: PEMBANGUNAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-road-bridge"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900">Infrastruktur Maju</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Pemerataan perbaikan jalan antar dusun, drainase pertanian, dan penerangan jalan umum dengan pengawasan warga.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-indigo-700">Sesuai RPJMDes</span>
                    <i class="fa-solid fa-check-double text-emerald-600"></i>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ================= BERITA TERKINI & AGENDA ================= -->
<section class="py-20 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
        <div>
            <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100/80 px-3 py-1 rounded-full">
                Informasi & Kegiatan Warga
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
                Berita Terkini & Agenda Desa
            </h2>
        </div>
        <a href="berita.php" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold flex items-center gap-1.5 transition-colors">
            <span>Lihat Semua Berita</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 2 KOLOM BERITA TERKINI -->
        <div class="lg:col-span-2 space-y-6">
            <?php
            $foundNews = false;
            if (isset($conn) && $conn && !mysqli_connect_error()) {
                $qBerita = @mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 2");
                if ($qBerita && mysqli_num_rows($qBerita) > 0) {
                    $foundNews = true;
                    while ($b = mysqli_fetch_assoc($qBerita)) {
                        $b = translateBeritaData($b, $b['id'] ?? 0);
            ?>
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-6 items-center hover-card-animate">
                        <div class="w-full sm:w-48 h-40 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 relative">
                            <?php if (!empty($b['foto']) && file_exists('uploads/' . $b['foto'])): ?>
                                <img src="uploads/<?= htmlspecialchars($b['foto']) ?>" alt="<?= htmlspecialchars($b['judul']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-emerald-800/10 flex items-center justify-center text-emerald-700 text-3xl">
                                    <i class="fa-regular fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            <span class="absolute top-2 left-2 bg-amber-500 text-slate-900 font-bold text-[10px] px-2.5 py-0.5 rounded-full">Berita</span>
                        </div>
                        <div class="flex-1">
                            <span class="text-xs text-slate-400 flex items-center gap-1.5 mb-2">
                                <i class="fa-regular fa-calendar text-emerald-600"></i>
                                <?= date('d M Y', strtotime($b['tanggal'])) ?>
                            </span>
                            <h3 class="font-heading font-bold text-xl text-slate-900 hover:text-emerald-800 transition-colors mb-2">
                                <a href="detail-berita.php?id=<?= $b['id'] ?>"><?= htmlspecialchars($b['judul']) ?></a>
                            </h3>
                            <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                <?= htmlspecialchars(strip_tags($b['isi'])) ?>
                            </p>
                            <a href="detail-berita.php?id=<?= $b['id'] ?>" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                                <span><?= t('baca_selengkapnya', 'Baca Selengkapnya') ?></span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
            <?php
                    }
                }
            }
            if (!$foundNews):
            ?>
                <!-- BERITA DUMMY PREMIUM (JIKA DATABASE BERITA BELUM DIISI) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-6 items-center hover-card-animate">
                    <div class="w-full sm:w-48 h-40 rounded-xl bg-gradient-to-tr from-emerald-800 to-teal-600 flex-shrink-0 flex items-center justify-center text-white text-4xl relative shadow-inner">
                        <i class="fa-solid fa-users-viewfinder text-amber-300"></i>
                        <span class="absolute top-2 left-2 bg-amber-500 text-slate-900 font-bold text-[10px] px-2.5 py-0.5 rounded-full">Berita Utama</span>
                    </div>
                    <div class="flex-1">
                        <span class="text-xs text-slate-400 flex items-center gap-1.5 mb-2">
                            <i class="fa-regular fa-calendar text-emerald-600"></i> 15 Januari 2026
                        </span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 hover:text-emerald-800 transition-colors mb-2">
                            <a href="berita.php">Musyawarah Desa Klego Tetapkan Perdes APBDes TA 2026</a>
                        </h3>
                        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">
                            Pemerintah Desa bersama BPD dan tokoh masyarakat telah meninjau dan menetapkan Perdes APBDes TA 2026 yang mengedepankan transparansi.
                        </p>
                        <a href="dokumen.php?kategori=Peraturan+%26+Produk+Legislasi+Desa" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                            <span>Buka Dokumen APBDes 2026</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-6 items-center hover-card-animate">
                    <div class="w-full sm:w-48 h-40 rounded-xl bg-gradient-to-tr from-amber-600 to-yellow-500 flex-shrink-0 flex items-center justify-center text-white text-4xl relative shadow-inner">
                        <i class="fa-solid fa-comments-dollar text-slate-900"></i>
                        <span class="absolute top-2 left-2 bg-emerald-800 text-white font-bold text-[10px] px-2.5 py-0.5 rounded-full">Laporan</span>
                    </div>
                    <div class="flex-1">
                        <span class="text-xs text-slate-400 flex items-center gap-1.5 mb-2">
                            <i class="fa-regular fa-calendar text-emerald-600"></i> 31 Desember 2025
                        </span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 hover:text-emerald-800 transition-colors mb-2">
                            <a href="dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi">Laporan Pertanggungjawaban SILPA & Buku Aset 2025 Terbit</a>
                        </h3>
                        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">
                            Sebagai komitmen akuntabilitas, Pemerintah Desa Klego merilis Berita Acara Stock Opname dan Laporan SILPA akhir tahun untuk diperiksa warga.
                        </p>
                        <a href="dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                            <span>Unduh Laporan SILPA</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- 1 KOLOM AGENDA & PENGUMUMAN WIDGET -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 pb-4 border-b border-slate-100 mb-4">
                    <i class="fa-solid fa-calendar-check text-amber-500 text-xl"></i>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Agenda Kegiatan Desa</h3>
                </div>
                <div class="space-y-4">
                    <!-- AGENDA 1 -->
                    <div class="flex gap-4 items-start pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <div class="w-14 text-center rounded-xl bg-emerald-50 text-emerald-900 p-2 flex-shrink-0 border border-emerald-200/60">
                            <span class="text-lg font-extrabold block leading-none">18</span>
                            <span class="text-[10px] uppercase font-bold text-amber-700">Agt</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 hover:text-emerald-700 transition-colors cursor-pointer">Posyandu Balita & Pemeriksaan Lansia</h4>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-amber-500"></i> Balai Desa Klego (08.00 WIB)
                            </p>
                        </div>
                    </div>

                    <!-- AGENDA 2 -->
                    <div class="flex gap-4 items-start pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <div class="w-14 text-center rounded-xl bg-emerald-50 text-emerald-900 p-2 flex-shrink-0 border border-emerald-200/60">
                            <span class="text-lg font-extrabold block leading-none">22</span>
                            <span class="text-[10px] uppercase font-bold text-amber-700">Agt</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 hover:text-emerald-700 transition-colors cursor-pointer">Rapat Koordinasi Rutin RT & RW</h4>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-amber-500"></i> Aula Balai Desa (19.30 WIB)
                            </p>
                        </div>
                    </div>

                    <!-- AGENDA 3 -->
                    <div class="flex gap-4 items-start">
                        <div class="w-14 text-center rounded-xl bg-emerald-50 text-emerald-900 p-2 flex-shrink-0 border border-emerald-200/60">
                            <span class="text-lg font-extrabold block leading-none">25</span>
                            <span class="text-[10px] uppercase font-bold text-amber-700">Agt</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 hover:text-emerald-700 transition-colors cursor-pointer">Gotong Royong Kebersihan Lingkungan</h4>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-amber-500"></i> Seluruh Wilayah 5 Dusun
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WIDGET KOTAK SARAN / LAYANAN -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl p-6 text-slate-900 shadow-md relative overflow-hidden">
                <i class="fa-solid fa-handshake-angle absolute -right-4 -bottom-4 text-7xl text-amber-700/20 pointer-events-none"></i>
                <h3 class="font-heading font-extrabold text-lg text-slate-900 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Butuh Bantuan Layanan?</span>
                </h3>
                <p class="text-xs font-medium text-slate-900/90 mb-4 leading-relaxed">
                    Pengurusan Surat Keterangan Domisili, Pengantar KK/KTP, dan SKTM diselesaikan dalam 1 Hari Kerja tanpa dipungut biaya (Gratis).
                </p>
                <a href="page.php?slug=panduan-layanan" class="inline-block bg-slate-900 hover:bg-emerald-950 text-white text-xs font-bold py-2.5 px-5 rounded-xl shadow transition-colors">
                    <i class="fa-solid fa-list-check mr-1 text-amber-400"></i> Lihat Panduan & Syarat
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ================= LAYANAN ADMINISTRASI CEPAT ================= -->
<section class="py-16 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Layanan Balai Desa
            </span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mt-2">
                Pelayanan Administrasi Cepat & Gratis
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- LAYANAN 1: DOMISILI -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-emerald-700 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base">Surat Keterangan Domisili</h3>
                <span class="inline-block bg-emerald-100 text-emerald-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2">Selesai 1 Hari Kerja</span>
                <p class="text-xs text-slate-500 mt-2">Syarat: Fotokopi KTP, KK, dan Pengantar Ketua RT/RW.</p>
            </div>

            <!-- LAYANAN 2: PENGANTAR KTP/KK -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-amber-600 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base">Pengantar KTP & KK</h3>
                <span class="inline-block bg-amber-100 text-amber-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2">Selesai 1 Hari Kerja</span>
                <p class="text-xs text-slate-500 mt-2">Syarat: Blanko Formulir F1.01 atau Kartu Keluarga (KK) Lama.</p>
            </div>

            <!-- LAYANAN 3: SKTM -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-blue-700 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base">Surat Ket. Tidak Mampu</h3>
                <span class="inline-block bg-blue-100 text-blue-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2">Selesai 1 Hari Kerja</span>
                <p class="text-xs text-slate-500 mt-2">Syarat: Fotokopi KTP, KK, dan Pengantar Resmi RT/RW.</p>
            </div>

            <!-- LAYANAN 4: USAHA SKU -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-purple-700 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base">Surat Keterangan Usaha (SKU)</h3>
                <span class="inline-block bg-purple-100 text-purple-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2">Selesai 1 Hari Kerja</span>
                <p class="text-xs text-slate-500 mt-2">Syarat: Fotokopi KTP, KK, Pengantar RT/RW & Bukti Usaha.</p>
            </div>

        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>
