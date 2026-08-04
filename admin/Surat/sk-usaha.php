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

/* =====================================================
   TANGGAL BERLAKU OTOMATIS
===================================================== */
$tglMulai = date('Y-m-d');
$tglAkhir = date('Y-m-d', strtotime('+1 month'));

/* =====================================================
   HITUNG TOTAL SURAT
===================================================== */
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) total FROM sku");
$totalSurat = mysqli_fetch_assoc($qTotal);

/* =====================================================
   INISIALISASI VARIABEL
===================================================== */
$dataPenduduk = null;
$modePreview = false;
$hasilCari = null;
$jabatanSurat = '';
$namaPejabat  = '';
$nik = '';
$nomor = '';
$keperluan = '';
$ket_lain = '';
$pejabat = '';
$tgl = date('Y-m-d');



// Ambil data untuk cetak ulang
if (isset($_GET['cetak_ulang'])) {
    $id = intval($_GET['cetak_ulang']);
    $q = mysqli_query($koneksi,"
        SELECT s.*, p.*
        FROM sku s
        JOIN penduduk p ON s.nik=p.NIK
        WHERE s.id='$id'
    ");
    $dataPenduduk = mysqli_fetch_assoc($q);
    $pejabatDipilih = $dataPenduduk['pejabat'] ?? '';

    if ($pejabatDipilih == 'kepala') {
    $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $kades['nama'] ?? '';
    }
    elseif ($pejabatDipilih == 'sekdes') {
    $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $sekdes['nama'] ?? '';
    }


    if ($dataPenduduk) {
        $_POST['nomor_surat'] = $dataPenduduk['nomor_surat'];
        $_POST['keperluan'] = $dataPenduduk['keperluan'];
        $_POST['nama_usaha'] = $dataPenduduk['nama_usaha'];
        $_POST['pejabat'] = $dataPenduduk['pejabat'];
        $_POST['preview'] = true;
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

// 3. Klik Preview
if (isset($_POST['preview']) && !empty($_POST['nik'])) {

    $modePreview = true;

    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);

    $q = mysqli_query($koneksi,
        "SELECT * FROM penduduk WHERE NIK='$nik'"
    );

    $dataPenduduk = mysqli_fetch_assoc($q);

    $pejabatDipilih = $_POST['pejabat'] ?? '';

    if ($pejabatDipilih == 'kepala') {
        $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $kades['nama'] ?? '';
    } elseif ($pejabatDipilih == 'sekdes') {
        $jabatanSurat = "a.n. Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $sekdes['nama'] ?? '';
    }
}
// simpan dan cetak
if (isset($_POST['simpan_cetak'])) {

    $nik         = mysqli_real_escape_string($koneksi,$_POST['nik']);
    $nomor       = mysqli_real_escape_string($koneksi,$_POST['nomor_surat']);
    $nama_usaha  = mysqli_real_escape_string($koneksi,$_POST['nama_usaha']);
    $keperluan   = mysqli_real_escape_string($koneksi,$_POST['keperluan']);
    $pejabat     = mysqli_real_escape_string($koneksi,$_POST['pejabat']);
    $tgl         = date('Y-m-d');

    mysqli_query($koneksi,"
        INSERT INTO sku
        (nik, nomor_surat, nama_usaha, keperluan, pejabat, tgl_surat)
        VALUES
        ('$nik','$nomor','$nama_usaha','$keperluan','$pejabat','$tgl')
    ");

    echo "<script>
        window.print();
        window.location='sk-usaha.php';
    </script>";
    exit;
}

// 4. Cetak ulang langsung otomatis
if (isset($_GET['cetak_ulang'])) {
    $id = intval($_GET['cetak_ulang']);
    $q = mysqli_query($koneksi,"
        SELECT s.*, p.*
        FROM sku s
        JOIN penduduk p ON s.nik=p.NIK
        WHERE s.id='$id'
    ");
    $dataPenduduk = mysqli_fetch_assoc($q);
    $modePreview = true;

    if ($dataPenduduk) {
        $_POST['nomor_surat'] = $dataPenduduk['nomor_surat'];
        $_POST['keperluan'] = $dataPenduduk['keperluan'];
        $_POST['nama_usaha'] = $dataPenduduk['nama_usaha'];
        $_POST['pejabat'] = $dataPenduduk['pejabat'];
        $_POST['preview'] = true;


    }
}

// Ambil daftar surat untuk tabel
$qSurat = mysqli_query($koneksi,"
    SELECT s.id, s.nomor_surat, s.tgl_surat, p.NAMA_LGKP
    FROM sku s
    JOIN penduduk p ON s.nik=p.NIK
    ORDER BY s.id DESC
");
?>








<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Keterangan Usaha</title>
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
    .print-hide {
    display: none !important;
}
}




</style>
</head>
<body class="bg-gray-100 p-4">
<div class="max-w-3xl mx-auto bg-white p-6 shadow ">

<h1 class="text-xl font-bold text-center mb-4">📄 SURAT KETERANGAN USAHA</h1>

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


<?php if ($dataPenduduk && !$modePreview) { ?>
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

<form method="POST" class="space-y-3">
<input type="hidden" name="nik" value="<?= $dataPenduduk['NIK'] ?>">
<label class="block text-sm font-medium mb-1">
        Nomor Surat
    </label>
<input type="text" name="nomor_surat" required class="border p-2 rounded w-full" placeholder="">
<label class="block text-sm font-medium mb-1">
        Nama Usaha
    </label>
<textarea name="nama_usaha" required class="border p-2 rounded w-full" placeholder=""></textarea>
<label class="block text-sm font-medium mb-1">
        Keperluan
    </label>
<textarea name="keperluan" class="border p-2 rounded w-full" placeholder=""></textarea>
<select name="pejabat" required class="border p-2 rounded w-full">
<option value="">-- Pilih Pejabat --</option>
<option value="kepala">Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
<option value="sekdes">Sekretaris <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
</select>


<button name="preview" class="bg-green-600 text-white px-6 py-2 rounded">Preview Surat</button>

</form>
<?php } ?>

<?php if ($modePreview && $dataPenduduk) {
    $ttdCamat = isset($_POST['ttd_camat']);
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

<h3 class="text-center font-bold underline mb-2">SURAT KETERANGAN USAHA</h3>
<p class="text-center text-sm mb-6">Nomor : <?= htmlspecialchars($_POST['nomor_surat']) ?></p>
<p class="indent-8 text-sm text-justify mb-4">
Yang bertanda tangan di bawah ini, Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?>, Provinsi Jawa Tengah, dengan ini menerangkan bahwa warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>:
</p>

<!-- Biodata -->
<table class="text-sm w-full mb-4">
<tr><td width="5%">1.</td><td width="30%">Nama Lengkap</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></td></tr>
<tr><td>2.</td><td>Tempat / Tanggal Lahir</td><td>: <?= htmlspecialchars($dataPenduduk['TMPT_LAHIR']) ?>,<?= strtoupper(tglIndonesia($dataPenduduk['TGL_LAHIR'])) ?></td></tr>
<tr><td>3.</td><td>Warga Negara</td><td>: INDONESIA</td></tr>
<tr><td>4.</td><td>Agama</td><td>: <?= htmlspecialchars($dataPenduduk['AGAMA']) ?></td></tr>
<tr><td>5.</td><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
<tr><td>6.</td><td>No. KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
<tr><td>7.</td><td>Pekerjaan</td><td>: <?= htmlspecialchars($dataPenduduk['PEKERJAAN']) ?></td></tr>
<tr><td>8.</td><td>Alamat</td><td>: <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> RT <?= htmlspecialchars($dataPenduduk['RT']) ?> RW <?= htmlspecialchars($dataPenduduk['RW']) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kecamatan'])) ?>, <?= strtoupper(htmlspecialchars($APP_PROFIL['kabupaten'])) ?></td></tr>
</table>
<p class="indent-8 text-sm text-justify mb-6">Berdasarkan Surat Keterangan dari Ketua RT <?= htmlspecialchars($dataPenduduk['RT']) ?> <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> Tanggal <?= tglIndonesia($tanggalSurat) ?>, bahwa yang bersangkutan betul warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?> dan menurut pengakuan yang bersangkutan Memiliki Usaha <?= htmlspecialchars($_POST['nama_usaha'] ?? $dataPenduduk['nama_usaha'] ?? '') ?></p>


<p class="indent-8 text-sm text-justify mb-6">Surat Keterangan ini diperlukan untuk <?= htmlspecialchars($_POST['keperluan'] ?? $dataPenduduk['keperluan'] ?? '') ?></p>


<p class="indent-8 text-sm text-justify mb-6">Demikian Surat Keterangan ini kami buat atas permintaan yang bersangkutan dan dapat digunakan sebagaimana mestinya.</p>



    <?php
    $pejabatDipilih = $_POST['pejabat']
        ?? $dataPenduduk['pejabat']
        ?? '';

    $namaTtd = '';

    if ($pejabatDipilih == 'kepala') {
        $jabatanSurat = 'Kepala ' . $APP_PROFIL['nama_desa'];
        $namaTtd = $kades['nama'] ?? '';
    }
    elseif ($pejabatDipilih == 'sekdes') {
    $jabatanSurat = 'a.n. Kepala ' . $APP_PROFIL['nama_desa'] . '<br>Sekretaris ' . $APP_PROFIL['nama_desa'];
    $namaTtd = $sekdes['nama'] ?? '';
    }

    
    ?>

    <table width="100%" style="margin-top:30px;">
        <tr>
            <td width="55%"></td>

            <td width="45%" align="center" valign="top">
                <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, <?= tglIndonesia($tanggalSurat) ?><br>

                <?= $jabatanSurat ?>

                <br><br><br><br>

                <strong><u><?= $namaTtd ?></u></strong>
            </td>
        </tr>
    </table>





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
<?php } ?>
</div>

</body>
<!-- Tombol Cetak -->

    </button>
    <?php if ($modePreview && $dataPenduduk) { ?>
<div class="mt-4 text-center print-hide">
    <button onclick="window.print()" 
            class="bg-blue-600 text-white px-6 py-2 rounded">
        🖨️ Cetak Surat
    </button>
</div>
<?php } ?>



</html>
