<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';
require_once __DIR__ . '/../config/upload_helper.php';

// Ambil semua berita
$query = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin CMS</title>
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
                    <h1 class="font-bold text-lg leading-tight">Manajemen Berita & Agenda Desa</h1>
                    <p class="text-[11px] text-amber-300">Pusat Kelola Publikasi Resmi Pemerintah Desa Klego</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="../berita.php" target="_blank" class="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow transition-all">
                    <i class="fa-solid fa-eye"></i> Lihat Berita Web
                </a>
                <a href="logout.php" class="text-xs bg-rose-600 hover:bg-rose-700 font-bold px-3.5 py-2 rounded-xl transition-colors">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-8 space-y-6">
        <!-- Tombol Aksi -->
        <div class="flex justify-between items-center">
            <a href="index.php" class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-300 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-emerald-700"></i> Kembali ke Dashboard
            </a>

            <a href="berita-tambah.php" class="inline-flex items-center gap-2 bg-[#165f36] text-white px-5 py-2.5 rounded-xl hover:bg-[#0f4426] transition font-bold text-xs shadow-md">
                <i class="fa-solid fa-plus text-amber-300"></i> Tambah Berita Baru
            </a>
        </div>

        <!-- Tabel Berita -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-base text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-list-ul text-emerald-700"></i> Daftar Publikasi Berita
                </h2>
                <span class="text-xs text-slate-500 font-medium">Diperbarui Secara Real-Time</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4 w-28 text-center">Foto Cover</th>
                            <th class="px-6 py-4">Judul Artikel</th>
                            <th class="px-6 py-4 w-36 text-center">Tanggal Rilis</th>
                            <th class="px-6 py-4 w-40 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $no = 1; while($row = mysqli_fetch_assoc($query)) : ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-500"><?= $no++; ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if($row['foto']): ?>
                                    <?php $fotoPath = '../' . ltrim(resolve_uploaded_image($row['foto']), '/'); ?>
                                    <div class="w-20 h-14 rounded-xl overflow-hidden shadow-sm border border-slate-200 bg-slate-100 mx-auto">
                                        <img src="<?= htmlspecialchars($fotoPath); ?>" onerror="this.onerror=null; this.src='../assets/img/utama.jpg';" alt="Foto" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="w-20 h-14 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xs mx-auto border border-slate-200">
                                        <i class="fa-regular fa-image text-lg"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-slate-900 text-base leading-snug block hover:text-emerald-700 transition-colors">
                                    <a href="../detail-berita.php?id=<?= $row['id'] ?>" target="_blank"><?= htmlspecialchars($row['judul']) ?></a>
                                </span>
                                <span class="text-xs text-slate-400 mt-1 line-clamp-1">
                                    <?= htmlspecialchars(substr(strip_tags($row['isi'] ?? ''), 0, 90)) ?>...
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 px-3 py-1 rounded-full font-bold text-xs">
                                    <?= date('d M Y', strtotime($row['tanggal'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-1">
                                <a href="berita-edit.php?id=<?= $row['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs border border-blue-200 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <a href="berita-hapus.php?id=<?= $row['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs border border-rose-200 transition" onclick="return confirm('Yakin ingin menghapus berita ini secara permanen?');">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
