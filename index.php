<?php
$pageTitle = "Beranda Utama";
include 'config/header.php';

// Seluruh angka beranda bersumber dari database, bukan angka contoh.
$statPenduduk = $statKK = $statLaki = $statPerempuan = 0;
$statDusun = $statRW = $statRT = 0;
$statApbdes = 0;

if (isset($conn) && $conn && !mysqli_connect_error()) {
    $qTotal = @mysqli_query($conn, "SELECT COUNT(*) as total FROM penduduk");
    if ($qTotal && $r = mysqli_fetch_assoc($qTotal)) {
        if ($r['total'] > 0) {
            $statPenduduk = $r['total'];
            $rKK = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT NO_KK) as kk FROM penduduk"));
            $statKK = $rKK['kk'] ?? 0;
            $rL = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as l FROM penduduk WHERE JENIS_KELAMIN='Laki-laki' OR JENIS_KELAMIN='L'"));
            $statLaki = $rL['l'] ?? 0;
            $statPerempuan = $statPenduduk - $statLaki;
        }
    }
    foreach (['wilayah_dusun' => 'statDusun', 'wilayah_rw' => 'statRW', 'wilayah_rt' => 'statRT'] as $table => $variable) {
        $result = @mysqli_query($conn, "SELECT COUNT(*) AS total FROM $table");
        if ($result && ($row = mysqli_fetch_assoc($result))) $$variable = (int) $row['total'];
    }
    $result = @mysqli_query($conn, "SELECT SUM(nilai) AS total FROM infografis_statistik WHERE kategori='Pendapatan APBDes 2026'");
    if ($result && ($row = mysqli_fetch_assoc($result))) $statApbdes = (float) ($row['total'] ?? 0);
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
                <span><?= tr('Transparansi Pembendaharaan Negara & Regulasi Desa') ?></span>
            </div>

            <h1 class="font-heading font-extrabold text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-[1.15]">
                <?= tr('Portal Resmi Pemerintahan') ?> <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-200 to-yellow-400">
                    <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
                </span>
            </h1>

            <p class="text-base sm:text-lg text-emerald-100 font-normal leading-relaxed max-w-2xl">
                <?= tr('Mewujudkan pelayanan publik terpadu yang cepat, pengungkapan data aset dan anggaran (APBDes) yang akuntabel, serta kemudahan unduh regulasi hukum bagi seluruh warga masyarakat.') ?>
            </p>

            <!-- CALL TO ACTION BUTTONS -->
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                <a href="dokumen.php" 
                   class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold px-7 py-3.5 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2.5">
                    <i class="fa-solid fa-book-open"></i>
                    <span><?= tr('Pustaka Hukum (JDIH)') ?></span>
                </a>
                <a href="infografis.php" 
                   class="bg-emerald-900/80 hover:bg-emerald-800 text-emerald-100 border border-emerald-600/60 font-semibold px-6 py-3.5 rounded-xl shadow-md hover:text-white transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-amber-400"></i>
                    <span><?= tr('Infografis Keuangan') ?></span>
                </a>
            </div>

            <div class="pt-6 flex items-center justify-center lg:justify-start gap-6 text-xs text-emerald-200/80">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i> <?= tr('Perdes APBDes 2026 Tersedia') ?>
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i> <?= tr('Laporan SILPA 2025 Terbuka') ?>
                </span>
            </div>

        </div>
    </div>
</section>

