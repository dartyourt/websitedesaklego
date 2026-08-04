<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin CMS - <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?></title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#165f36',
              secondary: '#c4891f'
            }
          }
        }
      }
    </script>
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <!-- TOP HEADER -->
    <header class="bg-[#165f36] text-white shadow-lg border-b-4 border-amber-500 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full p-1 border border-amber-400 flex items-center justify-center shadow">
                    <img src="../logoboyolali.png" alt="Logo Boyolali" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Dasbor Admin <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?></h1>
                    <p class="text-xs text-amber-300 font-medium">Sistem Informasi & Portal Resmi Pemerintahan Desa</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="../index.php" target="_blank" class="bg-emerald-800 hover:bg-emerald-700 text-emerald-100 text-xs font-semibold px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 shadow">
                    <i class="fa-solid fa-globe"></i> Lihat Website
                </a>
                <a href="logout.php" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow flex items-center gap-1.5">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">
        
        <!-- SECTION 1: MANAJEMEN PORTAL & KONTEN PUBLIK -->
        <div>
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-layer-group text-amber-600 text-xl"></i>
                <h2 class="font-extrabold text-xl text-slate-900">Manajemen Portal & Tata Kelola Konten Publik</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- MENU NAVBAR MANAGER -->
                <a href="menu.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-400 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-2xl mb-4 group-hover:bg-emerald-700 group-hover:text-white transition-colors shadow-inner">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-emerald-800 transition-colors">Menu Navigasi Website</h3>
                    <p class="text-xs text-slate-500 mt-1">Atur struktur menu navigasi utama & dropdown beranda website depan.</p>
                    <span class="inline-block mt-4 text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">Kelola Navigasi &rarr;</span>
                </a>

                <!-- PAGE BUILDER MANAGER -->
                <a href="halaman.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-400 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-2xl mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors shadow-inner">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-amber-700 transition-colors">Informasi & Layanan Publik</h3>
                    <p class="text-xs text-slate-500 mt-1">Kelola halaman informasi (Sejarah, Visi Misi, Potensi) dengan editor visual.</p>
                    <span class="inline-block mt-4 text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Kelola Halaman &rarr;</span>
                </a>

                <!-- DOKUMEN PUBLIK & JDIH -->
                <a href="dokumen.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-400 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-2xl mb-4 group-hover:bg-blue-700 group-hover:text-white transition-colors shadow-inner">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-blue-700 transition-colors">Dokumen Regulasi & Aset</h3>
                    <p class="text-xs text-slate-500 mt-1">Manajemen file Peraturan Desa, Buku Bantu Aset, SILPA 2025, dan RPJM.</p>
                    <span class="inline-block mt-4 text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">Kelola Pustaka &rarr;</span>
                </a>

                <!-- INFOGRAFIS & PEMBENDAHARAAN -->
                <a href="infografis.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-400 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center text-2xl mb-4 group-hover:bg-teal-700 group-hover:text-white transition-colors shadow-inner">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-teal-700 transition-colors">Infografis & Anggaran</h3>
                    <p class="text-xs text-slate-500 mt-1">Sesuaikan angka APBDes, statistik pembendaharaan negara, dan SILPA.</p>
                    <span class="inline-block mt-4 text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">Kelola Grafik &rarr;</span>
                </a>

                <!-- PUSAT LOKALISASI & TERJEMAHAN MANUAL (WPML STYLE) -->
                <a href="bahasa.php" class="bg-gradient-to-br from-amber-50 to-white p-6 rounded-2xl border-2 border-amber-300 shadow-sm hover:shadow-lg hover:border-amber-500 transition-all group lg:col-span-2">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500 text-slate-900 flex items-center justify-center text-2xl flex-shrink-0 group-hover:bg-emerald-800 group-hover:text-amber-300 transition-colors shadow-md">
                            <i class="fa-solid fa-language text-3xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono uppercase font-bold text-amber-800 bg-amber-200/80 px-2.5 py-0.5 rounded-full">Multilingual Support (Human Translator)</span>
                            <h3 class="font-extrabold text-lg text-slate-900 mt-1 group-hover:text-emerald-800 transition-colors">Pusat Terjemahan Bahasa (Inggris 🇬🇧 & Jepang 🇯🇵)</h3>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Kelola daftar bahasa website dan terjemahkan seluruh konten, menu navbar, hingga tombol secara manual (berdampingan) oleh human translator dengan editor visual yang mudah!</p>
                            <span class="inline-block mt-3 text-xs font-extrabold text-white bg-[#165f36] px-4 py-1.5 rounded-xl shadow-xs">Buka Studio Terjemahan &rarr;</span>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- SECTION 2: ADMINISTRASI DATA DESA & PEMERINTAHAN -->
        <div>
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-folder-tree text-emerald-700 text-xl"></i>
                <h2 class="font-extrabold text-xl text-slate-900">Administrasi Data Pemerintahan & Warga</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                
                <a href="penduduk.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Data Penduduk</h3>
                        <p class="text-xs text-slate-500 mt-1">Kelola arsip KTP, KK, NIK, dan kependudukan desa.</p>
                    </div>
                </a>

                <a href="keuangan.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Keuangan Desa</h3>
                        <p class="text-xs text-slate-500 mt-1">Input realisasi rincian anggaran pendapatan & belanja.</p>
                    </div>
                </a>

                <a href="surat.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Surat Menyurat</h3>
                        <p class="text-xs text-slate-500 mt-1">Penerbitan surat pengantar & administrasi warga.</p>
                    </div>
                </a>

                <a href="wilayah.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-map"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Wilayah Desa</h3>
                        <p class="text-xs text-slate-500 mt-1">Kelola pembagian Dusun, Ketua RW, dan RT.</p>
                    </div>
                </a>

                <a href="berita.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Berita & Artikel</h3>
                        <p class="text-xs text-slate-500 mt-1">Publikasi berita kegiatan serta agenda warga desa.</p>
                    </div>
                </a>

                <a href="agenda.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-amber-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Agenda Kegiatan Desa</h3>
                        <p class="text-xs text-slate-500 mt-1">Atur jadwal kegiatan, waktu, dan lokasi agenda desa.</p>
                    </div>
                </a>

                <a href="profil.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Profil & Identitas Desa</h3>
                        <p class="text-xs text-slate-500 mt-1">Konfigurasi nama desa, logo Boyolali, dan kontak.</p>
                    </div>
                </a>

                <a href="perangkat.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Perangkat Desa</h3>
                        <p class="text-xs text-slate-500 mt-1">Manajemen foto & jabatan jajaran aparatur desa.</p>
                    </div>
                </a>

                <a href="pejabat.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-signature"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Pejabat Surat</h3>
                        <p class="text-xs text-slate-500 mt-1">Daftar penandatangan sah pada dokumen surat.</p>
                    </div>
                </a>

                <a href="database.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:bg-emerald-50/50 transition flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Backup Database</h3>
                        <p class="text-xs text-slate-500 mt-1">Export maupun Import pencadangan seluruh data.</p>
                    </div>
                </a>

            </div>
        </div>

    </main>
</body>
</html>
