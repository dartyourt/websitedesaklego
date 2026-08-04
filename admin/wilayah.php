<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

$qDusun = mysqli_query($koneksi, "SELECT * FROM wilayah_dusun ORDER BY nama ASC");
$dusuns = [];
while ($d = mysqli_fetch_assoc($qDusun)) {
    $d['rws'] = [];
    $dusuns[$d['id']] = $d;
}

$qRW = mysqli_query($koneksi, "SELECT * FROM wilayah_rw ORDER BY rw ASC");
$rwMap = [];
while ($rw = mysqli_fetch_assoc($qRW)) {
    $rw['rts'] = [];
    $rwMap[$rw['id']] = $rw;
}

$qRT = mysqli_query($koneksi, "SELECT * FROM wilayah_rt ORDER BY rt ASC");
while ($rt = mysqli_fetch_assoc($qRT)) {
    if (isset($rwMap[$rt['rw_id']])) {
        $rwMap[$rt['rw_id']]['rts'][] = $rt;
    }
}

foreach ($rwMap as $rw) {
    if (isset($dusuns[$rw['dusun_id']])) {
        $dusuns[$rw['dusun_id']]['rws'][] = $rw;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Wilayah - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<div class="bg-blue-700 text-white p-4 flex justify-between items-center">
    <h1 class="font-bold text-lg">Manajemen Wilayah Desa</h1>
    <a href="index.php" class="text-sm hover:underline bg-blue-800 px-3 py-1 rounded">Kembali ke Dashboard</a>
</div>

<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Dusun</h2>
        <form action="wilayah-proses.php?action=add_dusun" method="POST" class="flex gap-2">
            <input type="text" name="nama_dusun" placeholder="Nama Dusun Baru" class="border p-2 rounded w-64" required style="text-transform:uppercase">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-bold">+ Tambah Dusun</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($dusuns as $dusun): ?>
            <div class="bg-white rounded shadow border border-gray-200">
                <!-- Dusun Header -->
                <div class="bg-blue-100 p-4 rounded-t border-b border-blue-200 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-blue-900">🏠 Dusun <?= htmlspecialchars($dusun['nama']) ?></h3>
                    <a href="wilayah-proses.php?action=del_dusun&id=<?= $dusun['id'] ?>" onclick="return confirm('Hapus Dusun ini beserta semua RW dan RT di dalamnya?')" class="text-red-500 hover:text-red-700 text-sm font-bold" title="Hapus Dusun">✕</a>
                </div>

                <div class="p-4">
                    <!-- List RW -->
                    <?php if (empty($dusun['rws'])): ?>
                        <p class="text-sm text-gray-500 italic mb-4">Belum ada RW</p>
                    <?php else: ?>
                        <div class="space-y-4 mb-4">
                            <?php foreach ($dusun['rws'] as $rw): ?>
                                <div class="border rounded p-3 bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-bold text-gray-700">RW <?= htmlspecialchars($rw['rw']) ?></h4>
                                        <a href="wilayah-proses.php?action=del_rw&id=<?= $rw['id'] ?>" onclick="return confirm('Hapus RW ini beserta RT di dalamnya?')" class="text-red-500 hover:text-red-700 text-xs font-bold" title="Hapus RW">Hapus RW</a>
                                    </div>
                                    
                                    <!-- List RT -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <?php if (empty($rw['rts'])): ?>
                                            <span class="text-xs text-gray-400 italic">Belum ada RT</span>
                                        <?php else: ?>
                                            <?php foreach ($rw['rts'] as $rt): ?>
                                                <div class="bg-white border text-xs px-2 py-1 rounded flex items-center gap-1 shadow-sm">
                                                    <span>RT <?= htmlspecialchars($rt['rt']) ?></span>
                                                    <a href="wilayah-proses.php?action=del_rt&id=<?= $rt['id'] ?>" onclick="return confirm('Hapus RT ini?')" class="text-red-400 hover:text-red-600 ml-1 font-bold">×</a>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Form Tambah RT -->
                                    <form action="wilayah-proses.php?action=add_rt" method="POST" class="flex gap-1">
                                        <input type="hidden" name="rw_id" value="<?= $rw['id'] ?>">
                                        <input type="text" name="rt" placeholder="No RT (ex: 001)" class="border p-1 rounded text-xs flex-1" required maxlength="5">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-bold">+ RT</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form Tambah RW -->
                    <form action="wilayah-proses.php?action=add_rw" method="POST" class="flex gap-2 border-t pt-3 mt-2">
                        <input type="hidden" name="dusun_id" value="<?= $dusun['id'] ?>">
                        <input type="text" name="rw" placeholder="No RW (ex: 001)" class="border p-2 rounded text-sm flex-1" required maxlength="5">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-bold">+ Tambah RW</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
