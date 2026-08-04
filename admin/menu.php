<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";

// Cek koneksi & tabel
$tableExists = false;
if ($conn && !mysqli_connect_error()) {
    $chk = @mysqli_query($conn, "SHOW TABLES LIKE 'menu_navbar'");
    if ($chk && mysqli_num_rows($chk) > 0) {
        $tableExists = true;
    }
}

// Proses Tambah Menu
if (isset($_POST['tambah']) && $tableExists) {
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $parent_id = (int)$_POST['parent_id'];
    $urutan = (int)$_POST['urutan'];
    
    $ins = mysqli_query($conn, "INSERT INTO menu_navbar (parent_id, label, url, urutan, status) VALUES ($parent_id, '$label', '$url', $urutan, 1)");
    if ($ins) {
        $msg = "Menu berhasil ditambahkan dan langsung aktif di website depan!";
    } else {
        $msg = "Gagal menambah menu: " . mysqli_error($conn);
    }
}

// Proses Hapus Menu
if (isset($_GET['hapus']) && $tableExists) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM menu_navbar WHERE id = $id OR parent_id = $id");
    header("Location: menu.php?msg=deleted");
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $msg = "Menu beserta sub-menu dropdown di dalamnya berhasil dihapus!";
}

// Ambil daftar menu induk untuk dropdown pilihan parent
$parentMenus = [];
$allMenus = [];
$subMenus = [];

if ($tableExists) {
    $resP = mysqli_query($conn, "SELECT * FROM menu_navbar WHERE parent_id = 0 ORDER BY urutan ASC");
    while ($p = mysqli_fetch_assoc($resP)) {
        $parentMenus[$p['id']] = $p['label'];
        $allMenus[$p['id']] = $p;
    }
    
    $resS = mysqli_query($conn, "SELECT * FROM menu_navbar WHERE parent_id > 0 ORDER BY urutan ASC");
    while ($s = mysqli_fetch_assoc($resS)) {
        $subMenus[$s['parent_id']][] = $s;
    }
}

