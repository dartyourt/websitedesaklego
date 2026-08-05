<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $nilai = (float)$_POST['nilai'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan'] ?: 'Rp');
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun'] ?: '2026');
    $warna = mysqli_real_escape_string($conn, $_POST['warna'] ?: '#165f36');
    $urutan = (int)$_POST['urutan'];

    if (!empty($kategori) && !empty($label)) {
        $query = "INSERT INTO infografis_statistik (kategori, label, nilai, satuan, tahun, warna, urutan) 
                  VALUES ('$kategori', '$label', $nilai, '$satuan', '$tahun', '$warna', $urutan)";
        
        if (mysqli_query($conn, $query)) {
            header("Location: infografis.php?status=sukses_tambah");
            exit;
        } else {
            $error_msg = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    } else {
        $error_msg = "Kategori dan Uraian/Label harus diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Keuangan APBDes - Admin CMS</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-3xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="infografis.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Tambah Data Keuangan & Infografis Baru</h1>
                    <p class="text-[11px] text-amber-300">Input parameter Anggaran, Belanja, atau SILPA Desa</p>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-8">
        <?php if (!empty($error_msg)): ?>
            <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-3 rounded-2xl mb-6 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-xl"></i>
                <span class="text-sm font-semibold"><?= htmlspecialchars($error_msg) ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl flex-shrink-0 border border-emerald-100">
                    <i class="fa-solid fa-chart-column text-emerald-700"></i>
                </div>
                <div>
                    <h2 class="font-bold text-base text-slate-900">Form Data APBDesa & Statistik</h2>
                    <p class="text-xs text-slate-400">Pastikan nominal sesuai dengan Rencana Anggaran Resmi / Buku Pembendaharaan.</p>
                </div>
            </div>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Kategori Keuangan <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#165f36] text-sm font-semibold bg-white">
                        <option value="Pendapatan APBDes 2026">Pendapatan APBDes Tahun 2026</option>
                        <option value="Belanja APBDes 2026">Belanja & Pengeluaran Desa 2026</option>
                        <option value="Pembiayaan APBDes 2026">Pembiayaan Desa 2026</option>
                        <option value="SILPA & Aset 2025">SILPA Akhir Tahun 2025 & Nilai Aset Desa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Uraian / Label Parameter <span class="text-rose-500">*</span></label>
                    <input type="text" name="label" required placeholder="Contoh: Pendapatan Asli Desa atau Pelaksanaan Pembangunan Desa" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#165f36] font-bold text-sm">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Nilai Nominal / Angka <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="nilai" required placeholder="Contoh: 1440238000 (ketik nominal utuh tanpa titik/koma)" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#165f36] text-sm font-mono font-bold text-emerald-700">
                    <p class="text-[11px] text-slate-500 mt-1">Ketik nilai rupiah utuh. Misalnya Rp 357.550.000 ketik <code>357550000</code>.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Satuan</label>
                        <input type="text" name="satuan" value="Rp" placeholder="Rp / Hektar / Warga" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-bold text-sm text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Tahun Anggaran</label>
                        <input type="text" name="tahun" value="2026" placeholder="2026 / 2025" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-bold text-sm text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Urutan Tampil</label>
                        <input type="number" name="urutan" value="1" min="1" max="50" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-bold text-sm text-center">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase mb-2">Warna Grafik</label>
                    <div class="flex items-center gap-4">
                        <input type="color" name="warna" value="#165f36" class="w-16 h-11 rounded-xl border border-slate-300 p-1 cursor-pointer">
                        <span class="text-xs text-slate-500 font-medium">Pilih warna yang harmonis untuk dirender pada dasbor visual infografis.</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="infografis.php" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#165f36] hover:bg-[#0f4426] text-white text-xs font-bold transition flex items-center gap-2 shadow-md">
                        <i class="fa-solid fa-floppy-disk text-amber-300"></i> Simpan Data APBDes
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