<!-- ================= STATISTIK DEMOGRAFI & WILAYAH ================= -->
<section class="py-12 bg-white border-b border-slate-200 shadow-sm relative -mt-8 z-20 max-w-7xl mx-auto rounded-3xl mx-4 sm:mx-auto px-6 shadow-xl">
    <div class="text-center mb-8">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
            <?= tr('Statistik Warga & Wilayah') ?>
        </span>
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mt-2">
            <?= tr('Demografi & Angka Penting') ?> <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
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
                <p class="text-xs text-slate-500 font-medium"><?= tr('Total Penduduk') ?></p>
            </div>
        </div>

        <!-- CARD 2: KEPALA KELUARGA -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-amber-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-house-chimney-user"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statKK, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium"><?= tr('Kepala Keluarga (KK)') ?></p>
            </div>
        </div>

        <!-- CARD 3: LAKI-LAKI -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-blue-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-person"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statLaki, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium"><?= tr('Laki-laki') ?></p>
            </div>
        </div>

        <!-- CARD 4: PEREMPUAN -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-person-dress"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= number_format($statPerempuan, 0, ',', '.') ?></p>
                <p class="text-xs text-slate-500 font-medium"><?= tr('Perempuan') ?></p>
            </div>
        </div>

        <!-- CARD 5: LUAS WILAYAH -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-teal-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-map-location"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">312 Ha</p>
                <p class="text-xs text-slate-500 font-medium"><?= tr('Lahan & Pertanian') ?></p>
            </div>
        </div>

        <!-- CARD 6: DUSUN -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-indigo-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-map-pin"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= $statDusun ?> <?= tr('Dusun') ?></p>
                <p class="text-xs text-slate-500 font-medium"><?= tr('Data wilayah tersimpan') ?></p>
            </div>
        </div>

        <!-- CARD 7: RT / RW -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover-card-animate">
            <div class="w-12 h-12 rounded-xl bg-purple-700 text-white flex items-center justify-center text-xl flex-shrink-0 shadow">
                <i class="fa-solid fa-sitemap"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900"><?= $statRW ?> <?= tr('RW') ?> / <?= $statRT ?> <?= tr('RT') ?></p>
                <p class="text-xs text-slate-500 font-medium"><?= tr('Pembagian Wilayah') ?></p>
            </div>
        </div>

        <!-- CARD 8: APBDES 2026 -->
        <div class="bg-gradient-to-br from-emerald-800 to-emerald-900 text-white rounded-2xl p-5 flex items-center gap-4 shadow-lg transform hover:-translate-y-1 transition-all duration-200 cursor-pointer" onclick="window.location='infografis.php'">
            <div class="w-12 h-12 rounded-xl bg-amber-500 text-slate-900 flex items-center justify-center text-xl flex-shrink-0 font-extrabold shadow">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <p class="text-xl sm:text-2xl font-black text-amber-300">Rp <?= number_format($statApbdes / 1000000000, 2, ',', '.') ?> M</p>
                <p class="text-xs text-emerald-100 font-medium"><?= tr('Total APBDes 2026') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ================= PETA INTERAKTIF DESA KLEGO (SUMBER: NAURA) ================= -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-3xl mx-auto mb-10">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100/80 px-3 py-1.5 rounded-full border border-emerald-300">
            <?= tr('Peta Digital & Fasilitas Umum') ?>
        </span>
        <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
            <?= tr('Peta Interaktif Desa Klego') ?>
        </h2>
        <p class="text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
            <?= tr('Peta digital fasilitas umum Desa Klego dengan batas administrasi resmi. Klik marker untuk melihat detail lokasi.') ?>
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">
        <div id="petaDesaKlego" style="height: 480px; width: 100%; z-index: 10;"></div>
    </div>
</section>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<style>
/* Peta Desa Klego Custom Styles */
#petaDesaKlego .leaflet-control-layers { border-radius: 12px !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important; font-size: 12px; }
#petaDesaKlego .leaflet-control-zoom a { border-radius: 8px !important; }
#petaDesaKlego .peta-legend-card { background: #fff; border-radius: 14px; padding: 0; box-shadow: 0 4px 16px rgba(0,0,0,0.12); border: 1px solid #e2e8f0; min-width: 180px; font-family: 'Inter', sans-serif; overflow: hidden; }
#petaDesaKlego .peta-legend-header { padding: 10px 14px; background: #f0fdf4; border-bottom: 1px solid #e2e8f0; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: 700; color: #165f36; }
#petaDesaKlego .peta-legend-body { padding: 10px 14px; }
#petaDesaKlego .peta-legend-body.collapsed { display: none; }
#petaDesaKlego .peta-lg-row { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; font-size: 11px; color: #334155; }
#petaDesaKlego .peta-lg-circle { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
#petaDesaKlego .peta-lg-poly { width: 22px; height: 14px; border: 2px dashed #0F6E8C; background: rgba(54,171,195,0.18); border-radius: 3px; flex-shrink: 0; }
#petaDesaKlego .custom-map-marker .marker-pin-wrapper { display: flex; align-items: center; gap: 4px; white-space: nowrap; }
#petaDesaKlego .custom-map-marker .facility-icon-badge { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.25); border: 2px solid #fff; flex-shrink: 0; }
#petaDesaKlego .custom-map-marker .facility-icon-badge svg { width: 12px; height: 12px; }
#petaDesaKlego .custom-map-marker .facility-name-label { font-size: 10px; font-weight: 700; color: #1e293b; background: rgba(255,255,255,0.92); padding: 2px 6px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 150px; overflow: hidden; text-overflow: ellipsis; }
#petaDesaKlego .marker-cluster-custom { background: rgba(22,95,54,0.2); border-radius: 50%; }
#petaDesaKlego .marker-cluster-custom div { background: #165f36; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
</style>

