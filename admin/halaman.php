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
    $chk = @mysqli_query($conn, "SHOW TABLES LIKE 'halaman_statis'");
    if ($chk && mysqli_num_rows($chk) > 0) {
        $tableExists = true;
    }
}

// Hapus Halaman
if (isset($_GET['hapus']) && $tableExists) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM halaman_statis WHERE id = $id");
    header("Location: halaman.php?msg=deleted");
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $msg = "Halaman berhasil dihapus dari sistem.";
}

// Mode Edit Halaman
$editMode = false;
$editData = ['id' => '', 'judul' => '', 'slug' => '', 'konten' => ''];

if (isset($_GET['edit']) && $tableExists) {
    $id = (int)$_GET['edit'];
    $resE = mysqli_query($conn, "SELECT * FROM halaman_statis WHERE id = $id");
    if ($resE && mysqli_num_rows($resE) > 0) {
        $editData = mysqli_fetch_assoc($resE);
        $editMode = true;
    }
}

// Simpan atau Update Halaman
if (isset($_POST['simpan']) && $tableExists) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    }
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);
    $id_edit = (int)$_POST['id_edit'];
    
    if ($id_edit > 0) {
        $upd = mysqli_query($conn, "UPDATE halaman_statis SET judul='$judul', slug='$slug', konten='$konten' WHERE id=$id_edit");
        if ($upd) {
            $msg = "Perubahan halaman berhasil disimpan!";
            $editMode = false;
            $editData = ['id' => '', 'judul' => '', 'slug' => '', 'konten' => ''];
        } else {
            $msg = "Gagal memperbarui halaman: " . mysqli_error($conn);
        }
    } else {
        // Tambah baru
        $chkSlug = mysqli_query($conn, "SELECT id FROM halaman_statis WHERE slug='$slug'");
        if (mysqli_num_rows($chkSlug) > 0) {
            $slug .= '-' . time();
        }
        $ins = mysqli_query($conn, "INSERT INTO halaman_statis (judul, slug, konten) VALUES ('$judul', '$slug', '$konten')");
        if ($ins) {
            $msg = "Halaman baru berhasil dibuat! Kini Anda bisa menautkannya ke Navbar di menu Kelola Navbar.";
        } else {
            $msg = "Gagal menyimpan halaman baru: " . mysqli_error($conn);
        }
    }
}

