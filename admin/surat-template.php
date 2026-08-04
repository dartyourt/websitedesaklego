<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

$mode = $_GET['mode'] ?? 'list';
$editData = null;

if ($mode === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $q = mysqli_query($koneksi, "SELECT * FROM surat_template WHERE id=$id");
    $editData = mysqli_fetch_assoc($q);
}

if ($mode === 'import_preview' && isset($_SESSION['import_html'])) {
    $editData = [
        'id' => '',
        'nama_surat' => 'Template Baru (Dari Word)',
        'kode_surat' => '',
        'konten_html' => $_SESSION['import_html']
    ];
    unset($_SESSION['import_html']);
    $mode = 'add';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Template Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="bg-blue-700 text-white p-4 flex justify-between items-center">
    <h1 class="font-bold text-lg">Manajemen Template Surat Dinamis</h1>
    <a href="surat.php" class="text-sm hover:underline bg-blue-800 px-3 py-1 rounded">Kembali ke Menu Surat</a>
</div>

<div class="p-6">
    <?php if ($mode === 'list'): ?>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Daftar Template Kustom</h2>
            <div class="space-x-2">
                <a href="?mode=add" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-bold">+ Buat Baru</a>
                <a href="surat-import.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold">📁 Import dari Word</a>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 border-b">ID</th>
                        <th class="p-3 border-b">Kode Surat</th>
                        <th class="p-3 border-b">Nama Surat</th>
                        <th class="p-3 border-b">Dibuat Pada</th>
                        <th class="p-3 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT * FROM surat_template ORDER BY id DESC");
                    while ($r = mysqli_fetch_assoc($q)):
                    ?>
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3"><?= $r['id'] ?></td>
                        <td class="p-3 font-mono"><?= htmlspecialchars($r['kode_surat']) ?></td>
                        <td class="p-3 font-bold text-blue-800"><?= htmlspecialchars($r['nama_surat']) ?></td>
                        <td class="p-3 text-sm text-gray-600"><?= $r['created_at'] ?></td>
                        <td class="p-3 text-center space-x-2">
                            <a href="surat-cetak-dinamis.php?template_id=<?= $r['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Gunakan (Cetak)</a>
                            <a href="?mode=edit&id=<?= $r['id'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                            <a href="surat-template-proses.php?action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus template ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <!-- Form Add/Edit -->
        <div class="bg-white rounded shadow p-6 max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-6"><?= $mode === 'edit' ? 'Edit Template' : 'Buat Template Baru' ?></h2>
            
            <div class="bg-blue-50 border border-blue-200 p-4 rounded mb-6 text-sm text-blue-800">
                <strong>Informasi Placeholder:</strong> Gunakan format berikut di dalam editor untuk diisi otomatis saat surat dicetak.
                <div class="grid grid-cols-3 gap-2 mt-2 font-mono text-xs">
                    <div>[NAMA_LENGKAP]</div>
                    <div>[NIK]</div>
                    <div>[NO_KK]</div>
                    <div>[JENIS_KELAMIN]</div>
                    <div>[TEMPAT_LAHIR]</div>
                    <div>[TANGGAL_LAHIR]</div>
                    <div>[AGAMA]</div>
                    <div>[PEKERJAAN]</div>
                    <div>[ALAMAT_LENGKAP]</div>
                    <div>[KEPERLUAN]</div>
                    <div>[KETERANGAN_LAIN]</div>
                    <div>[PEJABAT_JABATAN]</div>
                    <div>[PEJABAT_NAMA]</div>
                    <div>[TANGGAL_SURAT]</div>
                </div>
            </div>

            <form action="surat-template-proses.php?action=<?= $mode === 'edit' ? 'edit' : 'add' ?>" method="POST">
                <?php if ($mode === 'edit'): ?>
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-bold mb-1">Nama Surat</label>
                        <input type="text" name="nama_surat" value="<?= $editData ? htmlspecialchars($editData['nama_surat']) : '' ?>" class="w-full border p-2 rounded" required placeholder="Contoh: Surat Izin Keramaian">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Kode Surat</label>
                        <input type="text" name="kode_surat" value="<?= $editData ? htmlspecialchars($editData['kode_surat']) : '' ?>" class="w-full border p-2 rounded" placeholder="Contoh: 145/IX/2026">
                    </div>
                </div>

                <div class="mb-4 border bg-gray-100 p-4 rounded text-center">
                    <p class="text-xs text-gray-500 mb-2">Area kop surat (Logo dan tulisan PEMERINTAH KABUPATEN dst) akan ditambahkan otomatis di atas halaman saat dicetak. <strong>Jangan masukkan KOP di editor ini.</strong></p>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-1">Konten Surat</label>
                    <textarea id="summernote" name="konten_html"><?= $editData ? htmlspecialchars($editData['konten_html']) : '<h3 style="text-align: center;"><b><span style="text-decoration-line: underline;">SURAT KETERANGAN [NAMA_SURAT]</span></b></h3><p style="text-align: center;">Nomor: [KODE_SURAT]</p><p><br></p><p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p><table style="width: 100%;"><tbody><tr><td style="width: 25.0000%;">Nama</td><td>: [NAMA_LENGKAP]</td></tr><tr><td style="width: 25.0000%;">NIK</td><td>: [NIK]</td></tr><tr><td style="width: 25.0000%;">Alamat</td><td>: [ALAMAT_LENGKAP]</td></tr></tbody></table><p><br></p><p>Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p><p><br></p><table style="width: 100%;"><tbody><tr><td style="width: 50.0000%;"><br></td><td style="width: 50%; text-align: center;">[TANGGAL_SURAT]<br>[PEJABAT_JABATAN]<br><br><br><br><b>[PEJABAT_NAMA]</b></td></tr></tbody></table><p><br></p>' ?></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold">Simpan Template</button>
                    <a href="?mode=list" class="bg-gray-500 text-white px-6 py-2 rounded font-bold">Batal</a>
                </div>
            </form>
        </div>

        <script>
            $(document).ready(function() {
                $('#summernote').summernote({
                    height: 500,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });
            });
        </script>
    <?php endif; ?>
</div>

</body>
</html>
