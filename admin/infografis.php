<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";
$tableExists = false;
if ($conn && !mysqli_connect_error()) {
    $chk = @mysqli_query($conn, "SHOW TABLES LIKE 'infografis_statistik'");
    if ($chk && mysqli_num_rows($chk) > 0) {
        $tableExists = true;
    }
}

// Proses Tambah Item Grafik
if (isset($_POST['tambah']) && $tableExists) {
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nilai = (float)$_POST['nilai_angka'];
    $warna = mysqli_real_escape_string($conn, $_POST['warna_grafik'] ?: '#165f36');
    $urutan = (int)$_POST['urutan'];
    
    $ins = mysqli_query($conn, "INSERT INTO infografis_statistik (kategori, nama, nilai_angka, warna_grafik, urutan) VALUES ('$kategori', '$nama', $nilai, '$warna', $urutan)");
    if ($ins) {
        $msg = "Data statistik berhasil ditambahkan ke dasbor grafik depan!";
    } else {
        $msg = "Gagal menyimpan: " . mysqli_error($conn);
    }
}

// Proses Hapus Item
if (isset($_GET['hapus']) && $tableExists) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM infografis_statistik WHERE id = $id");
    header("Location: infografis.php?msg=deleted");
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $msg = "Item statistik berhasil dihapus dari grafik.";
}

// Ambil Daftar Data
$statItems = ['keuangan' => [], 'demografi' => []];
if ($tableExists) {
    $res = mysqli_query($conn, "SELECT * FROM infografis_statistik ORDER BY kategori ASC, urutan ASC");
    while ($r = mysqli_fetch_assoc($res)) {
        $statItems[$r['kategori']][] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Infografis Keuangan & Demografi - Admin CMS</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="index.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="font-bold text-lg">Kelola Data Infografis Pembendaharaan & Demografi</h1>
            </div>
            <a href="../infografis.php" target="_blank" class="bg-amber-500 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-eye"></i> Pratinjau Dasbor Infografis
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- FORM TAMBAH ANGKA STATISTIK -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h2 class="font-bold text-lg text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-square-plus text-teal-700"></i> Tambah Data Grafik Baru
                    </h2>

                    <form action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelompok Grafik <span class="text-rose-500">*</span></label>
                            <select name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-600 text-sm font-semibold bg-white">
                                <option value="keuangan">Keuangan & Pembendaharaan (APBDes)</option>
                                <option value="demografi">Demografi & Wilayah (Penduduk per Dusun)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Parameter / Program <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" required placeholder="Contoh: Dana Desa (APBN) atau Dusun Klego" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-600 text-sm font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nilai Angka / Jumlah <span class="text-rose-500">*</span></label>
                            <input type="number" step="any" name="nilai_angka" required placeholder="Contoh: 875 (dalam Juta Rp) atau 1243 (warga)" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-600 text-sm font-mono font-bold">
                            <span class="text-[10px] text-slate-400">Untuk keuangan gunakan nominal Juta Rupiah (misal Rp 875 Juta = ketik 875)</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Batang/Pie</label>
                                <input type="color" name="warna_grafik" value="#165f36" class="w-full h-10 rounded-xl border border-slate-300 p-1 cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                                <input type="number" name="urutan" value="1" min="1" max="50" class="w-full px-4 py-2 rounded-xl border border-slate-300 font-bold text-center">
                            </div>
                        </div>

                        <div class="pt-3">
                            <button type="submit" name="tambah" class="w-full bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold py-3 px-4 rounded-xl shadow transition-all duration-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> Simpan ke Dasbor Infografis
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DAFTAR ANGKA DAN STATISTIK -->
            <div class="lg:col-span-7 space-y-6">
                <!-- KELOMPOK KEUANGAN -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
                        <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-coins text-amber-500"></i> Parameter Pembendaharaan & APBDes 2026
                        </h3>
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-0.5 rounded-full">Keuangan</span>
                    </div>

                    <?php if (empty($statItems['keuangan'])): ?>
                        <p class="text-xs text-slate-400 italic py-4 text-center">Belum ada item khusus di database. Web depan saat ini menampilkan angka default perdes 2026.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($statItems['keuangan'] as $item): ?>
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 rounded-lg shadow-sm" style="background-color: <?= htmlspecialchars($item['warna_grafik'] ?? '#165f36') ?>;"></span>
                                        <div>
                                            <span class="font-bold text-sm text-slate-900 block"><?= htmlspecialchars($item['nama']) ?></span>
                                            <span class="text-xs text-emerald-700 font-mono font-bold">Rp <?= number_format($item['nilai_angka'], 2, ',', '.') ?> Juta</span>
                                        </div>
                                    </div>
                                    <a href="infografis.php?hapus=<?= $item['id'] ?>" onclick="return confirm('Hapus item dari grafik?')" class="text-xs text-rose-600 font-bold hover:underline">Hapus</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- KELOMPOK DEMOGRAFI -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
                        <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-users text-blue-600"></i> Parameter Demografi & Wilayah
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-0.5 rounded-full">Demografi</span>
                    </div>

                    <?php if (empty($statItems['demografi'])): ?>
                        <p class="text-xs text-slate-400 italic py-4 text-center">Belum ada item kustom di database. Web depan saat ini menggunakan estimasi kependudukan resmi balai desa.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($statItems['demografi'] as $item): ?>
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 rounded-lg shadow-sm" style="background-color: <?= htmlspecialchars($item['warna_grafik'] ?? '#3b82f6') ?>;"></span>
                                        <div>
                                            <span class="font-bold text-sm text-slate-900 block"><?= htmlspecialchars($item['nama']) ?></span>
                                            <span class="text-xs text-slate-600 font-mono"><?= number_format($item['nilai_angka'], 0, ',', '.') ?> Warga</span>
                                        </div>
                                    </div>
                                    <a href="infografis.php?hapus=<?= $item['id'] ?>" onclick="return confirm('Hapus item dari grafik?')" class="text-xs text-rose-600 font-bold hover:underline">Hapus</a>
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
