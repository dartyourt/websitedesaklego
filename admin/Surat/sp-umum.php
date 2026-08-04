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
// ===============================
// AMBIL DATA PEJABAT AKTIF
// ===============================
$pejabatDB = [];
$qPejabat = mysqli_query($koneksi,"SELECT * FROM pejabat WHERE status=1");

while($row = mysqli_fetch_assoc($qPejabat)){
    $pejabatDB[$row['jabatan']] = $row;
}
/* =====================================================
   TANGGAL BERLAKU OTOMATIS
===================================================== */
$tglMulai = date('Y-m-d');
$tglAkhir = date('Y-m-d', strtotime('+1 month'));

/* =====================================================
   HITUNG TOTAL SURAT
===================================================== */
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) total FROM surat_pengantar");
$totalSurat = mysqli_fetch_assoc($qTotal);

/* =====================================================
   INISIALISASI VARIABEL
===================================================== */
$dataPenduduk = null;
$hasilCari = null;
$jabatan = '';
$namaPejabat = '';
$nik = '';
$nomor = '';
$keperluan = '';
$ket_lain = '';
$pejabat = '';
$tgl = date('Y-m-d');
$modePreview = isset($_POST['preview']) || isset($_GET['cetak_ulang']);
$modeCetakUlang = isset($_GET['cetak_ulang']) ? true : false;

// Ambil data untuk cetak ulang
if (isset($_GET['cetak_ulang'])) {
    $id = intval($_GET['cetak_ulang']);
    $q = mysqli_query($koneksi,"
        SELECT s.*, p.*
        FROM surat_pengantar s
        JOIN penduduk p ON s.nik=p.NIK
        WHERE s.id='$id'
    ");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {
        $_POST['nomor_surat'] = $dataPenduduk['nomor_surat'];
        $_POST['keperluan'] = $dataPenduduk['keperluan'];
        $_POST['keterangan_lain'] = $dataPenduduk['keterangan_lain'];
        $_POST['pejabat'] = $dataPenduduk['pejabat'];
        $_POST['ttd_camat'] = $dataPenduduk['ttd_camat'];
        $ttdCamat = $dataPenduduk['ttd_camat'];
        $_POST['preview'] = true;

    $jabatanSurat = '';
    $namaPejabat  = '';
    $pejabat = $_POST['pejabat'] ?? $dataPenduduk['pejabat'] ?? '';

    if ($pejabat == 'kepala' && isset($pejabatDB['kepala'])) {

    $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $pejabatDB['kepala']['nama'];

    }
    elseif ($pejabat == 'sekdes' && isset($pejabatDB['sekdes'])) {

    $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $pejabatDB['sekdes']['nama'];

    }

}
}

