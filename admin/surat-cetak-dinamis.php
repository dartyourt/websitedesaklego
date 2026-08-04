<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/database.php';

function tglIndonesia($tanggal){
    if (empty($tanggal) || $tanggal === '0000-00-00') return '';
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $pecah = explode('-', $tanggal);
    if (count($pecah) != 3) return $tanggal;
    $blnIndex = (int)$pecah[1];
    if ($blnIndex < 1 || $blnIndex > 12) return $tanggal;
    return (int)$pecah[2] . ' ' . $bulan[$blnIndex] . ' ' . $pecah[0];
}

$template_id = (int)($_GET['template_id'] ?? 0);
$qTpl = mysqli_query($koneksi, "SELECT * FROM surat_template WHERE id=$template_id");
$template = mysqli_fetch_assoc($qTpl);

if (!$template) {
    die("Template surat tidak ditemukan.");
}

// Ambil data pejabat
$pejabatDB = [];
$qPejabat = mysqli_query($koneksi,"SELECT * FROM pejabat WHERE status=1");
while($row = mysqli_fetch_assoc($qPejabat)){
    $pejabatDB[$row['jabatan']] = $row;
}

$dataPenduduk = null;
$hasilCari = null;
$modePreview = isset($_POST['preview']);

// 1. Pencarian Penduduk
if (isset($_GET['cari']) && !empty($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    $hasilCari = mysqli_query($koneksi, "
        SELECT * FROM penduduk
        WHERE NIK LIKE '%$keyword%' OR NAMA_LGKP LIKE '%$keyword%'
    ");
    if (mysqli_num_rows($hasilCari) == 1) {
        $dataPenduduk = mysqli_fetch_assoc($hasilCari);
    }
}

// 2. Klik Pilih dari tabel hasil
if (isset($_GET['nik'])) {
    $nik = mysqli_real_escape_string($koneksi, $_GET['nik']);
    $q = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);
}

// 3. Preview
$renderedHtml = '';
if (isset($_POST['preview']) && !empty($_POST['nik'])) {
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $q = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {
        $jabatanSurat = '';
        $namaPejabat  = '';
        $pejabat = $_POST['pejabat'];
        if ($pejabat == 'kepala' && isset($pejabatDB['kepala'])) {
            $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
            $namaPejabat  = $pejabatDB['kepala']['nama'];
        } elseif ($pejabat == 'sekdes' && isset($pejabatDB['sekdes'])) {
            $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
            $namaPejabat  = $pejabatDB['sekdes']['nama'];
        }

        // Replace placeholders
        $html = $template['konten_html'];
        $map = [
            '[NAMA_SURAT]' => htmlspecialchars($template['nama_surat']),
            '[KODE_SURAT]' => htmlspecialchars($_POST['kode_surat'] ?? $template['kode_surat']),
            '[NAMA_LENGKAP]' => htmlspecialchars($dataPenduduk['NAMA_LGKP']),
            '[NIK]' => htmlspecialchars($dataPenduduk['NIK']),
            '[NO_KK]' => htmlspecialchars($dataPenduduk['NO_KK']),
            '[JENIS_KELAMIN]' => htmlspecialchars($dataPenduduk['JENIS_KELAMIN']),
            '[TEMPAT_LAHIR]' => htmlspecialchars($dataPenduduk['TMPT_LAHIR']),
            '[TANGGAL_LAHIR]' => tglIndonesia($dataPenduduk['TGL_LAHIR']),
            '[AGAMA]' => htmlspecialchars($dataPenduduk['AGAMA']),
            '[PEKERJAAN]' => htmlspecialchars($dataPenduduk['PEKERJAAN']),
            '[ALAMAT_LENGKAP]' => htmlspecialchars($dataPenduduk['DUSUN'] . " RT " . $dataPenduduk['RT'] . " RW " . $dataPenduduk['RW']),
            '[KEPERLUAN]' => nl2br(htmlspecialchars($_POST['keperluan'] ?? '')),
            '[KETERANGAN_LAIN]' => nl2br(htmlspecialchars($_POST['keterangan_lain'] ?? '')),
            '[PEJABAT_JABATAN]' => $jabatanSurat,
            '[PEJABAT_NAMA]' => $namaPejabat,
            '[TANGGAL_SURAT]' => htmlspecialchars($APP_PROFIL['nama_desa_clean']) . ', ' . tglIndonesia(date('Y-m-d'))
        ];

        foreach ($map as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        $renderedHtml = $html;

        // Note: For a fully dynamic system, we might want to store generated letters.
        // But for this generic builder, we'll just show the preview to print directly.
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak <?= htmlspecialchars($template['nama_surat']) ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@media print {
    @page { margin: 3mm 15mm 15mm 15mm; }
    body, html { background: #fff !important; }
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea {
        position: absolute; top: 0; left: 0; width: 100%;
        background: #fff !important; border: none !important;
        box-shadow: none !important; border-radius: 0 !important;
    }
}
</style>
</head>
<body class="bg-gray-100 p-4 min-h-screen">

<div class="max-w-3xl mx-auto bg-white p-6 shadow">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-blue-800">Cetak: <?= htmlspecialchars($template['nama_surat']) ?></h1>
        <a href="surat-template.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded text-sm">Kembali</a>
    </div>

    <?php if (!$modePreview): ?>
        <!-- PENCARIAN -->
        <form method="GET" class="mb-4 bg-gray-50 p-4 border rounded">
            <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
            <label class="block text-sm font-bold mb-2">Cari NIK / Nama Pemohon</label>
            <div class="flex gap-2">
                <input type="text" name="keyword" required class="border p-2 rounded flex-1">
                <button name="cari" class="bg-blue-600 text-white px-6 rounded font-bold">Cari</button>
            </div>
        </form>

        <?php if ($hasilCari && mysqli_num_rows($hasilCari) > 1): ?>
        <div class="border p-4 mb-4">
            <h2 class="font-bold mb-2">Pilih Data Penduduk</h2>
            <table class="w-full text-sm border">
                <tr class="bg-gray-200">
                    <th class="border p-2">NIK</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Aksi</th>
                </tr>
                <?php while($p = mysqli_fetch_assoc($hasilCari)): ?>
                <tr>
                    <td class="border p-2"><?= htmlspecialchars($p['NIK']) ?></td>
                    <td class="border p-2"><?= htmlspecialchars($p['NAMA_LGKP']) ?></td>
                    <td class="border p-2 text-center">
                        <a href="?template_id=<?= $template['id'] ?>&nik=<?= $p['NIK'] ?>" class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold">Pilih</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>

        <?php if ($dataPenduduk): ?>
            <div class="border border-green-300 bg-green-50 p-4 rounded mb-4 text-sm">
                <strong>Pemohon Terpilih:</strong> <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?> (NIK: <?= htmlspecialchars($dataPenduduk['NIK']) ?>)
            </div>

            <!-- INPUT FORM -->
            <form method="POST" class="space-y-4 border p-4 rounded bg-gray-50">
                <input type="hidden" name="nik" value="<?= $dataPenduduk['NIK'] ?>">
                
                <div>
                    <label class="block text-sm font-bold mb-1">Nomor Surat</label>
                    <input type="text" name="kode_surat" value="<?= htmlspecialchars($template['kode_surat']) ?>" required class="border p-2 rounded w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Keperluan (Opsional)</label>
                    <textarea name="keperluan" class="border p-2 rounded w-full"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Keterangan Lain (Opsional)</label>
                    <textarea name="keterangan_lain" class="border p-2 rounded w-full"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Pejabat Penandatangan</label>
                    <select name="pejabat" required class="border p-2 rounded w-full">
                        <option value="kepala">Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
                        <option value="sekdes">Sekretaris <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
                    </select>
                </div>

                <button name="preview" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-bold w-full">Preview Surat</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($modePreview): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4 text-sm">
            <strong>Mode Preview:</strong> Klik teks pada surat di bawah ini jika ingin mengeditnya secara manual sebelum dicetak.
        </div>
        
        <div class="border bg-white p-8" id="printArea" contenteditable="true" style="font-family: 'Times New Roman', Times, serif; outline:none; line-height: 1.5; font-size: 12pt;">
            <!-- KOP SURAT -->
            <table width="100%" style="border-bottom: 4px solid black; margin-bottom: 20px;">
                <tr>
                    <td width="15%" style="text-align: center; padding-bottom: 10px;">
                        <img src="../assets/img/<?= htmlspecialchars($APP_PROFIL['logo']) ?>" width="80" alt="Logo">
                    </td>
                    <td style="text-align: center; padding-bottom: 10px;">
                        <p style="line-height:1.2; font-size:14pt; font-weight:600; margin:0;">PEMERINTAH KABUPATEN <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></p>
                        <p style="line-height:1.2; font-size:14pt; font-weight:600; margin:0;">KECAMATAN <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?></p>
                        <p style="line-height:1.2; font-size:18pt; font-weight:bold; margin:0;">DESA <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa_clean'])) ?></p>
                        <p style="margin-top:2px; font-size:10pt; margin-bottom:0;"><?= htmlspecialchars($APP_PROFIL['alamat']) ?>, <?= htmlspecialchars($APP_PROFIL['nama_desa_clean']) ?>, <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?> Kode Pos. <?= htmlspecialchars($APP_PROFIL['kode_pos']) ?></p>
                    </td>
                </tr>
            </table>

            <!-- CONTENT -->
            <?= $renderedHtml ?>
        </div>

        <div class="mt-6 flex justify-center gap-4">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded font-bold text-lg shadow-lg">🖨️ Cetak Surat</button>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