// Ambil Daftar Halaman
$pages = [];
if ($tableExists) {
    $res = mysqli_query($conn, "SELECT * FROM halaman_statis ORDER BY id DESC");
    while ($p = mysqli_fetch_assoc($res)) {
        $pages[] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Halaman Statis - Admin CMS</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TINYMCE WYSIWYG RICH TEXT EDITOR (TANPA API KEY) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#editorKonten',
            height: 480,
            menubar: false,
            branding: false,
            promotion: false,
            language: 'id', // Bahasa Indonesia jika tesedia, default visual icon mudah dipikirkan
            plugins: 'advlist autolink lists link image table charmap preview fullscreen wordcount',
            toolbar: 'blocks | bold italic underline forecolor | alignleft aligncenter alignright | bullist numlist | link image table | removeformat fullscreen preview',
            block_formats: 'Paragraf Biasa=p; Subjudul Besar (H2)=h2; Subjudul Sedang (H3)=h3; Subjudul Kecil (H4)=h4; Kutipan Resmi=blockquote',
            content_style: `
                body { 
                    font-family: 'Inter', Helvetica, Arial, sans-serif; 
                    font-size: 15px; 
                    color: #1e293b; 
                    line-height: 1.7; 
                    padding: 12px;
                }
                h2, h3, h4 { color: #0f172a; font-weight: bold; margin-top: 1.5rem; margin-bottom: 0.5rem; }
                h2 { font-size: 1.5rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; }
                h3 { font-size: 1.25rem; color: #165f36; }
                p { margin-bottom: 1rem; }
                ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
                ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
                blockquote { border-left: 4px solid #c4891f; background: #fffbeb; padding: 10px 16px; margin: 0 0 1rem 0; font-style: italic; border-radius: 0 8px 8px 0; }
                figure { margin: 24px auto; text-align: center; }
                figure img { max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); display: block; margin: 0 auto; }
                figcaption { font-size: 13px; color: #475569; font-style: italic; margin-top: 10px; background: #f1f5f9; padding: 4px 16px; border-radius: 999px; display: inline-block; border: 1px solid #cbd5e1; }
            `,
            images_upload_url: 'upload_gambar_editor.php',
            automatic_uploads: true,
            convert_urls: false,
            relative_urls: false,
            file_picker_types: 'image',
            image_caption: true,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Pastikan isi textarea tersimpan ke DOM saat disubmit
                });
            }
        });
    });
    </script>
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <!-- HEADER ADMIN -->
    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="index.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Manajemen Informasi & Layanan Publik (Visual Editor)</h1>
                    <p class="text-[11px] text-amber-300">Editor Visual Terpadu - Kelola Konten Resmi Pemerintahan Tanpa Kode!</p>
                </div>
            </div>
            <a href="../page.php?slug=sejarah-visi-misi" target="_blank" class="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-eye"></i> Pratinjau di Website Depan
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

        <!-- BANNER PETUNJUK KEMUDAHAN AWAM -->
        <div class="bg-gradient-to-r from-emerald-900 to-[#165f36] text-white p-5 rounded-3xl shadow-md mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-emerald-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-500/20 border border-amber-400/40 text-amber-300 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-base text-white">Menulis Kini Sangat Mudah & Otomatis Tanpa Tag HTML!</h3>
                    <p class="text-xs text-emerald-100/90 mt-0.5 leading-relaxed">
                        Anda tidak perlu paham bahasa kode. Ketik langsung seperti di Microsoft Word! Gunakan ikon di atas kotak teks untuk <b>Teks Tebal (Bold)</b>, <i>Miring</i>, atau <b>Daftar Bullet (Titik-Titik)</b>.
                    </p>
                </div>
            </div>
            <button type="button" onclick="openUploadModal()" 
                    class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-extrabold px-5 py-3 rounded-xl text-xs shadow-lg transition-all flex items-center gap-2 whitespace-nowrap transform hover:scale-105">
                <i class="fa-solid fa-camera text-base text-emerald-900"></i>
                <span>📸 Sisipkan Gambar + Caption (Mudah)</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- FORM EDITOR WYSIWYG HALAMAN -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <h2 class="font-bold text-lg text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid <?= $editMode ? 'fa-pen-to-square text-amber-600' : 'fa-file-circle-plus text-emerald-700' ?>"></i>
                            <?= $editMode ? 'Edit Halaman: ' . htmlspecialchars($editData['judul']) : 'Buat Halaman Statis Baru' ?>
                        </span>
                        <?php if ($editMode): ?>
                            <a href="halaman.php" class="text-xs text-slate-500 hover:underline font-semibold bg-slate-100 px-3 py-1 rounded-lg">Batal Edit</a>
                        <?php endif; ?>
                    </h2>

                    <form action="" method="POST" class="space-y-5" onsubmit="tinymce.triggerSave()">
                        <input type="hidden" name="id_edit" value="<?= $editData['id'] ?>">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Halaman / Artikel <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" required value="<?= htmlspecialchars($editData['judul']) ?>" placeholder="Contoh: Potensi Pertanian dan UMKM Warga" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-base font-bold text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Slug / Alamat Tautan URL (Opsional)</label>
                            <input type="text" name="slug" value="<?= htmlspecialchars($editData['slug']) ?>" placeholder="potensi-pertanian-dan-umkm" 
                                   class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-xs font-mono bg-slate-50">
                            <span class="text-[11px] text-slate-400 mt-0.5 block">Kosongkan agar dibuat otomatis oleh sistem. Nanti bisa diakses melaui: <code>page.php?slug=...</code></span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Isi Konten Artikel / Penjelasan <span class="text-rose-500">*</span></label>
                                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    <i class="fa-solid fa-magic text-amber-500"></i> Mode Visual Otomatis (Tanpa Kode HTML)
                                </span>
                            </div>
                            
                            <!-- WYSIWYG EDITOR TEXTAREA -->
                            <textarea id="editorKonten" name="konten" rows="12" placeholder="Ketikkan isi cerita atau penjelasan di sini seperti biasa..." 
                                      class="w-full p-4 rounded-xl border border-slate-300"><?= htmlspecialchars($editData['konten']) ?></textarea>
                            
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 mt-2 flex items-center justify-between text-xs text-slate-600">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-lightbulb text-amber-500"></i>
                                    <b>Tips Awam:</b> Untuk menambah gambar ber-caption dengan cepat, tekan tombol di kanan:
                                </span>
                                <button type="button" onclick="openUploadModal()" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg transition-all">
                                    <i class="fa-solid fa-camera mr-1"></i> Masukkan Gambar + Caption
                                </button>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" name="simpan" class="w-full bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold py-4 px-6 rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center gap-2 text-base transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-floppy-disk text-amber-300 text-lg"></i>
                                <span><?= $editMode ? 'Simpan Perubahan Halaman Ini' : 'Terbitkan & Simpan Halaman Sekarang' ?></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DAFTAR HALAMAN STATIS TERDAFTAR -->
            <div class="lg:col-span-5">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm sticky top-24">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="font-bold text-lg text-slate-900">Daftar Halaman Desa</h2>
                            <p class="text-xs text-slate-500">Klik 'Edit' untuk membenahi isi konten dengan cepat.</p>
                        </div>
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200">
                            <?= count($pages) ?> Halaman
                        </span>
                    </div>

                    <?php if (empty($pages)): ?>
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400">
                            <i class="fa-solid fa-file-circle-xmark text-4xl mb-3 text-slate-300"></i>
                            <p class="text-xs">Belum ada halaman kustom tersimpan di database.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3.5 max-h-[600px] overflow-y-auto pr-1">
                            <?php foreach ($pages as $pg): ?>
                                <div class="p-4 rounded-2xl <?= $editData['id'] == $pg['id'] ? 'bg-amber-50 border-2 border-amber-400' : 'bg-slate-50 border border-slate-200' ?> shadow-xs flex items-center justify-between gap-3 hover:bg-white transition-all">
                                    <div class="overflow-hidden">
                                        <h3 class="font-bold text-slate-900 text-sm truncate">
                                            <?= htmlspecialchars($pg['judul']) ?>
                                        </h3>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <span class="text-[11px] font-mono text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 truncate max-w-[160px]">
                                                <?= htmlspecialchars($pg['slug']) ?>
                                            </span>
                                            <span class="text-[10px] text-slate-400">
                                                <?= date('d/m/Y', strtotime($pg['updated_at'] ?? 'now')) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <a href="../page.php?slug=<?= $pg['slug'] ?>" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center hover:bg-emerald-200 transition-colors" title="Lihat di Web Depan">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                        <a href="halaman.php?edit=<?= $pg['id'] ?>" class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-900 hover:bg-amber-200 text-xs font-bold transition-colors">
                                            Edit
                                        </a>
                                        <a href="halaman.php?hapus=<?= $pg['id'] ?>" onclick="return confirm('Yakin ingin menghapus halaman ini beserta isinya?')" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors" title="Hapus Halaman">
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

    <!-- ================= MODAL KUSTOM: UPLOAD GAMBAR + CAPTION ================= -->
    <div id="imageUploadModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-200 transform transition-all">
            <div class="bg-[#165f36] text-white px-6 py-4 flex items-center justify-between border-b-4 border-amber-500">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white text-emerald-800 flex items-center justify-center text-xl font-bold shadow">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-white">Sisipkan Foto & Caption Ke Artikel</h3>
                        <p class="text-[11px] text-emerald-200">Sistem akan otomatis mengatur tata letak rapi</p>
                    </div>
                </div>
                <button type="button" onclick="closeUploadModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form id="customUploadForm" onsubmit="handleImageUpload(event)" class="p-6 space-y-5">
                <!-- PILIH FILE FOTO -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">1. Pilih Foto / Gambar dari Komputer <span class="text-rose-500">*</span></label>
                    <div class="border-2 border-dashed border-emerald-600/60 hover:border-emerald-700 rounded-2xl p-4 text-center bg-emerald-50/50 cursor-pointer relative" onclick="document.getElementById('fileImageInput').click()">
                        <input type="file" id="fileImageInput" accept="image/*" required class="hidden" onchange="previewSelectedImage(this)">
                        <div id="uploadPlaceholder">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-emerald-700 mb-2"></i>
                            <p class="text-xs font-bold text-slate-700">Klik untuk memilih gambar dari Laptop/HP Anda</p>
                            <span class="text-[10px] text-slate-400">Format didukung: JPG, PNG, WEBP (Maksimal 10 MB)</span>
                        </div>
                        <div id="imagePreviewContainer" class="hidden mt-2">
                            <img id="imagePreviewEl" src="" alt="Preview" class="max-h-48 mx-auto rounded-xl shadow-md border border-slate-300 object-contain">
                            <span id="fileNameDisplay" class="text-xs font-mono font-bold text-emerald-800 block mt-2"></span>
                        </div>
                    </div>
                </div>

                <!-- TULIS CAPTION / KETERANGAN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">2. Tulis Keterangan / Caption Gambar <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-tag absolute left-4 top-3.5 text-amber-500"></i>
                        <input type="text" id="captionInput" placeholder="Contoh: Foto Bapak Kepala Desa meninjau hasil panen pertanian warga" 
                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Keterangan ini akan tampil dengan bingkai elegan tepat di bawah foto.</p>
                </div>

                <!-- POSISI TAMPILAN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">3. Posisi Gambar Di Dalam Artikel</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="border border-slate-300 p-2.5 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 text-xs font-bold peer-checked:border-emerald-600">
                            <input type="radio" name="img_align" value="center" checked class="text-emerald-700 mb-1">
                            <i class="fa-solid fa-align-center text-base mb-1 text-slate-600"></i> Rata Tengah
                        </label>
                        <label class="border border-slate-300 p-2.5 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 text-xs font-bold">
                            <input type="radio" name="img_align" value="left" class="text-emerald-700 mb-1">
                            <i class="fa-solid fa-align-left text-base mb-1 text-slate-600"></i> Rata Kiri
                        </label>
                        <label class="border border-slate-300 p-2.5 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 text-xs font-bold">
                            <input type="radio" name="img_align" value="right" class="text-emerald-700 mb-1">
                            <i class="fa-solid fa-align-right text-base mb-1 text-slate-600"></i> Rata Kanan
                        </label>
                    </div>
                </div>

                <div class="pt-3 flex gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeUploadModal()" class="px-5 py-3 rounded-xl bg-slate-100 font-bold text-xs text-slate-600 hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" id="btnUploadSubmit" class="flex-grow bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold py-3.5 px-6 rounded-xl shadow-lg transition-all text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-amber-300 text-lg"></i>
                        <span>Upload & Masukkan Ke Halaman</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openUploadModal() {
        const modal = document.getElementById('imageUploadModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
        }, 10);
    }

    function closeUploadModal() {
        const modal = document.getElementById('imageUploadModal');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('customUploadForm').reset();
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('uploadPlaceholder').classList.remove('hidden');
        }, 300);
    }

    function previewSelectedImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreviewEl').src = e.target.result;
                document.getElementById('fileNameDisplay').textContent = "Selected: " + file.name;
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    async function handleImageUpload(e) {
        e.preventDefault();
        const fileInput = document.getElementById('fileImageInput');
        const captionText = document.getElementById('captionInput').value.trim();
        const alignVal = document.querySelector('input[name="img_align"]:checked').value;
        const submitBtn = document.getElementById('btnUploadSubmit');

        if (!fileInput.files || !fileInput.files[0]) {
            alert("Harap pilih file foto terlebih dahulu!");
            return;
        }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-amber-300"></i> Mengunggah...';

            const response = await fetch('upload_gambar_editor.php', {
                method: 'POST',
                body: formData
            });

            const res = await response.json();

            if (res.success && res.public_url) {
                // Tentukan style alignment HTML
                let figureStyle = 'margin: 24px auto; text-align: center; display: block; clear: both;';
                let floatStyle = '';
                if (alignVal === 'left') {
                    figureStyle = 'float: left; margin: 10px 20px 10px 0; text-align: center; max-width: 50%;';
                } else if (alignVal === 'right') {
                    figureStyle = 'float: right; margin: 10px 0 10px 20px; text-align: center; max-width: 50%;';
                }

                // Bangun HTML snippet bergaya premium dan langsung sisipkan ke TinyMCE!
                const htmlInsert = `
                    <figure style="${figureStyle}">
                        <img src="../${res.public_url}" alt="${captionText || 'Gambar Bukti'}" style="max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.12); display: block; margin: 0 auto; border: 1px solid #e2e8f0;" />
                        ${captionText ? `<figcaption style="font-size: 13px; font-weight: 500; color: #475569; font-style: italic; margin-top: 10px; background-color: #f1f5f9; padding: 5px 18px; border-radius: 9999px; display: inline-block; border: 1px solid #cbd5e1; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">📸 ${captionText}</figcaption>` : ''}
                    </figure>
                    <p>&nbsp;</p>
                `;

                tinymce.activeEditor.insertContent(htmlInsert);
                closeUploadModal();
            } else {
                alert("Gagal mengunggah: " + (res.error || "Terjadi kesalahan server"));
            }
        } catch (err) {
            alert("Terjadi kesalahan koneksi saat mengunggah gambar.");
            console.error(err);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up text-amber-300 text-lg"></i><span>Upload & Masukkan Ke Halaman</span>';
        }
    }
    </script>
</body>
</html>