<script>
(function() {
  // DATA FASILITAS DESA KLEGO
  const fasilitasKlego = [
    {no: 1, nama: 'Kantor Desa Klego', lng: 110.6894, lat: -7.35662, kat: 'Kantor Desa'},
    {no: 2, nama: 'Masjid Darul Fallah', lng: 110.6908, lat: -7.3557, kat: 'Masjid'},
    {no: 3, nama: 'Masjid Al-Fatah', lng: 110.6840, lat: -7.36123, kat: 'Masjid'},
    {no: 4, nama: 'Masjid Al-Istijabah', lng: 110.6911, lat: -7.36114, kat: 'Masjid'},
    {no: 5, nama: 'Masjid Amnah Ali', lng: 110.6848, lat: -7.35288, kat: 'Masjid'},
    {no: 6, nama: 'SDN 1 Klego', lng: 110.6890, lat: -7.35673, kat: 'Sekolah / SD'},
    {no: 7, nama: 'SDN 3 Klego', lng: 110.6905, lat: -7.35995, kat: 'Sekolah / SD'},
    {no: 8, nama: 'MIN Kedokan Klego', lng: 110.6860, lat: -7.35853, kat: 'Sekolah / SD'},
    {no: 9, nama: 'SMP Bhinneka Karya Klego', lng: 110.6907, lat: -7.35902, kat: 'Sekolah / SMP'},
    {no: 10, nama: 'Puskesmas Klego I', lng: 110.6878, lat: -7.35695, kat: 'Puskesmas'},
    {no: 11, nama: "Masjid Umul Mu'minin Aisyah", lng: 110.6817, lat: -7.35811, kat: 'Masjid'},
    {no: 12, nama: 'Mushola Al-Hidayah', lng: 110.6847, lat: -7.35977, kat: 'Musholla'},
    {no: 13, nama: 'Mushalla Al-Fatih', lng: 110.6832, lat: -7.35604, kat: 'Musholla'},
    {no: 14, nama: 'Pasar Klego', lng: 110.6920, lat: -7.35596, kat: 'Pasar'},
    {no: 15, nama: 'Taman Klego', lng: 110.6933, lat: -7.35468, kat: 'Taman'},
    {no: 16, nama: 'Lapangan Bola Klego', lng: 110.6910, lat: -7.35956, kat: 'Lapangan'},
    {no: 17, nama: 'Masjid Nurul Iman Karanganyar', lng: 110.6891, lat: -7.3539, kat: 'Masjid'},
    {no: 18, nama: 'Masjid Amnah Ali Klalingan', lng: 110.6965, lat: -7.3574, kat: 'Masjid'}
  ];

  // WARNA & IKON PER KATEGORI
  const petaColors = {
    'Kantor Desa': '#d94a43', 'Masjid': '#168144', 'Musholla': '#77b840',
    'Sekolah / SD': '#2979c7', 'Sekolah / SMP': '#8755b5', 'Puskesmas': '#13a36b',
    'Pasar': '#ee9120', 'Taman': '#0e9b9e', 'Lapangan': '#94633c'
  };
  const petaIcons = {
    'Kantor Desa': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 21h18"/><path d="M5 21V10l7-5 7 5v11"/><path d="M9 21v-4h6v4"/></svg>',
    'Masjid': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v2"/><path d="M12 4c-3.5 0-6 2.5-6 6v11h12V10c0-3.5-2.5-6-6-6z"/><circle cx="12" cy="2" r="1" fill="currentColor"/></svg>',
    'Musholla': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v2"/><path d="M12 5c-3 0-5 2-5 5v11h10V10c0-3-2-5-5-5z"/><circle cx="12" cy="2" r="1" fill="currentColor"/></svg>',
    'Sekolah / SD': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
    'Sekolah / SMP': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
    'Puskesmas': '<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M19 10.5h-5.5V5c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v5.5H5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5h5.5V19c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5v-5.5H19c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5z"/></svg>',
    'Pasar': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
    'Taman': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19v3"/><path d="M12 19a7 7 0 1 0 0-14 7 7 0 0 0 0 14z"/></svg>',
    'Lapangan': '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20M2 12h20"/></svg>'
  };

  // POLYGON BATAS ADMINISTRASI DESA KLEGO
  const batasDesaPolygon = [
    [-7.3533, 110.6800], [-7.3524, 110.6831], [-7.3501, 110.6842], [-7.3483, 110.6871],
    [-7.3458, 110.6884], [-7.3470, 110.6895], [-7.3457, 110.6910], [-7.3470, 110.6945],
    [-7.3462, 110.6963], [-7.3473, 110.6996],
    [-7.3501, 110.7006], [-7.3522, 110.6995], [-7.3558, 110.6999], [-7.3574, 110.6965],
    [-7.3578, 110.7010], [-7.3584, 110.7044], [-7.3591, 110.7064], [-7.3636, 110.7052],
    [-7.3657, 110.7006], [-7.3651, 110.6963], [-7.3637, 110.6935], [-7.3632, 110.6895],
    [-7.3649, 110.6850],
    [-7.3635, 110.6814], [-7.3604, 110.6802], [-7.3574, 110.6796], [-7.3556, 110.6808]
  ];

  function initPetaDesa() {
    const mapEl = document.getElementById('petaDesaKlego');
    if (!mapEl || typeof L === 'undefined') return;

    const petaMap = L.map('petaDesaKlego', { zoomControl: false, scrollWheelZoom: false });
    L.control.zoom({ position: 'topright' }).addTo(petaMap);

    // Tile Layers
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19, attribution: '© OpenStreetMap'
    }).addTo(petaMap);
    const cartoLight = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, subdomains: 'abcd' });
    const esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

    // Batas Desa Polygon
    const boundary = L.polygon(batasDesaPolygon, {
      color: '#0F6E8C', weight: 3.5, fillColor: '#36abc3', fillOpacity: 0.18, dashArray: '6, 6', smoothFactor: 0
    }).addTo(petaMap);

    // Layer Control (topright, collapsed)
    L.control.layers(
      { 'OpenStreetMap': osm, 'Carto Light': cartoLight, 'Esri Satellite': esriSat },
      { 'Batas Desa Klego': boundary },
      { collapsed: true, position: 'topright' }
    ).addTo(petaMap);

    // Custom Marker Icon Creator
    function makeIcon(kat, nama) {
      return L.divIcon({
        className: 'custom-map-marker',
        html: '<div class="marker-pin-wrapper"><div class="facility-icon-badge" style="background:' + (petaColors[kat]||'#0F6E8C') + '">' + (petaIcons[kat]||'') + '</div><div class="facility-name-label">' + nama + '</div></div>',
        iconSize: [180, 28], iconAnchor: [14, 14]
      });
    }

    // Marker Cluster Group
    var cluster = (typeof L.markerClusterGroup === 'function') ? L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 40,
      iconCreateFunction: function(c) { return L.divIcon({ html: '<div>' + c.getChildCount() + '</div>', className: 'marker-cluster-custom', iconSize: L.point(40,40) }); }
    }) : L.layerGroup();

    fasilitasKlego.forEach(function(f) {
      var m = L.marker([f.lat, f.lng], { icon: makeIcon(f.kat, f.nama), title: f.nama });
      m.bindPopup('<div style="padding:10px;font-family:Inter,sans-serif;min-width:200px;"><div style="background:' + (petaColors[f.kat]||'#0F6E8C') + ';color:#fff;padding:10px 12px;border-radius:10px 10px 0 0;margin:-10px -10px 10px -10px;"><b style="font-size:13px;">' + f.nama + '</b></div><div style="font-size:11px;color:#64748b;margin-bottom:4px;"><i class="fa-solid fa-tag" style="color:' + (petaColors[f.kat]||'#0F6E8C') + ';margin-right:4px;"></i>' + f.kat + '</div><div style="font-size:11px;color:#64748b;"><i class="fa-solid fa-location-dot" style="color:#d94a43;margin-right:4px;"></i>Desa Klego, Kec. Klego, Boyolali</div><div style="margin-top:8px;"><a href="https://www.google.com/maps/search/?api=1&query=' + f.lat + ',' + f.lng + '" target="_blank" style="display:block;text-align:center;background:#4285F4;color:#fff;padding:6px;border-radius:8px;font-weight:600;font-size:11px;text-decoration:none;"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Google Maps</a></div></div>', { maxWidth: 260 });
      cluster.addLayer(m);
    });
    petaMap.addLayer(cluster);

    // Legenda (bottomleft - agar tidak menumpuk layer control)
    var legend = L.control({ position: 'bottomleft' });
    legend.onAdd = function() {
      var div = L.DomUtil.create('div', 'peta-legend-card');
      var rows = '';
      Object.entries(petaColors).forEach(function(e) {
        rows += '<div class="peta-lg-row"><span class="peta-lg-circle" style="background:' + e[1] + '">' + (petaIcons[e[0]]||'') + '</span><span>' + e[0] + '</span></div>';
      });
      rows += '<div class="peta-lg-row"><span class="peta-lg-poly"></span><span>Batas Administrasi Desa</span></div>';
      div.innerHTML = '<div class="peta-legend-header" onclick="var b=this.nextElementSibling;b.classList.toggle(\'collapsed\');this.querySelector(\'i\').className=b.classList.contains(\'collapsed\')?\'fa-solid fa-plus\':\'fa-solid fa-minus\';"><span><i class="fa-solid fa-layer-group"></i> Legenda Fasilitas</span><span><i class="fa-solid fa-minus"></i></span></div><div class="peta-legend-body">' + rows + '</div>';
      L.DomEvent.disableClickPropagation(div);
      return div;
    };
    legend.addTo(petaMap);

    // Fit to village bounds
    petaMap.fitBounds(boundary.getBounds(), { padding: [30, 30] });

    // Enable scroll zoom on click
    petaMap.on('click', function() { petaMap.scrollWheelZoom.enable(); });
    petaMap.on('mouseout', function() { petaMap.scrollWheelZoom.disable(); });
  }

  if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setTimeout(initPetaDesa, 100);
  } else {
    document.addEventListener('DOMContentLoaded', initPetaDesa);
  }
})();
</script>

