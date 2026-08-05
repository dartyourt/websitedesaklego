<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

$msg = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sukses_tambah') $msg = "Data keuangan / infografis baru berhasil ditambahkan.";
    if ($_GET['status'] == 'sukses_edit') $msg = "Data keuangan / infografis berhasil diperbarui.";
    if ($_GET['status'] == 'sukses_hapus') $msg = "Data keuangan / infografis telah dihapus secara permanen.";
}

// Ambil semua data dari infografis_statistik dikelompokkan per kategori
$query = mysqli_query($conn, "SELECT * FROM infografis_statistik ORDER BY kategori ASC, urutan ASC, id ASC");

$kategori_list = [
    'Pendapatan APBDes 2026' => 'Pendapatan APBDes Tahun 2026',
    'Belanja APBDes 2026' => 'Belanja & Pengeluaran Desa 2026',
    'Pembiayaan APBDes 2026' => 'Pembiayaan Desa 2026',
    'SILPA & Aset 2025' => 'SILPA Akhir Tahun 2025 & Nilai Aset Desa'
];

$data_per_kat = [];
while ($row = mysqli_fetch_assoc($query)) {
    $kat = $row['kategori'];
    $data_per_kat[$kat][] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Keuangan & APBDesa - Admin CMS</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <!-- Header -->
    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="index.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Manajemen Keuangan & APBDesa (Infografis)</h1>
                    <p class="text-[11px] text-amber-300">Data Pendapatan, Belanja, & Pembiayaan Resmi Desa Klego</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="../infografis.php" target="_blank" class="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow transition-all">
                    <i class="fa-solid fa-eye"></i> Pratinjau Web Infografis
                </a>
                <a href="logout.php" class="text-xs bg-rose-600 hover:bg-rose-700 font-bold px-3.5 py-2 rounded-xl transition-colors">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-8 space-y-6">
        <?php if (!empty($msg)): ?>
            <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="text-sm font-semibold"><?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>

        <!-- Tombol Aksi -->
        <div class="flex justify-between items-center">
            <a href="index.php" class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-300 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-emerald-700"></i> Kembali ke Dashboard
            </a>

            <a href="infografis-tambah.php" class="inline-flex items-center gap-2 bg-[#165f36] text-white px-5 py-2.5 rounded-xl hover:bg-[#0f4426] transition font-bold text-xs shadow-md">
                <i class="fa-solid fa-plus text-amber-300"></i> Tambah Data APBDes / Infografis
            </a>
        </div>

        <!-- Tabel Per Kategori -->
        <?php foreach ($kategori_list as $kat_key => $kat_judul): ?>
            <?php $items = $data_per_kat[$kat_key] ?? []; ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center text-sm shadow">
                            <i class="fa-solid fa-chart-pie"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900"><?= htmlspecialchars($kat_judul) ?></h3>
                            <p class="text-[11px] text-slate-500">Kategori Database: <code><?= htmlspecialchars($kat_key) ?></code></p>
                        </div>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 font-extrabold text-xs px-3 py-1 rounded-full border border-emerald-200">
                        <?= count($items) ?> Item Data
                    </span>
                </div>

                <?php if (empty($items)): ?>
                    <div class="p-6 text-center text-slate-400 text-xs italic">
                        Belum ada item data untuk kategori ini. Klik "Tambah Data APBDes / Infografis" untuk menambahkan.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600 font-extrabold uppercase tracking-wider border-b border-slate-200">
                                    <th class="py-3.5 px-6 w-14 text-center">Urutan</th>
                                    <th class="py-3.5 px-6">Uraian / Label Parameter</th>
                                    <th class="py-3.5 px-6 text-right">Nilai Nominal / Angka</th>
                                    <th class="py-3.5 px-6 w-24 text-center">Satuan</th>
                                    <th class="py-3.5 px-6 w-24 text-center">Tahun</th>
                                    <th class="py-3.5 px-6 w-32 text-center">Warna Grafik</th>
                                    <th class="py-3.5 px-6 w-44 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 font-medium">
                                <?php foreach ($items as $item): ?>
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="py-4 px-6 text-center font-bold text-slate-500">
                                            <?= (int)$item['urutan'] ?>
                                        </td>
                                        <td class="py-4 px-6 font-extrabold text-slate-900 text-sm">
                                            <?= htmlspecialchars($item['label']) ?>
                                        </td>
                                        <td class="py-4 px-6 text-right font-mono font-bold text-emerald-700 text-sm">
                                            <?= number_format($item['nilai'], 0, ',', '.') ?>
                                        </td>
                                        <td class="py-4 px-6 text-center font-bold text-slate-600">
                                            <?= htmlspecialchars($item['satuan'] ?: 'Rp') ?>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full font-bold">
                                                <?= htmlspecialchars($item['tahun'] ?: '2026') ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <div class="flex items-center justify-center gap-1.5 font-mono text-[11px] text-slate-600">
                                                <span class="w-5 h-5 rounded shadow-sm border border-slate-300 inline-block" style="background-color: <?= htmlspecialchars($item['warna'] ?: '#165f36') ?>;"></span>
                                                <?= htmlspecialchars($item['warna'] ?: '#165f36') ?>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center space-x-1">
                                            <a href="infografis-edit.php?id=<?= $item['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs border border-blue-200 transition">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                            <a href="infografis-hapus.php?id=<?= $item['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs border border-rose-200 transition" onclick="return confirm('Yakin ingin menghapus item data ini secara permanen?');">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- Kategori Lainnya (apabila ada data dengan kategori selain default di atas) -->
        <?php 
        $other_keys = array_diff(array_keys($data_per_kat), array_keys($kategori_list));
        if (!empty($other_keys)): 
            foreach ($other_keys as $oth_kat): $items = $data_per_kat[$oth_kat];
        ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900"><?= htmlspecialchars($oth_kat) ?></h3>
                        <p class="text-[11px] text-slate-500">Kategori Kustom</p>
                    </div>
                    <span class="bg-amber-100 text-amber-800 font-extrabold text-xs px-3 py-1 rounded-full border border-amber-200">
                        <?= count($items) ?> Item Data
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 font-extrabold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-3.5 px-6 text-center w-14">Urutan</th>
                                <th class="py-3.5 px-6">Uraian / Label Parameter</th>
                                <th class="py-3.5 px-6 text-right">Nilai Nominal / Angka</th>
                                <th class="py-3.5 px-6 text-center">Satuan</th>
                                <th class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-medium">
                            <?php foreach ($items as $item): ?>
                                <tr class="hover:bg-slate-50/70">
                                    <td class="py-4 px-6 text-center font-bold"><?= (int)$item['urutan'] ?></td>
                                    <td class="py-4 px-6 font-extrabold text-slate-900"><?= htmlspecialchars($item['label']) ?></td>
                                    <td class="py-4 px-6 text-right font-mono font-bold text-emerald-700"><?= number_format($item['nilai'], 0, ',', '.') ?></td>
                                    <td class="py-4 px-6 text-center"><?= htmlspecialchars($item['satuan'] ?: 'Rp') ?></td>
                                    <td class="py-4 px-6 text-center space-x-1">
                                        <a href="infografis-edit.php?id=<?= $item['id'] ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded bg-blue-50 text-blue-700 border border-blue-200">Edit</a>
                                        <a href="infografis-hapus.php?id=<?= $item['id'] ?>" onclick="return confirm('Hapus item?');" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded bg-rose-50 text-rose-700 border border-rose-200">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </main>

</body>
</html>
