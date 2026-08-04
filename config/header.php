<?php
if (!isset($conn)) {
    include __DIR__ . '/database.php';
}
include_once __DIR__ . '/lang_helper.php';

// Ambil data menu navigasi dari tabel menu_navbar (jika koneksi sukses)
$menuItems = [];
$dropdownItems = [];
if ($conn && !mysqli_connect_error()) {
    $qMenu = @mysqli_query($conn, "SELECT * FROM menu_navbar WHERE status = 1 ORDER BY urutan ASC");
    if ($qMenu) {
        while ($m = mysqli_fetch_assoc($qMenu)) {
            if ($m['parent_id'] == 0) {
                $menuItems[$m['id']] = $m;
            } else {
                $dropdownItems[$m['parent_id']][] = $m;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . " - " : "" ?><?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?> | Portal Resmi Pemerintahan</title>
    <meta name="description" content="Website Resmi Pemerintahan <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>, Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan'] ?? 'Klego') ?>, Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten'] ?? 'Boyolali') ?> - Layanan Publik, Produk Hukum & Transparansi Keuangan.">
    
    <!-- FAVICON LOGO BOYOLALI -->
    <link rel="icon" href="logoboyolali.ico" type="image/x-icon">
    <link rel="shortcut icon" href="logoboyolali.ico" type="image/x-icon">

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&family=Lora:ital,wght@0,600;1,400&display=swap" rel="stylesheet">

    <!-- FONT AWESOME 6 ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CHART.JS FOR INFOGRAFIK -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- TAILWIND CSS & CONFIG OVERRIDE (EMERALD GREEN & GOLD THEME) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: {
                DEFAULT: '#165f36',
                light: '#2e9e5b',
                dark: '#0e3f23'
              },
              secondary: {
                DEFAULT: '#c4891f',
                light: '#fbbf24',
                dark: '#926312'
              },
              surface: '#f8fafc',
              border: '#e2e8f0'
            },
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
              heading: ['Outfit', 'sans-serif'],
              serif: ['Lora', 'serif'],
            }
          }
        }
      }
    </script>

    <!-- CUSTOM CSS DENGAN CACHE BUSTER -->
    <link rel="stylesheet" href="assets/css/custom.css?v=<?= time() ?>">
    
    <!-- INLINE STYLE MUTLAK UNTUK EDITOR KONTEN (MENGATASI CACHE BROWSER & TAILWIND PREFLIGHT RESET) -->
    <style>
        .content-body h1, .content-body h2, .content-body h3, .content-body h4, .content-body h5, .content-body h6 {
            font-family: 'Outfit', 'Inter', sans-serif !important;
            display: block !important;
            margin-top: 1.6em !important;
            margin-bottom: 0.6em !important;
            font-weight: 700 !important;
            line-height: 1.3 !important;
        }
        .content-body h1 { font-size: 2.25rem !important; color: #0f172a !important; font-weight: 800 !important; }
        .content-body h2 { font-size: 1.75rem !important; color: #0f172a !important; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.35em !important; }
        .content-body h3 { font-size: 1.45rem !important; color: #165f36 !important; font-weight: 800 !important; margin-top: 1.8em !important; }
        .content-body h4 { font-size: 1.2rem !important; color: #1e293b !important; font-weight: 700 !important; }
        .content-body p { margin-top: 0.75em !important; margin-bottom: 1.25em !important; line-height: 1.85 !important; font-size: 1.05rem !important; color: #334155 !important; }
        .content-body ul { list-style-type: disc !important; padding-left: 2.5rem !important; margin-top: 0.75em !important; margin-bottom: 1.5em !important; display: block !important; }
        .content-body ol { list-style-type: decimal !important; padding-left: 2.5rem !important; margin-top: 0.75em !important; margin-bottom: 1.5em !important; display: block !important; }
        .content-body li { display: list-item !important; margin-bottom: 0.6em !important; line-height: 1.75 !important; padding-left: 0.35em !important; color: #334155 !important; font-size: 1.05rem !important; }
        .content-body blockquote { border-left: 5px solid #c4891f !important; background-color: #fffbeb !important; padding: 18px 24px !important; margin: 2rem 0 !important; font-style: italic !important; border-radius: 0 16px 16px 0 !important; color: #1e293b !important; font-size: 1.1rem !important; box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important; }
        .content-body figure { margin: 2.5rem auto !important; text-align: center !important; display: flex !important; flex-direction: column !important; align-items: center !important; clear: both !important; }
        .content-body figure img { max-width: 100% !important; height: auto !important; border-radius: 16px !important; box-shadow: 0 12px 28px -6px rgba(0, 0, 0, 0.18) !important; display: block !important; margin: 0 auto !important; border: 2px solid #cbd5e1 !important; }
        .content-body figcaption { font-size: 0.9rem !important; font-weight: 600 !important; color: #475569 !important; font-style: italic !important; margin-top: 14px !important; background-color: #f8fafc !important; padding: 6px 22px !important; border-radius: 9999px !important; display: inline-block !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important; }
        .content-body strong, .content-body b { font-weight: 700 !important; color: #0f172a !important; }
        .content-body em, .content-body i { font-style: italic !important; }
        .content-body a { color: #165f36 !important; text-decoration: underline !important; font-weight: 600 !important; }
        .content-body a:hover { color: #c4891f !important; }
        .content-body table { width: 100% !important; border-collapse: collapse !important; margin: 1.75rem 0 !important; border: 1px solid #cbd5e1 !important; }
        .content-body th, .content-body td { border: 1px solid #cbd5e1 !important; padding: 12px 16px !important; text-align: left !important; }
        .content-body th { background-color: #f1f5f9 !important; font-weight: 700 !important; color: #0f172a !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-emerald-600 selection:text-white min-h-screen flex flex-col">

    <!-- TOP BAR OPTIMIZATION -->
    <div class="bg-[#165f36] text-emerald-100 text-xs py-2 border-b border-emerald-800 shadow-inner hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <span class="flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-clock text-amber-400"></i> <?= tr('Senin - Jumat: 08.00 - 16.00 WIB') ?>
                </span>
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-phone text-amber-400"></i> <?= htmlspecialchars($APP_PROFIL['telepon'] ?? '(0276) 321-456') ?>
                </span>
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-envelope text-amber-400"></i> <?= htmlspecialchars($APP_PROFIL['email'] ?? 'desaklego@boyolali.go.id') ?>
                </span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="dokumen.php" class="hover:text-white transition-colors font-medium" title="JDIH & Regulasi">
                    <i class="fa-solid fa-scale-balanced mr-1 text-amber-300"></i> <?= tr('JDIH Desa') ?>
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR (MODULAR ALA WORDPRESS) -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-slate-200 transition-all duration-300" id="main-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            
            <!-- LOGO BOYOLALI & BRANDING -->
            <a href="index.php" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-full bg-slate-100 border-2 border-emerald-600 flex items-center justify-center p-1 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300 overflow-hidden">
                    <img src="logoboyolali.png" alt="Logo Boyolali" class="w-full h-full object-contain">
                </div>
                <div class="leading-tight">
                    <h1 class="font-heading font-bold text-lg text-slate-900 tracking-tight group-hover:text-emerald-800 transition-colors">
                        <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
                    </h1>
                    <p class="text-xs text-amber-700 font-medium">
                        Kec. <?= htmlspecialchars($APP_PROFIL['kecamatan'] ?? 'Klego') ?>, Kab. <?= htmlspecialchars($APP_PROFIL['kabupaten'] ?? 'Boyolali') ?>
                    </p>
                </div>
            </a>

            <!-- DESKTOP NAVIGATION -->
            <nav class="hidden lg:flex items-center space-x-1.5">
                <?php if (!empty($menuItems)): ?>
                    <?php foreach ($menuItems as $mId => $menu): ?>
                        <?php 
                        $menu = translateMenuItem($menu);
                        $hasSub = isset($dropdownItems[$mId]) && !empty($dropdownItems[$mId]); 
                        ?>
                        <?php if (!$hasSub): ?>
                            <a href="<?= htmlspecialchars($menu['url']) ?>" 
                               class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/80 transition-all duration-200">
                                <?= htmlspecialchars($menu['label']) ?>
                            </a>
                        <?php else: ?>
                            <!-- DROPDOWN MENU -->
                            <div class="relative group dropdown-container">
                                <button type="button" 
                                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 group-hover:text-emerald-700 group-hover:bg-emerald-50/80 transition-all duration-200 focus:outline-none">
                                    <?= htmlspecialchars($menu['label']) ?>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-emerald-600 transition-transform duration-200 group-hover:-rotate-180"></i>
                                </button>
                                
                                <!-- DROPDOWN CONTENT -->
                                <div class="absolute left-0 top-full mt-1 w-64 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform origin-top-left scale-95 group-hover:scale-100 overflow-hidden py-1.5">
                                    <div class="px-3 py-1 text-[10px] font-bold tracking-wider uppercase text-amber-600 bg-amber-50/50 border-b border-slate-100 mb-1">
                                        <?= htmlspecialchars($menu['label']) ?>
                                    </div>
                                    <?php foreach ($dropdownItems[$mId] as $sub): ?>
                                        <?php $sub = translateMenuItem($sub); ?>
                                        <a href="<?= htmlspecialchars($sub['url']) ?>" 
                                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 transition-colors">
                                            <i class="fa-solid fa-circle-chevron-right text-[11px] text-amber-500"></i>
                                            <span><?= htmlspecialchars($sub['label']) ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- DEFAULT MENU JIKA DATABASE BELUM CONNECT/DISETUP -->
                    <a href="index.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-emerald-800 bg-emerald-50"><?= t('beranda', 'Beranda') ?></a>
                    <a href="infografis.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-emerald-700"><?= t('infografis_keuangan', 'Infografis Keuangan') ?></a>
                    <a href="dokumen.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-emerald-700"><?= t('regulasi_aset', 'Regulasi & Aset Desa') ?></a>
                    <a href="berita.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-emerald-700"><?= t('pelayanan_berita', 'Berita & Agenda') ?></a>
                <?php endif; ?>

                <div class="h-6 w-[1px] bg-slate-200 mx-1.5"></div>

                <!-- LANGUAGE SWITCHER RESPONSIF & HARMONIS -->
                <?php if (!empty($activeLanguages)): ?>
                    <div class="relative group dropdown-container z-[100]">
                        <button type="button" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/80 transition-all duration-200 focus:outline-none cursor-pointer">
                            <span class="text-base leading-none filter drop-shadow-xs transform group-hover:scale-110 transition-transform duration-200"><?= htmlspecialchars($activeLanguages[$currentLang]['bendera'] ?? '🇮🇩') ?></span>
                            <span class="font-bold tracking-wide"><?= strtoupper($currentLang) ?></span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-emerald-600 transition-transform duration-200 group-hover:-rotate-180"></i>
                        </button>
                        <div class="absolute right-0 top-full mt-1.5 w-52 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[110] transform origin-top-right scale-95 group-hover:scale-100 overflow-hidden p-1.5">
                            <div class="px-3 py-1.5 text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-100 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-globe text-emerald-600"></i> Pilih Bahasa / Language
                            </div>
                            <?php foreach ($activeLanguages as $codeL => $infoL): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['lang' => $codeL])) ?>" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold <?= $codeL == $currentLang ? 'bg-emerald-50/90 text-emerald-800 font-extrabold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' ?> transition-all duration-150 my-0.5">
                                    <span class="flex items-center gap-2.5">
                                        <span class="text-base leading-none"><?= htmlspecialchars($infoL['bendera']) ?></span>
                                        <span><?= htmlspecialchars($infoL['nama']) ?></span>
                                    </span>
                                    <?php if ($codeL == $currentLang): ?>
                                        <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]"></i>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- TOMBOL LAYANAN CEPAT -->
                <a href="dokumen.php" class="ml-1 bg-gradient-to-r from-[#165f36] to-[#2e9e5b] hover:from-[#0e3f23] hover:to-[#165f36] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-arrow-down text-amber-300"></i>
                    <span><?= t('pusat_unduhan', 'Pusat Unduhan') ?></span>
                </a>
            </nav>

            <!-- MOBILE MENU BUTTON -->
            <div class="lg:hidden flex items-center gap-2">
                <!-- MOBILE LANGUAGE BUTTON -->
                <?php if (!empty($activeLanguages)): ?>
                    <a href="?lang=<?= $currentLang === 'id' ? 'en' : ($currentLang === 'en' ? 'ja' : 'id') ?>" class="px-3 py-1.5 rounded-lg bg-emerald-50/80 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 font-bold text-xs flex items-center gap-1.5 shadow-2xs transition-all">
                        <span class="text-sm"><?= htmlspecialchars($activeLanguages[$currentLang]['bendera'] ?? '🇮🇩') ?></span>
                        <span><?= strtoupper($currentLang) ?></span>
                        <i class="fa-solid fa-arrows-rotate text-[10px] text-emerald-600 ml-0.5"></i>
                    </a>
                <?php endif; ?>
                
                <a href="dokumen.php" class="text-xs bg-emerald-700 text-white px-3 py-1.5 rounded-lg font-semibold">
                    <i class="fa-solid fa-download mr-1"></i> Data
                </a>
                <button id="mobile-menu-toggle" class="p-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl" id="mobile-icon-bars"></i>
                    <i class="fa-solid fa-xmark text-xl hidden" id="mobile-icon-close"></i>
                </button>
            </div>
        </div>

        <!-- MOBILE MENU CONTENT -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-200 px-4 pt-3 pb-6 space-y-2 shadow-xl">
            <!-- PILIHAN BAHASA MOBILE LENGKAP -->
            <?php if (!empty($activeLanguages)): ?>
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 mb-3">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Pilih Bahasa / Language Switcher:</span>
                    <div class="grid grid-cols-3 gap-1.5">
                        <?php foreach ($activeLanguages as $codeL => $infoL): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['lang' => $codeL])) ?>" class="flex items-center justify-center gap-1 py-1.5 rounded-lg text-xs font-bold transition-all <?= $codeL == $currentLang ? 'bg-[#165f36] text-white shadow' : 'bg-white text-slate-700 border border-slate-200' ?>">
                                <span><?= htmlspecialchars($infoL['bendera']) ?></span>
                                <span><?= strtoupper($codeL) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($menuItems)): ?>
                <?php foreach ($menuItems as $mId => $menu): ?>
                    <?php 
                    $menu = translateMenuItem($menu);
                    $hasSub = isset($dropdownItems[$mId]) && !empty($dropdownItems[$mId]); 
                    ?>
                    <?php if (!$hasSub): ?>
                        <a href="<?= htmlspecialchars($menu['url']) ?>" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-emerald-50">
                            <?= htmlspecialchars($menu['label']) ?>
                        </a>
                    <?php else: ?>
                        <div class="py-1 border-y border-slate-100 my-1">
                            <span class="block px-3 py-1 text-xs font-bold text-amber-700 uppercase">
                                <?= htmlspecialchars($menu['label']) ?>
                            </span>
                            <div class="pl-4 space-y-1 mt-1">
                                <?php foreach ($dropdownItems[$mId] as $sub): ?>
                                    <?php $sub = translateMenuItem($sub); ?>
                                    <a href="<?= htmlspecialchars($sub['url']) ?>" class="block px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-emerald-700">
                                        • <?= htmlspecialchars($sub['label']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="index.php" class="block px-3 py-2 font-semibold"><?= t('beranda', 'Beranda') ?></a>
                <a href="infografis.php" class="block px-3 py-2 font-semibold"><?= t('infografis_keuangan', 'Infografis Keuangan') ?></a>
                <a href="dokumen.php" class="block px-3 py-2 font-semibold"><?= t('regulasi_aset', 'Regulasi & Aset Desa') ?></a>
            <?php endif; ?>
            <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                <span class="text-xs text-slate-500">Pemerintah <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?></span>
            </div>
        </div>
    </header>

    <!-- JS untuk Toggle Mobile Menu -->
    <script>
      document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        const bars = document.getElementById('mobile-icon-bars');
        const close = document.getElementById('mobile-icon-close');
        menu.classList.toggle('hidden');
        bars.classList.toggle('hidden');
        close.classList.toggle('hidden');
      });
    </script>

    <!-- MAIN CONTENT CONTAINER START -->
    <main class="flex-grow">
