<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

// Ambil semua agenda dari database
$query = mysqli_query($conn, "SELECT * FROM agenda_desa ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Agenda Desa - Admin CMS</title>
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
                    <h1 class="font-bold text-lg leading-tight">Manajemen Agenda & Kegiatan Desa</h1>
                    <p class="text-[11px] text-amber-300">Jadwal Acara & Kegiatan Resmi Pemerintah Desa Klego</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="../index.php" target="_blank" class="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow transition-all">
                    <i class="fa-solid fa-eye"></i> Lihat Beranda Web
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

            <a href="agenda-tambah.php" class="inline-flex items-center gap-2 bg-[#165f36] text-white px-5 py-2.5 rounded-xl hover:bg-[#0f4426] transition font-bold text-xs shadow-md">
                <i class="fa-solid fa-plus text-amber-300"></i> Tambah Agenda Baru
            </a>
        </div>

        <!-- Tabel Agenda -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-base text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-emerald-700"></i> Daftar Agenda & Kegiatan
                </h2>
                <span class="text-xs text-slate-500 font-medium">Ditampilkan Langsung di Halaman Beranda</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4 w-40 text-center">Tanggal</th>
                            <th class="px-6 py-4">Judul Kegiatan</th>
                            <th class="px-6 py-4 w-48">Waktu & Lokasi</th>
                            <th class="px-6 py-4 w-40 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php 
                        if ($query && mysqli_num_rows($query) > 0) :
                            $no = 1; 
                            while($row = mysqli_fetch_assoc($query)) : 
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-500"><?= $no++; ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex flex-col items-center justify-center bg-emerald-50 text-emerald-900 border border-emerald-200/80 px-3 py-1.5 rounded-xl min-w-[70px]">
                                    <span class="text-lg font-extrabold leading-none"><?= date('d', strtotime($row['tanggal'])); ?></span>
                                    <span class="text-[11px] font-bold text-amber-700 uppercase"><?= date('M Y', strtotime($row['tanggal'])); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-900 text-base">
                                <?= htmlspecialchars($row['judul']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-700 font-semibold flex items-center gap-1.5 mb-1">
                                    <i class="fa-regular fa-clock text-amber-500"></i> <?= htmlspecialchars($row['waktu']); ?>
                                </div>
                                <div class="text-xs text-slate-500 flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-emerald-600"></i> <?= htmlspecialchars($row['lokasi']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center space-x-1">
                                <a href="agenda-edit.php?id=<?= $row['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs border border-blue-200 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <a href="agenda-hapus.php?id=<?= $row['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs border border-rose-200 transition" onclick="return confirm('Yakin ingin menghapus agenda ini secara permanen?');">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else : 
                        ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-regular fa-calendar-xmark text-3xl block mb-2"></i>
                                Belum ada data agenda kegiatan desa. Silakan klik "Tambah Agenda Baru".
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
