
<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}
include '../../config/database.php';

function tglIndonesia($tanggal){
    if (empty($tanggal) || $tanggal === '0000-00-00') {
        return '';
    }

    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $pecah = explode('-', $tanggal);

    if (count($pecah) != 3) {
        return $tanggal;
    }

    $blnIndex = (int)$pecah[1];
    if ($blnIndex < 1 || $blnIndex > 12) {
        return $tanggal;
    }

    return (int)$pecah[2] . ' ' . $bulan[$blnIndex] . ' ' . $pecah[0];
}

// Ambil data pejabat
$kades  = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT * FROM pejabat WHERE jabatan='kepala' LIMIT 1"
));

$sekdes = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT * FROM pejabat WHERE jabatan='sekretaris' LIMIT 1"
));

// Cegah error kalau kosong
$namaKades  = $kades['nama']  ?? '-';
$nipKades   = $kades['nip']   ?? '';
$namaSekdes = $sekdes['nama'] ?? '-';
$nipSekdes  = $sekdes['nip']  ?? '';


// ================================
// AMBIL PEJABAT AKTIF
// ================================
function getPejabat($koneksi, $jabatan){
    $q = mysqli_query($koneksi,"
        SELECT * FROM pejabat
        WHERE jabatan='$jabatan' AND status='1'
        ORDER BY urutan ASC
        LIMIT 1
    ");
    return mysqli_fetch_assoc($q);
}

$kades  = getPejabat($koneksi,'kepala');
$sekdes = getPejabat($koneksi,'sekdes');
$camat  = getPejabat($koneksi,'camat');


/* ============================================
   TANGGAL
============================================ */
$tglMulai = date('Y-m-d');
$tglAkhir = date('Y-m-d', strtotime('+1 month'));

/* ============================================
   TOTAL SURAT
============================================ */
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM sk_dom_bade");
$totalSurat = mysqli_fetch_assoc($qTotal);

/* ============================================
   DAFTAR SURAT TERBIT
============================================ */
$qSurat = mysqli_query($koneksi, "
    SELECT s.id, s.nomor_surat, s.tgl_surat, p.NAMA_LGKP
    FROM sk_dom_bade s
    JOIN penduduk p ON s.nik = p.NIK
    ORDER BY s.id DESC
");

/* ============================================
   VARIABEL
============================================ */
$ttdCamat = false;
$dataPenduduk = null;
$modePreview = false;
$modePreview = isset($_GET['preview']) ? true : false;
$modeCetak = false;
$hasilCari = null;
$jabatan = '';
$namaPejabat = '';
$nik = '';
$nomor = '';
$keperluan = '';
$pejabat = '';
$tgl = date('Y-m-d');

/* ============================================
   CETAK ULANG
============================================ */
if (isset($_GET['cetak_ulang'])) {

    $modePreview = false;
    $modeCetak   = true;
    $id = intval($_GET['cetak_ulang']);

    $q = mysqli_query($koneksi,"
        SELECT s.*, p.*
        FROM sk_dom_bade s
        JOIN penduduk p ON s.nik = p.NIK
        WHERE s.id='$id'
    ");

    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {

        $nomor     = $dataPenduduk['nomor_surat'] ?? '';
        $keperluan = $dataPenduduk['keperluan'] ?? '';
        $pejabat   = $dataPenduduk['pejabat'] ?? '';
        $ttdCamat  = $dataPenduduk['ttd_camat'];

        $pejabatDipilih = $_POST['pejabat'] 
        ?? $dataPenduduk['pejabat'] 
        ?? '';

        if ($modeCetak) {
        if ($pejabat == 'kepala' && !empty($kades)) {
        $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $kades['nama'];
        } elseif ($pejabat == 'sekdes' && !empty($sekdes)) {
        $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $sekdes['nama'];
        } else {
        $jabatanSurat = $jabatan ?? '';
        $namaPejabat  = $namaPejabat ?? '';
    }
}
    }
}


/* ============================================
   CARI PENDUDUK
============================================ */
if (isset($_GET['cari']) && !empty($_GET['keyword'])) {

    $keyword=mysqli_real_escape_string($koneksi,$_GET['keyword']);

    $hasilCari=mysqli_query($koneksi,"
        SELECT * FROM penduduk
        WHERE NIK LIKE '%$keyword%' 
        OR NAMA_LGKP LIKE '%$keyword%'
    ");

    if (mysqli_num_rows($hasilCari)==1) {
        $dataPenduduk=mysqli_fetch_assoc($hasilCari);
        $modePreview  = true;   // ✅ supaya biodata muncul
        $modeCetak   = false;
    }
}

/* pilih dari list */
if (isset($_GET['nik'])) {

    $nik=mysqli_real_escape_string($koneksi,$_GET['nik']);
    $q=mysqli_query($koneksi,"SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk=mysqli_fetch_assoc($q);
    if ($dataPenduduk) {
    }
}



/* ============================================
   PREVIEW SURAT DARI FORM
============================================ */
if (isset($_POST['preview']) && !empty($_POST['nik'])) {

    $modePreview = true;
    $modeCetak   = true;

    $nik       = mysqli_real_escape_string($koneksi,$_POST['nik']);
    $nomor     = mysqli_real_escape_string($koneksi,$_POST['nomor_surat']);
    $keperluan = mysqli_real_escape_string($koneksi,$_POST['keperluan']);
    $pejabat   = $_POST['pejabat'];
    $ttdCamat  = isset($_POST['ttd_camat']) ? 1 : 0;
    $tgl       = date('Y-m-d');

    // simpan ke database
    mysqli_query($koneksi,"
        INSERT INTO sk_dom_bade
        (nik, nomor_surat, keperluan, pejabat, tgl_surat, ttd_camat)
        VALUES ('$nik','$nomor','$keperluan','$pejabat','$tgl','$ttdCamat')
    ");

    // ✅ AMBIL LAGI DATA PENDUDUK
    $q = mysqli_query($koneksi,"SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);

    // ✅ tentukan pejabat
    $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = "";

    if ($pejabat == 'kepala' && !empty($kades)) {

    $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $kades['nama'];

    }
    elseif ($pejabat == 'sekdes' && !empty($sekdes)) {

    $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $sekdes['nama'];

}
}

?>




<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Keterangan Domisili <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></title>
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
<style>
@media print {
    form, .no-print {
        display: none;
    }
}
</style>
</head>
<body class="bg-gray-100 p-4">

<button onclick="window.location.href='../surat.php'"
        class="bg-gray-600 text-white px-4 py-2 rounded">
    ⬅️ Kembali
</button>

<div class="max-w-3xl mx-auto bg-white p-6 shadow ">

<h1 class="text-xl font-bold text-center mb-4">📄 SURAT KETERANGAN DOMISILI <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h1>

<!-- Total Surat -->
<div class="bg-blue-100 text-blue-800 p-3 rounded mb-4 text-sm">
📄 Total Surat Terbit: <b><?= $totalSurat['total'] ?></b>
</div>

<!-- Daftar Surat Terbit scrollable -->
<h2 class="text-lg font-semibold mb-2 mt-2">Daftar Surat Terbit</h2>
<div class="border rounded " style="max-height:200px; overflow-y:auto;">
<table class="w-full text-sm border-collapse border border-gray-300">
<tr class="bg-gray-200 sticky top-0">
<th class="border-collapse border border-gray-300 p-2">No</th>
<th class="border-collapse border border-gray-300 p-2">Nama</th>
<th class="border-collapse border border-gray-300 p-2">Nomor Surat</th>
<th class="border-collapse border border-gray-300 p-2">Tanggal</th>
<th class="border-collapse border border-gray-300 p-2">Aksi</th>
</tr>

<?php $no=1; while($r=mysqli_fetch_assoc($qSurat)) { ?>
<tr>
<td class="border p-2"><?= $no++ ?></td>
<td class="border p-2"><?= htmlspecialchars($r['NAMA_LGKP']) ?></td>
<td class="border p-2"><?= htmlspecialchars($r['nomor_surat']) ?></td>
<td class="border p-2"><?= date('d-m-Y', strtotime($r['tgl_surat'])) ?></td>
<td class="border p-2 text-center">
<a href="?cetak_ulang=<?= $r['id'] ?>" class="text-blue-600">Cetak</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<!-- FORM PENCARIAN -->

<form method="GET" class="mb-4 mt-6">
    <label class="block text-sm font-medium mb-1">Cari NIK / Nama Penduduk <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></label>
    <div class="flex gap-2">
        <input type="text" name="keyword" required class="border p-2 rounded w-full" placeholder="Masukkan NIK atau Nama">
        <button name="cari" class="bg-blue-600 text-white px-4 rounded">Cari</button>
    </div>
</form>

<?php if ($hasilCari && mysqli_num_rows($hasilCari) > 1) : ?>
<div class="border p-4 mb-4 bg-white">
    <h2 class="font-semibold mb-2">Pilih Data Penduduk</h2>
    <table class="w-full text-sm border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="border p-2">No</th>
                <th class="border p-2">NIK</th>
                <th class="border p-2">Nama</th>
                <th class="border p-2">Alamat</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no=1; while($p = mysqli_fetch_assoc($hasilCari)) : ?>
            <tr>
                <td class="border p-2"><?= $no++ ?></td>
                <td class="border p-2"><?= htmlspecialchars($p['NIK']) ?></td>
                <td class="border p-2"><?= htmlspecialchars($p['NAMA_LGKP']) ?></td>
                <td class="border p-2"><?= htmlspecialchars($p['DUSUN']) ?> RT <?= $p['RT'] ?> RW <?= $p['RW'] ?></td>
                <td class="border p-2 text-center">
                    <a href="?nik=<?= $p['NIK'] ?>" class="bg-blue-600 text-white px-3 py-1 rounded text-xs">Pilih</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (!$modeCetak && $dataPenduduk) : ?>
<?php $tglSurat = $dataPenduduk['tgl_surat'] ?? $tgl ?? date('Y-m-d');?>

<div class="border p-4 mb-4 bg-white">
    <h2 class="font-semibold mb-2">Biodata Penduduk <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h2>
    <table class="text-sm w-full">
        <tr><td width="30%">Nama Lengkap</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></td></tr>
        <tr><td>Tempat / Tanggal Lahir</td><td>: <?= htmlspecialchars($dataPenduduk['TMPT_LAHIR']) ?>, <?= tglIndonesia($dataPenduduk['TGL_LAHIR']) ?></td></tr>
        <tr><td>Jenis Kelamin</td><td>:<?= ($dataPenduduk['JENIS_KELAMIN'] == 'LK') ? 'LAKI-LAKI' : 'PEREMPUAN'; ?></td></tr>
        <tr><td>Agama</td><td>: <?= htmlspecialchars($dataPenduduk['AGAMA']) ?></td></tr>
        <tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($dataPenduduk['PEKERJAAN']) ?></td></tr>
        <tr><td>Alamat</td><td>: <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> RT <?= htmlspecialchars($dataPenduduk['RT']) ?> RW <?= htmlspecialchars($dataPenduduk['RW']) ?>, <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, Klego, Boyolali</td></tr>
        <tr><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
        <tr><td>No. KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
    </table>
</div>

<form method="POST" class="space-y-3">
    <input type="hidden" name="nik" value="<?= $dataPenduduk['NIK'] ?>">
    
    <label class="block text-sm font-medium mb-1">Nomor Surat</label>
    <input type="text" name="nomor_surat" required class="border p-2 rounded w-full" placeholder="Masukkan nomor surat">
    
    <label class="block text-sm font-medium mb-1">Keperluan Surat</label>
    <textarea name="keperluan" required class="border p-2 rounded w-full" placeholder="Jelaskan keperluan surat"></textarea>
    
    <label class="block text-sm font-medium mb-1">Pilih Pejabat Penandatangan</label>
        <select name="pejabat" required class="border p-2 rounded w-full">
        <option value="">-- Pilih Pejabat --</option>
        <?php if(!empty($kades)) : ?>
        <option value="kepala">Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
        <?php endif; ?>

        <?php if(!empty($sekdes)) : ?>
        <option value="sekdes">Sekretaris <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
        <?php endif; ?>
        </select>
    <div class="mt-2">
    <label class="inline-flex items-center text-sm">
        <input type="checkbox" name="ttd_camat" value="1" class="mr-2">
        Ttd Camat
    </label>
</div>
<button type="submit" name="preview" class="bg-green-600 text-white px-6 py-2 rounded">
📄 Preview Surat
</button>












</form>
<?php endif; ?>

<?php if ($modeCetak && $dataPenduduk) : ?>
<?php
$tglSurat = date('Y-m-d');

if (isset($dataPenduduk['tgl_surat']) && !empty($dataPenduduk['tgl_surat'])) {
    $tglSurat = $dataPenduduk['tgl_surat'];
} elseif (!empty($tgl)) {
    $tglSurat = $tgl;
}
?>

<div class="mt-8 border bg-white p-4" id="printArea" contenteditable="true" style="font-family: 'Times New Roman', Times, serif; outline: none;">

    <!-- KOP SURAT -->
    <table width="100%" class="border-b-4 border-black mb-4">
    <tr>
        <td width="15%" class="text-center">
            <img src="../../assets/img/<?= htmlspecialchars($APP_PROFIL['logo']) ?>" width="80" alt="Logo <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>">
        </td>
        <td class="text-center">
            <p class="text-sm font-semibold">PEMERINTAH KABUPATEN <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></p>
            <p class="text-lg font-bold">KECAMATAN <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?></p>
            <p class="text-lg font-bold">DESA <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa_clean'])) ?></p>
            <p class="text-xs"><?= htmlspecialchars($APP_PROFIL['alamat']) ?>, <?= htmlspecialchars($APP_PROFIL['nama_desa_clean']) ?>, <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?> Kode Pos. <?= htmlspecialchars($APP_PROFIL['kode_pos']) ?></p>
        </td>
    </tr>
    </table>

    <h3 class="text-center font-bold underline mb-2">SURAT KETERANGAN DOMISILI TEMPAT TINGGAL</h3>
    <p class="text-center text-sm mb-6">Nomor : <?= htmlspecialchars($nomor) ?></p>

    <p class="indent-8 text-sm text-justify mb-4">
        Yang Bertanda tangan dibawah ini, Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?> Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?>, dengan ini menerangkan bahwa warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>:
    </p>

    <!-- Biodata -->
    <table class="text-sm w-full mb-4">
        <tr><td width="5%">1.</td><td width="30%">Nama Lengkap</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></td></tr>
        <tr><td>2.</td><td>Jenis Kelamin</td><td>: <?= ($dataPenduduk['JENIS_KELAMIN'] == 'LK') ? 'LAKI-LAKI' : 'PEREMPUAN'; ?></td></tr>
        <tr><td>3.</td><td>Bin/Binti</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_AYAH']) ?></td></tr>
        <tr><td>4.</td><td>Tempat / Tanggal Lahir</td><td>: <?= htmlspecialchars($dataPenduduk['TMPT_LAHIR']) ?>/ <?= strtoupper(tglIndonesia($dataPenduduk['TGL_LAHIR'])) ?></td></tr>
        <tr><td>5.</td><td>Agama</td><td>: <?= htmlspecialchars($dataPenduduk['AGAMA']) ?></td></tr>
        <tr><td>6.</td><td>Warganegara</td><td>: INDONESIA</td></tr>
        <tr><td>7.</td><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
        <tr><td>8.</td><td>No. KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
        <tr><td>9.</td><td>Pekerjaan</td><td>: <?= htmlspecialchars($dataPenduduk['PEKERJAAN']) ?></td></tr>
        <tr><td>10.</td><td>Alamat</td><td>: <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> RT <?= htmlspecialchars($dataPenduduk['RT']) ?> RW <?= htmlspecialchars($dataPenduduk['RW']) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></td></tr>
    </table>
    <p class="indent-8 text-sm text-justify mb-6">Berdasarkan Surat Keterangan dari Ketua RW <?= htmlspecialchars($dataPenduduk['RW']) ?> <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> Tanggal <?= tglIndonesia($tglSurat) ?> , bahwa yang bersangkutan benar warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?> Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?> Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?> yang beralamat pada alamat tersebut diatas, surat ini dibuat untuk keperluan <?= nl2br(htmlspecialchars($keperluan)) ?> </p>

    <p class="text-sm text-justify mb-6">
        Demikian surat keterangan domisili ini dibuat dengan sebenarnya, agar dapat dipergunakan sebagaimana mestinya.
    </p>


    <!-- Tanda Tangan -->
    <table width="100%" style="margin-top:30px;">
    <tr>

    <!-- KIRI -->
    <td width="50%" valign="top">

    <table>
    <tr>
    <td style="text-align:center;" width="150">No. Reg</td>
    <td width="10">:</td>
    <td>_________________</td>
    </tr>
    <tr>
    <td style="text-align:center;">Tanggal</td>
    <td>:</td>
    <td>________________</td>
    </tr>
    </table>

    <p style="text-align:center;">
    Mengetahui<?php if ($ttdCamat) echo ',<br>Camat Klego'; ?>
    </p>
        <br><br> <br>
        <?php if ($ttdCamat && !empty($camat)) { ?>
        <p style="text-align:center;">
        <strong><?= htmlspecialchars($camat['nama'] ?? '') ?></strong><br>
        <?= htmlspecialchars($camat['gol'] ?? '') ?><br>
        NIP. <?= htmlspecialchars($camat['nip'] ?? '') ?>
        </p>
        <?php } ?>

    </td>

    <!-- KANAN -->
    <td width="50%" valign="top" align="right">

    

    <br><br><p style="text-align:center;">
    <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, <?= tglIndonesia($tglSurat) ?>
    </p>

    <p style="text-align:center;">
    <?= $jabatanSurat ?>
    </p>

    <br><br><br>

    <p style="text-align:center;">
    <strong><?= htmlspecialchars($namaPejabat ?? '') ?></strong>
    </p>

    </td>
    </tr>
    </table>
    </div>

<div class="mt-4 text-center">
    <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded">🖨️ Cetak Surat</button>
</div>

<?php endif; ?>

</div>

</body>
</html>