/* =====================================================
   AMBIL DATA PENDUDUK
===================================================== */
// 1. Hasil pencarian
if (isset($_GET['cari']) && !empty($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    $hasilCari = mysqli_query($koneksi, "
        SELECT * FROM penduduk
        WHERE NIK LIKE '%$keyword%'
        OR NAMA_LGKP LIKE '%$keyword%'
    ");

    if (mysqli_num_rows($hasilCari) == 1) {
        $dataPenduduk = mysqli_fetch_assoc($hasilCari);
    }

}

// 2. Klik "Pilih" dari tabel hasil >1
if (isset($_GET['nik'])) {
    $nik = mysqli_real_escape_string($koneksi, $_GET['nik']);
    $q = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);
}

// 3. Klik Preview (POST)
if (isset($_POST['preview']) && !empty($_POST['nik'])) {

    $nik = mysqli_real_escape_string($koneksi,$_POST['nik']);
    $q = mysqli_query($koneksi,"SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if($dataPenduduk){

        $jabatanSurat = '';
        $namaPejabat = '';

        $pejabat = $_POST['pejabat'];

        if($pejabat=="kepala"){
            $jabatanSurat="Kepala " . $APP_PROFIL['nama_desa'];
            $namaPejabat= isset($pejabatDB['kepala']) ? $pejabatDB['kepala']['nama'] : '-';
        }else{
            $jabatanSurat="a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
            $namaPejabat= isset($pejabatDB['sekdes']) ? $pejabatDB['sekdes']['nama'] : '-';
        }

        $ttdCamat = ($_POST['ttd_camat'] ?? 0)==1;
    }
}

/* =====================================================
   SIMPAN SAAT TOMBOL CETAK
===================================================== */

if (isset($_POST['simpan'])) {

    $nik        = mysqli_real_escape_string($koneksi,$_POST['nik']);
    $nomor      = mysqli_real_escape_string($koneksi,$_POST['nomor_surat']);
    $keperluan  = mysqli_real_escape_string($koneksi,$_POST['keperluan']);
    $ket_lain   = mysqli_real_escape_string($koneksi,$_POST['keterangan_lain']);
    $pejabat    = mysqli_real_escape_string($koneksi,$_POST['pejabat']);
    $ttdCamat   = ($_POST['ttd_camat'] ?? 0)==1;
    $tgl        = date('Y-m-d');
    $q = mysqli_query($koneksi,"SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);
    mysqli_query($koneksi,"
        INSERT INTO surat_pengantar
        (
            nik,
            nomor_surat,
            keperluan,
            keterangan_lain,
            pejabat,
            tgl_surat,
            ttd_camat
        )
        VALUES
        (
            '$nik',
            '$nomor',
            '$keperluan',
            '$ket_lain',
            '$pejabat',
            '$tgl',
            '$ttdCamat'
        )
    ");

echo "<script>
location.href='sp-umum.php';
</script>";
exit;
}

// 4. Cetak ulang langsung otomatis
if (isset($_GET['cetak_ulang'])) {
    $modeCetakUlang = true;
    $id = intval($_GET['cetak_ulang']);
    $q = mysqli_query($koneksi,"
        SELECT s.*, p.*
        FROM surat_pengantar s
        JOIN penduduk p ON s.nik=p.NIK
        WHERE s.id='$id'
    ");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {
        $_POST['nomor_surat'] = $dataPenduduk['nomor_surat'];
        $_POST['keperluan'] = $dataPenduduk['keperluan'];
        $_POST['keterangan_lain'] = $dataPenduduk['keterangan_lain'];
        $_POST['pejabat'] = $dataPenduduk['pejabat'];
        $_POST['preview'] = true;

        $jabatanSurat = '';
        $namaPejabat  = '';
        $pejabat = $dataPenduduk['pejabat'];

        if ($pejabat == 'kepala' && isset($pejabatDB['kepala'])) {
        $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
        $namaPejabat = $pejabatDB['kepala']['nama'];
        }
        elseif ($pejabat == 'sekdes' && isset($pejabatDB['sekdes'])) {
        $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
        $namaPejabat = $pejabatDB['sekdes']['nama'];
        }
        }
        }

// Ambil daftar surat untuk tabel
$qSurat = mysqli_query($koneksi,"
    SELECT s.id, s.nomor_surat, s.tgl_surat, p.NAMA_LGKP
    FROM surat_pengantar s
    JOIN penduduk p ON s.nik=p.NIK
    ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Pengantar Umum</title>
<button onclick="window.location.href='../surat.php'"
        class="bg-gray-600 text-white px-4 py-2 rounded">
    ⬅️ Kembali
</button>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@media print {
    @page {
        margin: 3mm 15mm 15mm 15mm; /* margin kertas kecil di semua sisi */
    }

    body, html {
        background: #fff !important; /* halaman putih */
    }
    body * {
        visibility: hidden; /* sembunyikan semua selain printArea */
    }
    #printArea, #printArea * {
        visibility: visible; /* tampilkan div surat */
    }
    #printArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: #fff !important; /* background putih */
        border: none !important;       /* hilangkan border */
        box-shadow: none !important;   /* hilangkan shadow */
        border-radius: 0 !important;   /* hilangkan rounded */
    }
}




</style>
</head>
<body class="bg-gray-100 p-4">
<div class="max-w-3xl mx-auto bg-white p-6 shadow ">

<h1 class="text-xl font-bold text-center mb-4">📄 SURAT PENGANTAR UMUM</h1>

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
    <label class="block text-sm font-medium mb-1">
        Cari NIK / Nama Penduduk
    </label>
    <div class="flex gap-2">
        <input type="text" name="keyword" required
               class="border p-2 rounded w-full">
        <button name="cari"
                class="bg-blue-600 text-white px-4 rounded">
            Cari
        </button>
    </div>
</form>

<?php
// Jika hasil pencarian lebih dari 1, tampilkan pilihan
if ($hasilCari && mysqli_num_rows($hasilCari) > 1) {
?>
<div class="border p-4 mb-4 bg-white">
    <h2 class="font-semibold mb-2">Pilih Data Penduduk</h2>

    <table class="w-full text-sm border border-gray-300">
        <tr class="bg-gray-200">
            <th class="border p-2">No</th>
            <th class="border p-2">NIK</th>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Alamat</th>
            <th class="border p-2">Aksi</th>
        </tr>

        <?php $no=1; while($p = mysqli_fetch_assoc($hasilCari)) { ?>
        <tr>
            <td class="border p-2"><?= $no++ ?></td>
            <td class="border p-2"><?= htmlspecialchars($p['NIK']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($p['NAMA_LGKP']) ?></td>
            <td class="border p-2">
                <?= htmlspecialchars($p['DUSUN']) ?> RT <?= $p['RT'] ?> RW <?= $p['RW'] ?>
            </td>
            <td class="border p-2 text-center">
                <a href="?nik=<?= $p['NIK'] ?>" 
                   class="bg-blue-600 text-white px-3 py-1 rounded text-xs">
                   Pilih
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php } ?>


<?php if ($dataPenduduk && !$modePreview) : ?>
<div class="border p-4 mb-4 bg-white">
<h2 class="font-semibold mb-2" >Biodata Penduduk</h2>
<table class="text-sm w-full" >
<tr><td width="30%">Nama Lengkap</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></td></tr>
<tr><td>Tempat / Tanggal Lahir</td><td>: <?= htmlspecialchars($dataPenduduk['TMPT_LAHIR']) ?>, <?= date('d-m-Y', strtotime($dataPenduduk['TGL_LAHIR'])) ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= $dataPenduduk['JENIS_KELAMIN'] ?></td></tr>
<tr><td>Agama</td><td>: <?= htmlspecialchars($dataPenduduk['AGAMA']) ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($dataPenduduk['PEKERJAAN']) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> RT <?= htmlspecialchars($dataPenduduk['RT']) ?> RW <?= htmlspecialchars($dataPenduduk['RW']) ?>, <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, Klego, Boyolali</td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
<tr><td>No. KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
</table>
</div>


<?php if (!isset($modePreview) || !$modePreview) : ?>
<form method="POST" class="space-y-3">
<input type="hidden" name="nik" value="<?= $dataPenduduk['NIK'] ?>">
<label class="block text-sm font-medium mb-1">
        Nomor Surat
    </label>
<input type="text" name="nomor_surat" required class="border p-2 rounded w-full" placeholder="">
<label class="block text-sm font-medium mb-1">
        Keperluan Surat
    </label>
<textarea name="keperluan" required class="border p-2 rounded w-full" placeholder=""></textarea>
<label class="block text-sm font-medium mb-1">
    Keterangan Lain
</label>

<div class="flex flex-wrap gap-2 mb-2">
<button type="button"
onclick="isiKet('Nama tersebut di atas benar-benar warga Desa kami, berkelakuan dan beradat istiadat baik.')"
class="bg-gray-200 px-2 py-1 rounded text-xs">
Berkelakuan Baik
</button>
</div>

<textarea name="keterangan_lain"
id="keterangan_lain"
class="border p-2 rounded w-full"
placeholder="Bisa pilih template atau tulis manual..."><?= isset($_POST['keterangan_lain']) ? htmlspecialchars($_POST['keterangan_lain']) : '' ?></textarea>
<select name="pejabat" required class="border p-2 rounded w-full">
<option value="">-- Pilih Pejabat --</option>
<option value="kepala">Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
<option value="sekdes">Sekretaris <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
</select>
<div class="mt-2">
    <label class="inline-flex items-center text-sm">
        <input type="checkbox" name="ttd_camat" value="1"
        <?= isset($_POST['ttd_camat']) ? 'checked' : '' ?>
        class="mr-2">
        Ttd Camat
    </label>
</div>
<button name="preview" class="bg-green-600 text-white px-6 py-2 rounded">Preview Surat</button>
</form>
<?php endif; ?>
<?php endif; ?>


<?php

?>
<form method="POST" id="formCetak">
<input type="hidden" name="simpan" value="1">

<input type="hidden" name="nik" value="<?= htmlspecialchars($dataPenduduk['NIK'] ?? '') ?>">

<input type="hidden" name="nomor_surat"
       value="<?= htmlspecialchars($_POST['nomor_surat'] ?? '') ?>">

<input type="hidden" name="keperluan"
       value="<?= htmlspecialchars($_POST['keperluan'] ?? '') ?>">

<input type="hidden" name="keterangan_lain"
       value="<?= htmlspecialchars($_POST['keterangan_lain'] ?? '') ?>">

<input type="hidden" name="pejabat"
       value="<?= htmlspecialchars($_POST['pejabat'] ?? '') ?>">

<input type="hidden" name="ttd_camat"
       value="<?= isset($_POST['ttd_camat']) ? 1 : 0 ?>">


<?php if ($modePreview || $modeCetakUlang) { ?>

<div class="mt-8 border bg-white p-4" id="printArea" contenteditable="true" style="font-family: 'Times New Roman', Times, serif; outline: none;">


<!-- KOP SURAT -->
<table width="100%" class="border-b-4 border-black mb-4">
<tr>
<td width="15%" class="text-center">
<img src="../../assets/img/<?= htmlspecialchars($APP_PROFIL['logo']) ?>" width="80" alt="Logo">
</td>
<td class="text-center">
<p style="line-height:1.2; font-size:14pt; font-weight:600;">PEMERINTAH KABUPATEN <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></p>
<p style="line-height:1.2; font-size:14pt; font-weight:600;">KECAMATAN <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?></p>
<p style="line-height:1.2; font-size:18pt; font-weight:bold;">DESA <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa_clean'])) ?></p>
<p style="margin-top:1px; font-size:10pt;"><?= htmlspecialchars($APP_PROFIL['alamat']) ?>, <?= htmlspecialchars($APP_PROFIL['nama_desa_clean']) ?>, <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?> Kode Pos. <?= htmlspecialchars($APP_PROFIL['kode_pos']) ?></p>
</td>
</tr>
</table>

<h3 class="text-center font-bold underline mb-2">SURAT PENGANTAR</h3>
<p class="text-center text-sm mb-6">Nomor : <?= htmlspecialchars($_POST['nomor_surat']) ?></p>
<br>
<p class="indent-8 text-sm text-justify mb-4">
Yang bertanda tangan di bawah ini, <?= $jabatanSurat ?>, Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?>, Provinsi Jawa Tengah, dengan ini menerangkan bahwa warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>:
</p>

<!-- Biodata -->
<table class="text-sm w-full mb-4">
<tr><td width="5%">1.</td><td width="30%">Nama Lengkap</td><td>: <?= strtoupper(htmlspecialchars($dataPenduduk['NAMA_LGKP'])) ?></td></tr>
<tr><td>2.</td><td>Tempat / Tanggal Lahir</td><td>: <?= strtoupper(htmlspecialchars($dataPenduduk['TMPT_LAHIR'])) ?>/ <?= strtoupper(tglIndonesia($dataPenduduk['TGL_LAHIR'])) ?></td></tr>
<tr><td>3.</td><td>Warga Negara</td><td>: INDONESIA</td></tr>
<tr><td>4.</td><td>Agama</td><td>: <?= strtoupper(htmlspecialchars($dataPenduduk['AGAMA'])) ?></td></tr>
<tr><td>5.</td><td>Pekerjaan</td><td>: <?= strtoupper(htmlspecialchars($dataPenduduk['PEKERJAAN'])) ?></td></tr>
<tr><td>6.</td><td>Alamat</td><td>: <?= strtoupper(htmlspecialchars($dataPenduduk['DUSUN'])) ?> RT <?= strtoupper(htmlspecialchars($dataPenduduk['RT'])) ?> RW <?= strtoupper(htmlspecialchars($dataPenduduk['RW'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></td></tr>
<tr><td>7.</td><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
<tr><td>8.</td><td>No. KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
<tr><td>9.</td><td>Keperluan</td><td>: <?= strtoupper(nl2br(htmlspecialchars($_POST['keperluan']))) ?></td></tr>
<tr><td>10.</td><td>Masa Berlaku</td><td>: <?= strtoupper(tglIndonesia($tglMulai)) ?> s/d <?= strtoupper(tglIndonesia($tglAkhir)) ?></td></tr>
<?php if(!empty($_POST['keterangan_lain'])): ?>
<tr><td>11.</td><td>Keterangan Lain</td><td>: <?= strtoupper(nl2br(htmlspecialchars($_POST['keterangan_lain']))) ?></td></tr>
<?php endif; ?>
</table>

<p class="text-sm text-justify mb-6">Demikian untuk menjadikan maklum bagi yang berkepentingan.</p>

<!-- Tanda Tangan -->
<?php { ?>

<table width="100%" class="mt-6 text-sm">
    <tr>
        <td width="33%" class="text-center"></td>
        <td width="34%" class="text-center"></td>
        <td width="33%" class="text-center">
            <?php
            $tanggalSurat = $modeCetakUlang
            ? $dataPenduduk['tgl_surat']
            : date('Y-m-d');
            ?>

            <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, <?= tglIndonesia($tanggalSurat) ?>
        </td>
    </tr>

    <tr>
        <td class="text-center">
            Pemohon
            <br><br><br><br><br>
            <b><?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></b>
        </td>

        <td class="text-center">
            <?php if($ttdCamat){ ?>
                Mengetahui<br>
                Camat Klego
                <br><br><br><br><br>
                <b><?= $pejabatDB['camat']['nama']; ?></b><br>
                NIP. <?= $pejabatDB['camat']['nip']; ?>
            <?php } ?>
        </td>

        <td class="text-center">
            <?= $jabatanSurat ?>
            <br><br><br><br><br>
            <b><?= $namaPejabat ?></b>
        </td>

    </tr>
</table>

<?php } ?>
</div>



<!-- Tombol Cetak -->
    <div class="mt-4 text-center">
    <button type="button"
            onclick="cetakSurat()"
            class="bg-blue-600 text-white px-6 py-2 rounded">
        🖨️ Cetak Surat
    </button>
</div>

</form>

<script>
function cetakSurat() {
    window.print();
}

window.onafterprint = function () {
    document.getElementById("formCetak").submit();
}

</script>


<?php

?>
</div>
<?php } ?>
<script>
function isiKet(teks){
    let area = document.getElementById("keterangan_lain");

    if(area.value !== ""){
        area.value += "\n" + teks;
    } else {
        area.value = teks;
    }
}
</script>

</body>
</html>
