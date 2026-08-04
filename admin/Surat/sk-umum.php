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
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) total FROM surat_umum");
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
        FROM surat_umum s
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
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $q = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {
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

        $nomor     = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
        $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
        $ket_lain  = mysqli_real_escape_string($koneksi, $_POST['keterangan_lain']);
        $pejabat   = $_POST['pejabat'];
        $tgl       = date('Y-m-d');
        $modeCetakUlang = false;

        mysqli_query($koneksi, "
            INSERT INTO surat_umum
            (nik, nomor_surat, keperluan, keterangan_lain, pejabat, tgl_surat)
            VALUES ('$nik','$nomor','$keperluan','$ket_lain','$pejabat','$tgl')
        ");
    }
}

// 4. Cetak ulang langsung otomatis
if (isset($_GET['cetak_ulang'])) {
    $modeCetakUlang = true;
    $id = intval($_GET['cetak_ulang']);
    $q = mysqli_query($koneksi,"
        SELECT s.*, p.*
        FROM surat_umum s
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

// Ambil daftar surat untuk tabel
$qSurat = mysqli_query($koneksi,"
    SELECT s.id, s.nomor_surat, s.tgl_surat, p.NAMA_LGKP
    FROM surat_umum s
    JOIN penduduk p ON s.nik=p.NIK
    ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Keterangan Umum</title>
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

<h1 class="text-xl font-bold text-center mb-4">📄 SURAT KETERANGAN UMUM</h1>

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
<tr><td>Tempat / Tanggal Lahir</td><td>: <?= htmlspecialchars($dataPenduduk['TMPT_LAHIR']) ?>, <?= tglIndonesia($dataPenduduk['TGL_LAHIR']) ?></td></tr>
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
onclick="isiKet('Nama tersebut di atas benar-benar warga Desa kami,berkelakuan dan beradat istiadat baik.')"
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
<button name="preview" class="bg-green-600 text-white px-6 py-2 rounded">Preview Surat</button>
</form>
<?php endif; ?>
<?php endif; ?>


<?php
if (isset($_POST['preview']) && $dataPenduduk) {
    $tanggalSurat = $dataPenduduk['tgl_surat'] ?? date('Y-m-d');
?>
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

<h3 class="text-center font-bold underline mb-2">SURAT KETERANGAN</h3>
<p class="text-center text-sm mb-6">Nomor : <?= htmlspecialchars($_POST['nomor_surat']) ?></p>
<br>
<p class="indent-8 text-sm text-justify mb-4">
Yang bertanda tangan di bawah ini, kami <?= $jabatanSurat ?> Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?>, Provinsi Jawa Tengah, dengan ini menerangkan bahwa warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>:
</p>

<!-- Biodata -->
<table class="text-sm w-full mb-4">
<tr><td width="5%">1.</td><td width="30%">Nama Lengkap</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></td></tr>
<tr><td>2.</td><td>Tempat / Tanggal Lahir</td><td>: <?= htmlspecialchars($dataPenduduk['TMPT_LAHIR']) ?>/ <?= strtoupper (tglIndonesia($dataPenduduk['TGL_LAHIR'])) ?></td></tr>
<tr><td>3.</td><td>Warga Negara</td><td>: WNI</td></tr>
<tr><td>4.</td><td>Agama</td><td>: <?= htmlspecialchars($dataPenduduk['AGAMA']) ?></td></tr>
<tr><td>5.</td><td>Pekerjaan</td><td>: <?= htmlspecialchars($dataPenduduk['PEKERJAAN']) ?></td></tr>
<tr><td>6.</td><td>Alamat</td><td>: <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> RT <?= htmlspecialchars($dataPenduduk['RT']) ?> RW <?= htmlspecialchars($dataPenduduk['RW']) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></td></tr>
<tr><td>7.</td><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
<tr><td>8.</td><td>No. KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
<tr><td>9.</td><td>Keperluan</td><td>: <?= nl2br(strtoupper(htmlspecialchars($_POST['keperluan']))) ?></td></tr>
<tr><td>10.</td><td>Masa Berlaku</td><td>: <?= strtoupper(tglIndonesia($tglMulai)) ?> s/d <?= strtoupper(tglIndonesia($tglAkhir)) ?></td></tr>
<?php if(!empty($_POST['keterangan_lain'])): ?>
<tr><td>11.</td><td>Keterangan Lain</td><td>: <?= nl2br(strtoupper(htmlspecialchars($_POST['keterangan_lain']))) ?></td></tr>
<?php endif; ?>
</table>

<p class="text-sm text-justify mb-6">Demikian untuk menjadikan maklum bagi yang berkepentingan.</p>

<!-- Tanda Tangan -->
<table width="100%" class="mt-4 text-sm">
<tr><td></td><td class="text-center"><?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, <?= tglIndonesia($tanggalSurat) ?></td></tr>
<tr>
<td class="text-center">Pemohon<br><br><br><br><br><b><?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></b></td>
<td class="text-center"><?= $jabatanSurat ?><br><br><br><br><br><b><?= $namaPejabat ?></b></td>
</tr>
</table>
</div>



<!-- Tombol Cetak -->
<div class="mt-4 text-center">
    <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded">
    🖨️ Cetak Surat
</button>
</div>

<script>
function printDiv(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>


<?php
}
?>
</div>

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
