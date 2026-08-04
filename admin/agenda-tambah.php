<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul   = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $tanggal = mysqli_real_escape_string($conn, trim($_POST['tanggal']));
    $waktu   = mysqli_real_escape_string($conn, trim($_POST['waktu']));
    $lokasi  = mysqli_real_escape_string($conn, trim($_POST['lokasi']));

    if (empty($judul) || empty($tanggal) || empty($waktu) || empty($lokasi)) {
        $error_msg = "Seluruh kolom (Judul, Tanggal, Waktu, Lokasi) wajib diisi!";
    } else {
        $query = "INSERT INTO agenda_desa (judul, tanggal, waktu, lokasi) VALUES ('$judul', '$tanggal', '$waktu', '$lokasi')";
        if (mysqli_query($conn, $query)) {
            header("Location: agenda.php?status=sukses_tambah");
            exit;
        } else {
            $error_msg = "Gagal menyimpan agenda: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Agenda Baru - Admin CMS</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="agenda.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Tambah Agenda Kegiatan Baru</h1>
                    <p class="text-[11px] text-amber-300">Input Jadwal Acara Warga atau Pemerintah Desa Klego</p>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-8">
        <?php if (!empty($error_msg)): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-center gap-3 shadow-sm mb-6">
                <i class="fa-solid fa-circle-exclamation text-xl text-rose-600 flex-shrink-0"></i>
                <div class="text-sm font-semibold"><?= htmlspecialchars($error_msg) ?></div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h2 class="font-extrabold text-lg text-slate-900 mb-6 flex items-center gap-2 border-b pb-4 border-slate-100">
                <i class="fa-solid fa-calendar-plus text-emerald-700"></i> Form Data Agenda Desa
            </h2>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Judul Kegiatan / Agenda <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: Posyandu Balita & Pemeriksaan Lansia Rutin" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-200 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Tanggal Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-200 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Waktu / Jam Pelaksanaan <span class="text-rose-500">*</span></label>
                        <input type="text" name="waktu" required placeholder="Contoh: 08.00 WIB - Selesai" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-200 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Lokasi Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="lokasi" required placeholder="Contoh: Balai Desa Klego / Aula Balai Desa" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-200 transition">
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <a href="agenda.php" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-[#165f36] hover:bg-[#0f4426] text-white text-xs font-extrabold px-6 py-2.5 rounded-xl shadow-md flex items-center gap-2 transition-transform active:scale-95">
                        <i class="fa-solid fa-floppy-disk text-amber-300"></i> Simpan Agenda
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
