<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/database.php';
require_once __DIR__ . '/../config/upload_helper.php';

if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id = $id");
$berita = mysqli_fetch_assoc($query);

if (!$berita) {
    echo "<script>alert('Berita tidak ditemukan!'); window.location='berita.php';</script>";
    exit;
}

$msg = "";
if (isset($_POST['submit'])) {
    $judul  = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi    = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal = $_POST['tanggal'] ?: date('Y-m-d');
    $foto   = $berita['foto'];

    // Cek apakah ada file foto baru yang diupload
    if (!empty($_FILES['foto']['name'])) {
        [$uploaded, $newFoto] = upload_image($_FILES['foto'], __DIR__ . '/../uploads/berita', 'berita_');
        if (!$uploaded) {
            $msg = $newFoto;
        } else {
            $foto = $newFoto;
        }
    }

    if (empty($msg)) {
        $sql = "UPDATE berita SET judul='$judul', isi='$isi', foto='$foto', tanggal='$tanggal' WHERE id=$id";
        $update = mysqli_query($conn, $sql);

        if ($update) {
            echo "<script>alert('Berita berhasil diperbarui!'); window.location='berita.php';</script>";
            exit;
        } else {
            $msg = "Gagal memperbarui berita: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita - Admin CMS</title>
    <link rel="icon" href="../logoboyolali.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TINYMCE WYSIWYG RICH TEXT EDITOR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#editorIsiBerita',
            height: 450,
            menubar: false,
            branding: false,
            promotion: false,
            plugins: 'advlist autolink lists link image table charmap preview fullscreen wordcount',
            toolbar: 'blocks | bold italic underline forecolor | alignleft aligncenter alignright | bullist numlist | link image table | removeformat fullscreen preview',
            block_formats: 'Paragraf Biasa=p; Subjudul Besar=h2; Subjudul Sedang=h3; Kutipan=blockquote',
            content_style: `
                body { font-family: 'Inter', sans-serif; font-size: 15px; color: #1e293b; line-height: 1.7; padding: 12px; }
                h2, h3 { color: #0f172a; font-weight: bold; margin-top: 1.25rem; }
                h2 { font-size: 1.4rem; } h3 { font-size: 1.2rem; }
                p { margin-bottom: 1rem; }
                ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
                ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
                figure { margin: 20px auto; text-align: center; }
                figure img { max-width: 100%; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                figcaption { font-size: 13px; color: #475569; font-style: italic; margin-top: 8px; background: #f1f5f9; padding: 3px 14px; border-radius: 999px; display: inline-block; }
            `,
            images_upload_url: 'upload_gambar_editor.php',
            automatic_uploads: true,
            convert_urls: false,
            relative_urls: false,
            setup: function(editor) {
                editor.on('change', function() { editor.save(); });
            }
        });
    });
    </script>
</head>