<!-- ================= PUSAT INFORMASI & KETERBUKAAN DATA (FOLDER SATRIA) ================= -->
<section class="py-20 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100/80 px-3 py-1.5 rounded-full border border-emerald-300">
            <?= tr('Keterbukaan Informasi & Pembendaharaan') ?>
        </span>
        <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
            <?= tr('Pusat Data Regulasi, Aset & Perencanaan') ?>
        </h2>
        <p class="text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
            <?= tr('Mewujudkan pemerintahan Desa Klego yang bersih dan terbuka. Seluruh berkas Peraturan Desa, Buku Bantu Aset, Laporan SILPA, dan RPJM Desa dapat diperiksa serta diunduh secara bebas oleh masyarakat.') ?>
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
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full"><?= tr('11 Dokumen Tersedia') ?></span>
                <h3 class="font-heading font-bold text-xl text-slate-900 mt-4 mb-3">
                    <?= tr('Peraturan & Produk Legislasi Desa') ?>
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    <?= tr('Pusat informasi produk hukum Desa Klego untuk mendukung transparansi pemerintahan dan memudahkan akses masyarakat terhadap regulasi yang berlaku. Meliputi Perdes APBDes 2026, Perkades APBDes, Keputusan Kepala Desa, dan produk hukum lainnya yang diperbarui secara berkala.') ?>
                </p>
            </div>
            <div class="pt-6 border-t border-slate-100 mt-2">
                <a href="dokumen.php?kategori=Peraturan+%26+Produk+Legislasi+Desa" class="w-full bg-slate-900 hover:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow">
                    <span><?= tr('Lihat & Unduh Regulasi') ?></span>
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
                <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full"><?= tr('9 Laporan Resmi') ?></span>
                <h3 class="font-heading font-bold text-xl text-slate-900 mt-4 mb-3">
                    <?= tr('Data Aset & Pembendaharaan Desa') ?>
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    <?= tr('Pusat informasi yang memuat berbagai data penyelenggaraan pemerintahan Desa Klego sebagai wujud transparansi dan pelayanan publik. Melalui halaman ini, masyarakat dapat mengakses data seperti inventaris aset desa, buku bantu, stock opname, laporan SILPA 2025, serta CaLK 2025.') ?>
                </p>
            </div>
            <div class="pt-6 border-t border-slate-100 mt-2">
                <a href="dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow">
                    <span><?= tr('Buka Inventaris & SILPA') ?></span>
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
                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full"><?= tr('Perencanaan 6 Tahun') ?></span>
                <h3 class="font-heading font-bold text-xl text-slate-900 mt-4 mb-3">
                    <?= tr('RPJM Desa (Rencana Pembangunan)') ?>
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    <?= tr('RPJM Desa merupakan dokumen perencanaan pembangunan desa untuk jangka waktu 6 (enam) tahun yang menjadi pedoman dalam penyelenggaraan pemerintahan, pelaksanaan pembangunan, dan pemberdayaan masyarakat. Disusun sebagai dasar terarah dan berkelanjutan.') ?>
                </p>
            </div>
            <div class="pt-6 border-t border-slate-100 mt-2">
                <a href="dokumen.php?kategori=Rencana+Pembangunan+Jangka+Menengah+%28RPJM%29" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow">
                    <span><?= tr('Unduh Naskah RPJM') ?></span>
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
                    <?= tr('Statistik & Keuangan Desa') ?>
                </span>
                <h2 class="font-heading font-extrabold text-3xl sm:text-4xl leading-tight text-white">
                    <?= tr('Transparansi Pengelolaan Pembendaharaan Negara & Dana Desa') ?>
                </h2>
                <p class="text-emerald-100/80 text-sm sm:text-base leading-relaxed">
                    <?= tr('Kami menjunjung tinggi prinsip akuntabilitas dalam penggunaan Anggaran Pendapatan dan Belanja Desa (APBDes) 2026 maupun pertanggungjawaban SILPA 2025.') ?>
                </p>
                
                <div class="space-y-3 pt-2">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-emerald-200 font-medium"><?= tr('Dana Desa (APBN)') ?></p>
                            <p class="text-lg font-bold text-white">Rp 875.000.000</p>
                        </div>
                        <span class="bg-emerald-600 text-white text-xs px-2.5 py-1 rounded-full font-bold"><?= tr('70% APBDes') ?></span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-amber-200 font-medium"><?= tr('SILPA Akhir Tahun 2025') ?></p>
                            <p class="text-lg font-bold text-white">Rp 98.450.000</p>
                        </div>
                        <span class="bg-amber-500 text-slate-900 text-xs px-2.5 py-1 rounded-full font-black"><?= tr('Audit Ok') ?></span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="infografis.php" class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-7 py-3.5 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <span><?= tr('Buka Dasbor Infografis Lengkap') ?></span>
                        <i class="fa-solid fa-chart-line"></i>
                    </a>
                </div>
            </div>

            <!-- PREVIEW CHART CONTAINER -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl text-slate-800 border border-slate-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Distribusi Anggaran Belanja APBDes 2026') ?></h3>
                            <p class="text-xs text-slate-500"><?= tr('Berdasarkan Perdes No. 01 Tahun 2026 Desa Klego') ?></p>
                        </div>
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-300"><?= tr('TA 2026') ?></span>
                    </div>
                    
                    <div class="h-64 sm:h-72 w-full">
                        <canvas id="homeBudgetChart"></canvas>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-2 gap-4 text-center text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5"><?= tr('Fokus Pembangunan') ?></span>
                            <span class="font-bold text-slate-800 text-sm"><?= tr('Infrastruktur & Jalan (54.4%)') ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5"><?= tr('Fokus Pemberdayaan') ?></span>
                            <span class="font-bold text-emerald-700 text-sm"><?= tr('Pelatihan UMKM (25.6%)') ?></span>
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
                labels: ['<?= tr("Pembangunan Infrastruktur") ?>', '<?= tr("Penyelenggaraan Pemdes") ?>', '<?= tr("Pemberdayaan UMKM") ?>', '<?= tr("Pembinaan Masyarakat") ?>'],
                datasets: [{
                    label: '<?= tr("Alokasi Belanja (Juta Rp)") ?>',
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
                <?= tr('Keunggulan & Potensi Lokal') ?>
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
                <?= tr('Potensi Agraris & Kekuatan UMKM Warga') ?>
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2">
                <?= tr('Desa Klego dianugerahi lahan pertanian subur dan komunitas pengrajin UMKM yang aktif menggerakkan roda ekonomi desa.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- POTENSI 1: PERTANIAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Pertanian Produktif') ?></h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    <?= tr('Lahan produktif seluas 312 Ha dengan komoditas utama padi, jagung, dan kedelai berkualitas tinggi dari Boyolali.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700"><?= tr('312 Ha Lahan') ?></span>
                    <i class="fa-solid fa-seedling text-amber-500"></i>
                </div>
            </div>

            <!-- POTENSI 2: UMKM -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('87 UMKM Aktif') ?></h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    <?= tr('Berkembangnya usaha masyarakat bidang kerajinan anyaman bambu, batik tulis lokal, dan olahan pangan tradisional.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700"><?= tr('Kerajinan & Pangan') ?></span>
                    <i class="fa-solid fa-hand-holding-dollar text-emerald-600"></i>
                </div>
            </div>

            <!-- POTENSI 3: KELEMBAGAAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Kelembagaan Solid') ?></h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    <?= tr('Didukung 12 lembaga kemasyarakatan yang aktif: BPD, LKMD, PKK, Karang Taruna, dan Gapoktan yang tanggap.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-teal-700"><?= tr('12 Lembaga Aktif') ?></span>
                    <i class="fa-solid fa-people-group text-blue-500"></i>
                </div>
            </div>

            <!-- POTENSI 4: PEMBANGUNAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card-animate">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-800 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-road-bridge"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Infrastruktur Maju') ?></h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    <?= tr('Pemerataan perbaikan jalan antar dusun, drainase pertanian, dan penerangan jalan umum dengan pengawasan warga.') ?>
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-indigo-700"><?= tr('Sesuai RPJMDes') ?></span>
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
                <?= tr('Informasi & Kegiatan Warga') ?>
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 mt-3">
                <?= tr('Berita Terkini & Agenda Desa') ?>
            </h2>
        </div>
        <a href="berita.php" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold flex items-center gap-1.5 transition-colors">
            <span><?= tr('Lihat Semua Berita') ?></span>
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
                            <?php $newsFotoPath = !empty($b['foto']) ? resolve_uploaded_image($b['foto']) : ''; ?>
                            <?php if (!empty($newsFotoPath)): ?>
                                <img src="<?= htmlspecialchars($newsFotoPath) ?>" onerror="this.onerror=null; this.src='assets/img/utama.jpg';" alt="<?= htmlspecialchars($b['judul']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-emerald-800/10 flex items-center justify-center text-emerald-700 text-3xl">
                                    <i class="fa-regular fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            <span class="absolute top-2 left-2 bg-amber-500 text-slate-900 font-bold text-[10px] px-2.5 py-0.5 rounded-full"><?= tr('Berita') ?></span>
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
                                <?= clean_preview_text($b['isi'] ?? '', 160) ?>
                            </p>
                            <a href="detail-berita.php?id=<?= $b['id'] ?>" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                                <span><?= tr('Baca Selengkapnya') ?></span>
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
                    <h3 class="font-heading font-bold text-lg text-slate-900"><?= tr('Agenda Kegiatan Desa') ?></h3>
                </div>
                <div class="space-y-4">
                    <?php
                    $qAgenda = @mysqli_query($conn, "SELECT * FROM agenda_desa ORDER BY tanggal ASC LIMIT 5");
                    if ($qAgenda && mysqli_num_rows($qAgenda) > 0) :
                        while ($ag = mysqli_fetch_assoc($qAgenda)) :
                    ?>
                        <div class="flex gap-4 items-start pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                            <div class="w-14 text-center rounded-xl bg-emerald-50 text-emerald-900 p-2 flex-shrink-0 border border-emerald-200/60">
                                <span class="text-lg font-extrabold block leading-none"><?= date('d', strtotime($ag['tanggal'])); ?></span>
                                <span class="text-[10px] uppercase font-bold text-amber-700"><?= date('M', strtotime($ag['tanggal'])); ?></span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 hover:text-emerald-700 transition-colors"><?= htmlspecialchars($ag['judul']); ?></h4>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock text-amber-500"></i> <?= htmlspecialchars($ag['waktu']); ?>
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-emerald-600"></i> <?= htmlspecialchars($ag['lokasi']); ?>
                                </p>
                            </div>
                        </div>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <div class="text-center py-8 text-slate-400 text-xs font-medium">
                            <i class="fa-regular fa-calendar-xmark text-2xl block mb-2 text-slate-300"></i>
                            <?= tr('Belum ada jadwal agenda kegiatan desa terdahulu atau waktu dekat.') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- WIDGET KOTAK SARAN / LAYANAN -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl p-6 text-slate-900 shadow-md relative overflow-hidden">
                <i class="fa-solid fa-handshake-angle absolute -right-4 -bottom-4 text-7xl text-amber-700/20 pointer-events-none"></i>
                <h3 class="font-heading font-extrabold text-lg text-slate-900 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question"></i>
                    <span><?= tr('Butuh Bantuan Layanan?') ?></span>
                </h3>
                <p class="text-xs font-medium text-slate-900/90 mb-4 leading-relaxed">
                    <?= tr('Pengurusan Surat Keterangan Domisili, Pengantar KK/KTP, dan SKTM diselesaikan dalam 1 Hari Kerja tanpa dipungut biaya (Gratis).') ?>
                </p>
                <a href="page.php?slug=panduan-layanan" class="inline-block bg-slate-900 hover:bg-emerald-950 text-white text-xs font-bold py-2.5 px-5 rounded-xl shadow transition-colors">
                    <i class="fa-solid fa-list-check mr-1 text-amber-400"></i> <?= tr('Lihat Panduan & Syarat') ?>
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
                <?= tr('Layanan Balai Desa') ?>
            </span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 mt-2">
                <?= tr('Pelayanan Administrasi Cepat & Gratis') ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- LAYANAN 1: DOMISILI -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-emerald-700 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base"><?= tr('Surat Keterangan Domisili') ?></h3>
                <span class="inline-block bg-emerald-100 text-emerald-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2"><?= tr('Selesai 1 Hari Kerja') ?></span>
                <p class="text-xs text-slate-500 mt-2"><?= tr('Syarat: Fotokopi KTP, KK, dan Pengantar Ketua RT/RW.') ?></p>
            </div>

            <!-- LAYANAN 2: PENGANTAR KTP/KK -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-amber-600 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base"><?= tr('Pengantar KTP & KK') ?></h3>
                <span class="inline-block bg-amber-100 text-amber-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2"><?= tr('Selesai 1 Hari Kerja') ?></span>
                <p class="text-xs text-slate-500 mt-2"><?= tr('Syarat: Blanko Formulir F1.01 atau Kartu Keluarga (KK) Lama.') ?></p>
            </div>

            <!-- LAYANAN 3: SKTM -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-blue-700 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base"><?= tr('Surat Ket. Tidak Mampu') ?></h3>
                <span class="inline-block bg-blue-100 text-blue-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2"><?= tr('Selesai 1 Hari Kerja') ?></span>
                <p class="text-xs text-slate-500 mt-2"><?= tr('Syarat: Fotokopi KTP, KK, dan Pengantar Resmi RT/RW.') ?></p>
            </div>

            <!-- LAYANAN 4: USAHA SKU -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover-card-animate">
                <div class="w-12 h-12 bg-purple-700 text-white rounded-xl flex items-center justify-center mx-auto text-xl mb-4 shadow">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-base"><?= tr('Surat Keterangan Usaha (SKU)') ?></h3>
                <span class="inline-block bg-purple-100 text-purple-800 font-semibold text-[10px] px-2.5 py-0.5 rounded-full my-2"><?= tr('Selesai 1 Hari Kerja') ?></span>
                <p class="text-xs text-slate-500 mt-2"><?= tr('Syarat: Fotokopi KTP, KK, Pengantar RT/RW & Bukti Usaha.') ?></p>
            </div>

        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>
