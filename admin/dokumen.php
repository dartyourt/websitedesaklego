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
    $chk = @mysqli_query($conn, "SHOW TABLES LIKE 'dokumen_publik'");
    if ($chk && mysqli_num_rows($chk) > 0) {
        $tableExists = true;
    }
}

// Proses Hapus Dokumen
if (isset($_GET['hapus']) && $tableExists) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM dokumen_publik WHERE id = $id");
    header("Location: dokumen.php?msg=deleted");
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $msg = "Dokumen berhasil dihapus dari perpustakaan publik.";
}

// Proses Tambah / Upload Dokumen Baru
if (isset($_POST['upload']) && $tableExists) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tanggal = $_POST['tanggal'] ?: date('Y-m-d');
    
    $file_path = "";
    $file_type = "pdf";
    $file_size = 0;
    
    // Periksa apakah ada upload file
    if (!empty($_FILES['file_doc']['name'])) {
        $fileName = $_FILES['file_doc']['name'];
        $tmpName = $_FILES['file_doc']['tmp_name'];
        $fileSize = $_FILES['file_doc']['size'];
        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'docx', 'doc', 'xlsx', 'xls', 'ppt', 'pptx'];
        
        if (in_array($ext, $allowed)) {
            $newName = 'doc_' . time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $fileName);
            if (!is_dir('../uploads')) {
                @mkdir('../uploads', 0777, true);
            }
            if (move_uploaded_file($tmpName, '../uploads/' . $newName)) {
                $file_path = 'uploads/' . $newName;
                $file_type = $ext;
                $file_size = $fileSize;
            }
        }
    } elseif (!empty($_POST['file_path_existing'])) {
        $file_path = mysqli_real_escape_string($conn, $_POST['file_path_existing']);
        $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $file_size = 150000; // default perkiraan
    }
    
    if (!empty($file_path)) {
        $ins = mysqli_query($conn, "INSERT INTO dokumen_publik (kategori, judul, file_path, file_type, file_size, keterangan, tanggal) VALUES ('$kategori', '$judul', '$file_path', '$file_type', $file_size, '$keterangan', '$tanggal')");
        if ($ins) {
            $msg = "Berhasil! Dokumen regulasi/aset berhasil dipublikasikan di portal warga.";
        } else {
            $msg = "Gagal menyimpan ke database: " . mysqli_error($conn);
        }
    } else {
        $msg = "Gagal: Harap upload berkas (PDF, DOCX, XLSX) atau cantumkan lokasi file yang valid.";
    }
}

// Ambil Semua Dokumen
$docs = [];
if ($tableExists) {
    $res = mysqli_query($conn, "SELECT * FROM dokumen_publik ORDER BY tanggal DESC, id DESC");
    while ($r = mysqli_fetch_assoc($res)) {
        $r['file_size_formatted'] = $r['file_size'] > 0 ? round($r['file_size']/1024, 1) . ' KB' : '-';
        if ($r['file_size'] > 1048576) {
            $r['file_size_formatted'] = round($r['file_size']/1048576, 1) . ' MB';
        }
        $docs[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokumen JDIH & Aset - Admin CMS</title>
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
                <h1 class="font-bold text-lg">Kelola Dokumen Regulasi (JDIH) & Aset Desa</h1>
            </div>
            <a href="../dokumen.php" target="_blank" class="bg-amber-500 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-eye"></i> Pratinjau Portal Dokumen
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
            
            <!-- FORM UPLOAD DOKUMEN -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h2 class="font-bold text-lg text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-emerald-700"></i> Publikasikan Dokumen Baru
                    </h2>

                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Dokumen <span class="text-rose-500">*</span></label>
                            <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold bg-white">
                                <option value="Peraturan & Produk Legislasi Desa">Peraturan & Produk Legislasi (JDIH)</option>
                                <option value="Inventarisasi Aset & Informasi">Inventarisasi Aset & SILPA</option>
                                <option value="Rencana Pembangunan Jangka Menengah (RPJM)">Rencana Pembangunan (RPJM)</option>
                                <option value="Lain Lainnya">Visi Misi & Lain Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Dokumen / Peraturan <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" required placeholder="Contoh: Perdes No. 2 Tentang Ketahanan Pangan 2026" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Berkas (PDF / Excel / Word) <span class="text-rose-500">*</span></label>
                            <input type="file" name="file_doc" 
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-600 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100">
                            <span class="text-[10px] text-slate-400">Mendukung file: .pdf, .docx, .xlsx, .pptx</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Pengesahan / Rilis</label>
                            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" 
                                   class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan Singkat</label>
                            <textarea name="keterangan" rows="3" placeholder="Ringkasan atau poin utama dalam dokumen ini..." 
                                      class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-xs leading-relaxed"></textarea>
                        </div>

                        <div class="pt-3">
                            <button type="submit" name="upload" class="w-full bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold py-3 px-4 rounded-xl shadow transition-all duration-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-share-from-square"></i> Unggah & Publikasikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL DAFTAR DOKUMEN -->
            <div class="lg:col-span-7">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="font-bold text-lg text-slate-900">Daftar Berkas Publik</h2>
                            <p class="text-xs text-slate-500">Seluruh dokumen ini dapat diunduh langsung oleh masyarakat di halaman depan.</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                            <?= count($docs) ?> Berkas Terdaftar
                        </span>
                    </div>

                    <?php if (empty($docs)): ?>
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
                            <p class="text-xs">Belum ada dokumen terunggah di database. Silakan jalankan script setup_modular_db.php atau upload file baru.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($docs as $d): ?>
                                <?php
                                $type = strtolower($d['file_type'] ?? 'pdf');
                                $icon = "fa-file-pdf text-rose-600";
                                if (in_array($type, ['xlsx','xls'])) $icon = "fa-file-excel text-emerald-600";
                                if (in_array($type, ['docx','doc'])) $icon = "fa-file-word text-blue-600";
                                ?>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fa-solid <?= $icon ?> text-2xl mt-1 flex-shrink-0"></i>
                                        <div>
                                            <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">
                                                <?= htmlspecialchars($d['kategori']) ?>
                                            </span>
                                            <h3 class="font-bold text-slate-900 text-sm mt-1">
                                                <?= htmlspecialchars($d['judul']) ?>
                                            </h3>
                                            <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-1">
                                                <span>Tanggal: <?= date('d/m/Y', strtotime($d['tanggal'] ?? 'now')) ?></span>
                                                <span>&bull;</span>
                                                <span>Ukuran: <?= $d['file_size_formatted'] ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="../<?= htmlspecialchars($d['file_path']) ?>" target="_blank" download class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center hover:bg-emerald-200 transition-colors" title="Unduh File">
                                            <i class="fa-solid fa-download text-xs"></i>
                                        </a>
                                        <a href="dokumen.php?hapus=<?= $d['id'] ?>" onclick="return confirm('Hapus file dari sistem?')" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </a>
                                    </div>
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