<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <header class="bg-[#165f36] text-white shadow-md border-b-4 border-amber-500">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="berita.php" class="w-9 h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Edit Berita / Agenda Desa</h1>
                    <p class="text-[11px] text-amber-300">Editor Visual Otomatis - Tanpa Kode HTML</p>
                </div>
            </div>
            <a href="../detail-berita.php?id=<?= $id ?>" target="_blank" class="bg-amber-500 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-eye"></i> Lihat Berita Web
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-8">
        <?php if (!empty($msg)): ?>
            <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-3 rounded-2xl mb-6 flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                <span class="text-sm font-semibold"><?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h2 class="font-bold text-xl text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-700"></i> Form Edit Berita Desa
                </h2>
                <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    ID Artikel: #<?= $id ?>
                </span>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="space-y-6" onsubmit="tinymce.triggerSave()">
                
                <div>
                    <label class="block mb-2 font-bold text-xs uppercase text-slate-700">Judul Berita <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($berita['judul']) ?>" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-emerald-600 text-base font-bold" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label class="block mb-2 font-bold text-xs uppercase text-slate-700">Tanggal Rilis <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d', strtotime($berita['tanggal'])) ?>" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>

                    <div class="space-y-2">
                        <label class="block mb-1 font-bold text-xs uppercase text-slate-700">Ganti Foto Sampul Utama (Opsional)</label>
                        <input type="file" name="foto" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:font-bold file:bg-emerald-50 file:text-emerald-800" accept="image/*">
                        <p class="text-[11px] text-slate-500">Biarkan kosong jika tidak ingin mengubah foto sampul saat ini.</p>
                        
                        <?php if (!empty($berita['foto'])): ?>
                            <?php $currPhoto = resolve_uploaded_image($berita['foto']); ?>
                            <div class="mt-2 p-2 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-3">
                                <img src="../<?= htmlspecialchars($currPhoto) ?>" onerror="this.onerror=null; this.src='../assets/img/utama.jpg';" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                <div class="text-xs">
                                    <span class="font-bold text-slate-700 block">Foto Saat Ini:</span>
                                    <span class="text-slate-500 truncate max-w-[200px] block"><?= htmlspecialchars($berita['foto']) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block font-bold text-xs uppercase text-slate-700">Isi Berita / Artikel <span class="text-rose-500">*</span></label>
                        <button type="button" onclick="openUploadModal()" class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-extrabold text-xs px-3.5 py-1.5 rounded-lg transition-all shadow flex items-center gap-1.5">
                            <i class="fa-solid fa-camera text-sm"></i>
                            <span>📸 Sisipkan Foto di Dalam Teks + Caption</span>
                        </button>
                    </div>
                    
                    <textarea id="editorIsiBerita" name="isi" rows="12" class="w-full border rounded-xl p-4" placeholder="Tulis berita desa di sini..."><?= htmlspecialchars($berita['isi']) ?></textarea>
                    <p class="text-xs text-slate-500 mt-2 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-amber-500"></i> Ketik biasa seperti di Microsoft Word. Tekan tombol toolbar di atas kotak untuk <b>Tebal</b>, <i>Miring</i>, atau <b>Daftar Bullet</b>.
                    </p>
                </div>

                <div class="pt-4 flex items-center gap-4 border-t border-slate-100">
                    <button type="submit" name="submit" class="bg-[#165f36] hover:bg-[#0e3f23] text-white font-bold px-8 py-3.5 rounded-xl shadow-lg transition-all text-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-amber-300"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="berita.php" class="px-6 py-3.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-sm transition-colors">Batal</a>
                </div>

            </form>
        </div>
    </main>

    <!-- MODAL SISIP KAN GAMBAR + CAPTION -->
    <div id="imageUploadModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-200">
            <div class="bg-[#165f36] text-white px-6 py-4 flex items-center justify-between border-b-4 border-amber-500">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-camera text-2xl text-amber-400"></i>
                    <div>
                        <h3 class="font-bold text-base text-white">Sisipkan Foto & Caption Di Teks</h3>
                        <p class="text-[11px] text-emerald-200">Otomatis rapi tanpa perlu kode HTML</p>
                    </div>
                </div>
                <button type="button" onclick="closeUploadModal()" class="w-8 h-8 rounded-lg bg-white/10 text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="handleImageUpload(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">1. Pilih Foto dari Komputer <span class="text-rose-500">*</span></label>
                    <input type="file" id="fileImageInput" accept="image/*" required class="w-full p-2 border rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">2. Tulis Keterangan / Caption Gambar <span class="text-rose-500">*</span></label>
                    <input type="text" id="captionInput" placeholder="Contoh: Warga desa berpartisipasi dalam kegiatan" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div class="pt-3 flex gap-3">
                    <button type="button" onclick="closeUploadModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-xs font-bold text-slate-600">Batal</button>
                    <button type="submit" id="btnUploadSubmit" class="flex-grow bg-[#165f36] text-white font-bold py-3 rounded-xl text-sm shadow">Upload & Masukkan Ke Berita</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openUploadModal() {
        const modal = document.getElementById('imageUploadModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { modal.classList.remove('opacity-0'); modal.classList.add('opacity-100'); }, 10);
    }
    function closeUploadModal() {
        const modal = document.getElementById('imageUploadModal');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        setTimeout(() => { modal.classList.remove('flex'); modal.classList.add('hidden'); }, 300);
    }
    async function handleImageUpload(e) {
        e.preventDefault();
        const fileInput = document.getElementById('fileImageInput');
        const captionText = document.getElementById('captionInput').value.trim();
        const submitBtn = document.getElementById('btnUploadSubmit');
        if (!fileInput.files[0]) return alert("Pilih file gambar!");
        
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengunggah...';
        
        try {
            const response = await fetch('upload_gambar_editor.php', { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success && res.public_url) {
                const htmlInsert = `
                    <figure style="margin: 20px auto; text-align: center;">
                        <img src="${res.public_url}" alt="${captionText}" style="max-width: 100%; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.12); display: block; margin: 0 auto; border: 1px solid #e2e8f0;" />
                        ${captionText ? `<figcaption style="font-size: 13px; color: #475569; font-style: italic; margin-top: 8px; background: #f1f5f9; padding: 4px 16px; border-radius: 999px; display: inline-block; border: 1px solid #cbd5e1;">📸 ${captionText}</figcaption>` : ''}
                    </figure>
                    <p>&nbsp;</p>
                `;
                tinymce.activeEditor.insertContent(htmlInsert);
                closeUploadModal();
            } else { alert("Gagal: " + res.error); }
        } catch (err) { alert("Terjadi kesalahan."); }
        finally { submitBtn.disabled = false; submitBtn.textContent = 'Upload & Masukkan Ke Berita'; }
    }
    </script>
</body>
</html>