// Daftar Halaman Statis untuk Referensi Cepat
$staticPages = [
    'Beranda' => 'index.php',
    'Pustaka Hukum & Regulasi' => 'dokumen.php',
    'Infografis Keuangan & Demografi' => 'infografis.php',
    'Berita & Agenda' => 'berita.php',
    'Sejarah & Visi Misi' => 'page.php?slug=sejarah-visi-misi',
    'Struktur Pemerintahan' => 'page.php?slug=struktur-pemerintahan',
    'Potensi & UMKM Desa' => 'page.php?slug=potensi-desa',
    'Panduan Layanan Warga' => 'page.php?slug=panduan-layanan'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu Navigasi - Admin Portal</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <!-- HEADER -->
    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="index.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="font-bold text-lg">Kelola Struktur Menu & Navigasi Website</h1>
            </div>
            <a href="../index.php" target="_blank" class="bg-amber-500 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-eye"></i> Pratinjau Navbar Depan
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <?php if (!empty($msg)): ?>
            <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-2xl mb-6 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="text-sm font-semibold"><?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$tableExists): ?>
            <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl mb-8 shadow-sm">
                <h3 class="font-bold text-amber-800 text-lg flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Tabel Database Belum Terpasang
                </h3>
                <p class="text-xs text-slate-600 mt-1 mb-4">
                    Sistem modular saat ini membutuhkan eksekusi instalasi tabel baru (`menu_navbar`, `halaman_statis`, `dokumen_publik`). Pastikan MySQL sudah aktif, lalu jalankan script setup.
                </p>
                <a href="../setup_modular_db.php" target="_blank" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-5 py-2.5 rounded-xl inline-block shadow">
                    Jalankan Script Migrasi Database Sekarang
                </a>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- FORM TAMBAH MENU (LEFT COLUMN) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h2 class="font-bold text-lg text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-emerald-700"></i> Tambah Menu Baru
                    </h2>
                    
                    <form action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Label Menu <span class="text-rose-500">*</span></label>
                            <input type="text" name="label" required placeholder="Contoh: Profil Desa,atau Produk Hukum" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Induk Menu (Parent / Dropdown)</label>
                            <select name="parent_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                                <option value="0">--- Jadi Menu Utama (Paling Atas) ---</option>
                                <?php foreach ($parentMenus as $pid => $pLabel): ?>
                                    <option value="<?= $pid ?>">Sub-menu di dalam: <?= htmlspecialchars($pLabel) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Jika memilih induk, menu ini akan tampil saat pengunjung mengarahkan mouse ke menu utama.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target URL / Link <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="url" id="inputUrl" required placeholder="index.php atau page.php?slug=..." 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono">
                            </div>
                            <!-- QUICK SELECT LINK FROM EXISTING PAGES -->
                            <div class="mt-2 pt-2 border-t border-slate-100">
                                <span class="text-[11px] text-slate-500 font-bold block mb-1">Atau Pilih Cepat dari Halaman Sistem:</span>
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach ($staticPages as $pageName => $pageUrl): ?>
                                        <button type="button" onclick="document.getElementById('inputUrl').value = '<?= $pageUrl ?>'" 
                                                class="text-[10px] bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 text-slate-700 font-semibold px-2.5 py-1 rounded-lg border border-slate-200 transition-colors">
                                            <?= $pageName ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan Tampilan</label>
                            <input type="number" name="urutan" value="1" min="1" max="99" 
                                   class="w-24 px-4 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-bold text-center">
                            <span class="text-[11px] text-slate-400 ml-2">Angka kecil tampil di awal (kiri)</span>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="tambah" class="w-full bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold py-3 px-4 rounded-xl shadow transition-all duration-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-save"></i> Simpan ke Navbar Web
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DAFTAR STRUKTUR MENU (RIGHT COLUMN) -->
            <div class="lg:col-span-7">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="font-bold text-lg text-slate-900">Struktur Navbar Saat Ini</h2>
                            <p class="text-xs text-slate-500">Perubahan di bawah langsung merefleksikan navigasi website publik.</p>
                        </div>
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200">
                            <?= count($allMenus) ?> Menu Utama
                        </span>
                    </div>

                    <?php if (empty($allMenus)): ?>
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400">
                            <i class="fa-solid fa-list-ul text-3xl mb-2"></i>
                            <p class="text-xs">Belum ada struktur menu. Silakan hubungkan database atau klik tombol tambah di kiri.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($allMenus as $mId => $main): ?>
                                <!-- MAIN MENU ITEM -->
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-7 h-7 rounded-lg bg-emerald-700 text-white font-mono font-bold text-xs flex items-center justify-center shadow">
                                                <?= $main['urutan'] ?>
                                            </span>
                                            <div>
                                                <span class="font-bold text-slate-900 text-sm block">
                                                    <?= htmlspecialchars($main['label']) ?>
                                                </span>
                                                <span class="text-[11px] font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                                    <?= htmlspecialchars($main['url']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="menu.php?hapus=<?= $main['id'] ?>" onclick="return confirm('Hapus menu ini beserta dropdown di dalamnya?')" 
                                               class="text-xs text-rose-600 hover:text-rose-800 font-bold px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                                <i class="fa-solid fa-trash mr-1"></i> Hapus
                                            </a>
                                        </div>
                                    </div>

                                    <!-- SUB-MENU ITEMS -->
                                    <?php if (isset($subMenus[$mId])): ?>
                                        <div class="ml-8 mt-3 pl-4 border-l-2 border-amber-400 space-y-2">
                                            <?php foreach ($subMenus[$mId] as $sub): ?>
                                                <div class="p-3 rounded-xl bg-white border border-slate-200 flex items-center justify-between shadow-xs">
                                                    <div class="flex items-center gap-2.5">
                                                        <i class="fa-solid fa-turn-up text-amber-500 rotate-90"></i>
                                                        <span class="w-5 h-5 rounded bg-slate-200 text-slate-700 font-mono font-bold text-[10px] flex items-center justify-center">
                                                            <?= $sub['urutan'] ?>
                                                        </span>
                                                        <span class="text-xs font-semibold text-slate-800">
                                                            <?= htmlspecialchars($sub['label']) ?>
                                                        </span>
                                                        <span class="text-[10px] font-mono text-slate-500">
                                                            (<?= htmlspecialchars($sub['url']) ?>)
                                                        </span>
                                                    </div>
                                                    <a href="menu.php?hapus=<?= $sub['id'] ?>" onclick="return confirm('Hapus sub-menu ini?')" class="text-[11px] text-rose-500 hover:underline font-semibold">
                                                        Hapus
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>
</body>
</html>
