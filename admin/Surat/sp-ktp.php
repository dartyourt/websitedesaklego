<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../config/database.php';

/* ===============================
   MODE
=================================*/
$modeTambah = true;
$modePreview = false;

if (isset($_POST['preview'])) {
    $modePreview = true;
    $modeCetak   = true;
}

/* ===============================
   AMBIL DATA PEJABAT AKTIF
=================================*/
// Ambil Kepala Desa
$qKades = mysqli_query($koneksi, "SELECT * FROM pejabat WHERE jabatan='kepala' AND status=1 LIMIT 1");
$kades = mysqli_fetch_assoc($qKades);

// Ambil Sekdes
$qSekdes = mysqli_query($koneksi, "SELECT * FROM pejabat WHERE jabatan='sekdes' AND status=1 LIMIT 1");
$sekdes = mysqli_fetch_assoc($qSekdes);



/* ===============================
   TOTAL SURAT
=================================*/
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) total FROM sp_ktp");
$totalSurat = mysqli_fetch_assoc($qTotal);

/* ===============================
   AMBIL DAFTAR SURAT
=================================*/
$qSurat = mysqli_query($koneksi, "
    SELECT s.id, s.tgl_surat, s.nik, s.nomor_surat, p.NAMA_LGKP
    FROM sp_ktp s
    LEFT JOIN penduduk p ON s.nik=p.NIK
    ORDER BY s.id DESC
");
/* ===============================
   VARIABEL PENDUDUK
=================================*/
$dataPenduduk = null;
$hasilCari = null;
$jumlahHasil = 0;
$jabatanSurat = '';
$namaPejabat  = '';
$nik = '';
$nomor_surat = '';
$keperluan = '';
$pejabat = '';
$tgl = date('Y-m-d');

/* ===============================
   PENCARIAN PENDUDUK
=================================*/
if (isset($_GET['cari']) && !empty($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);

    $hasilCari = mysqli_query($koneksi, "
        SELECT * FROM penduduk 
        WHERE NIK LIKE '%$keyword%' 
        OR NAMA_LGKP LIKE '%$keyword%'
    ");

    $jumlahHasil = mysqli_num_rows($hasilCari);

    if ($jumlahHasil == 1) {
        $dataPenduduk = mysqli_fetch_assoc($hasilCari);
    }
}
/* ===============================
   PREVIEW / SIMPAN SURAT
=================================*/
if (isset($_POST['preview']) && !empty($_POST['nik'])) {
    $modePreview = true;

    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $q = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {
        $qNo = mysqli_query($koneksi, "SELECT MAX(id) as terakhir FROM sp_ktp");
        $dNo = mysqli_fetch_assoc($qNo);
        $noUrut = $dNo['terakhir'] + 1;

        $nomor_surat = "470/" . $noUrut . "/DS-BD/" . date('Y');
        $jenis = $_POST['jenis'];

        if ($jenis == 'baru') {
        $keperluan = "Permohonan pembuatan KTP baru";
        } elseif ($jenis == 'penggantian') {
        $keperluan = "Permohonan penggantian KTP";
        }

        $pejabat     = $_POST['pejabat'];

        // tentukan jabatan & nama pejabat
        // Tentukan pejabat
        $pejabat = $_POST['pejabat'] ?? '';

        $jabatanSurat = "";
        $namaPejabat  = "";

        if ($pejabat == 'kepala' && !empty($kades)) {
        $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $kades['nama'];
        } elseif ($pejabat == 'sekdes' && !empty($sekdes)) {
        $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $sekdes['nama'];
        }

        // simpan ke database
        mysqli_query($koneksi, "INSERT INTO sp_ktp 
        (nik, nama, no_kk, alamat, tgl_surat, nomor_surat, nama_pejabat)
        VALUES (
        '{$nik}',
        '{$dataPenduduk['NAMA_LGKP']}',
        '{$dataPenduduk['NO_KK']}',
        '{$dataPenduduk['DUSUN']} RT {$dataPenduduk['RT']} RW {$dataPenduduk['RW']}',
        '{$tgl}',
        '{$nomor_surat}',
    '{$namaPejabat}'
)");
    }
}

/* ===============================
   CETAK ULANG
=================================*/
if (isset($_GET['cetak_ulang'])) {
    $modePreview = true;
    $modeCetakUlang = true;
    $id = intval($_GET['cetak_ulang']);
    $q = mysqli_query($koneksi, "
        SELECT s.*, p.*
        FROM sp_ktp s
        JOIN penduduk p ON s.nik=p.NIK
        WHERE s.id='$id'
    ");
    $dataPenduduk = mysqli_fetch_assoc($q);

    if ($dataPenduduk) {
        $nomor_surat = $dataPenduduk['nomor_surat'];

        $pejabat_id = intval($dataPenduduk['pejabat']);
        if (isset($pejabatDB[$pejabat_id])) {
        $namaPejabat = $pejabatDB[$pejabat_id]['nama'];
        $jabatanSurat = ucfirst($pejabatDB[$pejabat_id]['jabatan']) . " " . $APP_PROFIL['nama_desa'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Pengantar KTP (F.121)</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
.form-row {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
}

.label {
    width: 220px;
}

.titik {
    width: 10px;
    text-align: center;
}

.box-input {
    border: 1px solid #000;
    padding: 2px 6px;
    min-height: 18px;
    flex: 1;
}

.box-kecil {
    border: 1px solid #000;
    width: 35px;
    text-align: center;
    margin-right: 5px;
}

.box-pilihan {
    border: 1px solid #000;
    padding: 2px 10px;
    margin-right: 10px;
    display: inline-block;
}

.box-ttd {
    border: 1px solid #000;
    height: 80px;
}

.judul {
    text-align: center;
    font-weight: bold;
    margin: 5px 0;
}
 .table-form {
    width: 100%;
    border-collapse: collapse;
}

.table-form td, 
.table-form th {
    border: 1px solid #000;
    padding: 4px;
    vertical-align: top;
}

.table-title {
    border: 1px solid #000;
    padding: 6px;
    font-weight: bold;
}

.box {
    border: 1px solid #000;
    padding: 6px;
}   
@media print {
    @page { margin: 3mm 15mm 15mm 15mm; }
    body, html { background: #fff !important; }
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position: absolute; top:0; left:0; width:100%; }
    form, .no-print { display: none; }
}
</style>
</head>
<body class="bg-gray-100 p-4">

<button onclick="window.location.href='../surat.php'" class="bg-gray-600 text-white px-4 py-2 rounded mb-4">
⬅️ Kembali
</button>

<div class="max-w-3xl mx-auto bg-white p-6 shadow">

<h1 class="text-xl font-bold text-center mb-4">📑 F.121 SURAT PENGANTAR KTP</h1>

<!-- Total Surat -->
<div class="bg-blue-100 text-blue-800 p-3 rounded mb-4 text-sm">
📄 Total Surat Terbit: <b><?= $totalSurat['total'] ?></b>
</div>

<!-- Daftar Surat -->
<h2 class="text-lg font-semibold mb-2 mt-2">Daftar Surat Terbit</h2>
<div class="border rounded" style="max-height:200px; overflow-y:auto;">
<table class="w-full text-sm border-collapse border border-gray-300">
<tr class="bg-gray-200 sticky top-0">
<th class="border p-2">No</th>
<th class="border p-2">Nama</th>
<th class="border p-2">NIK</th>
<th class="border p-2">Nomor Surat</th>
<th class="border p-2">Tanggal</th>
<th class="border p-2">Aksi</th>
</tr>
<?php $no=1; while($r=mysqli_fetch_assoc($qSurat)) { ?>
<tr>
<td class="border p-2"><?= $no++ ?></td>
<td class="border p-2"><?= htmlspecialchars($r['NAMA_LGKP']) ?></td>
<td class="border p-2"><?= htmlspecialchars($r['nik']) ?></td>
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
<?php if ($modeTambah && !$modePreview): ?>
<form method="GET" class="mb-4 mt-6">
<label class="block text-sm font-medium mb-1">Cari NIK / Nama Penduduk</label>
<div class="flex gap-2">
<input type="text" name="keyword" required class="border p-2 rounded w-full">
<button type="submit" name="cari" class="bg-blue-600 text-white px-4 rounded">Cari</button>
</div>
</form>



<?php if ($dataPenduduk && $jumlahHasil == 1): ?>
<div class="border p-4 mb-4 bg-white">
    <h2 class="font-semibold mb-2">Biodata Penduduk</h2>
    <table class="text-sm w-full">
        <tr><td>Nama</td><td>: <?= htmlspecialchars($dataPenduduk['NAMA_LGKP']) ?></td></tr>
        <tr><td>NIK</td><td>: <?= htmlspecialchars($dataPenduduk['NIK']) ?></td></tr>
        <tr><td>No KK</td><td>: <?= htmlspecialchars($dataPenduduk['NO_KK']) ?></td></tr>
        <tr><td>Alamat</td>
            <td>: <?= htmlspecialchars($dataPenduduk['DUSUN']) ?> RT <?= $dataPenduduk['RT'] ?> RW <?= $dataPenduduk['RW'] ?></td>
        </tr>
    </table>
</div>
<?php endif; ?>



<?php if ($hasilCari && $jumlahHasil > 1): ?>
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
            <td class="border p-2"><?= htmlspecialchars($p['DUSUN']) ?> RT <?= $p['RT'] ?> RW <?= $p['RW'] ?></td>
            <td class="border p-2 text-center">
                <a href="?nik=<?= $p['NIK'] ?>&tambah=1" class="bg-blue-600 text-white px-3 py-1 rounded text-xs">Pilih</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php endif; ?>


<?php if ($hasilCari && $jumlahHasil == 0): ?>
<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
    ❌ Data tidak ditemukan
</div>
<?php endif; ?>






<div class="mt-4">
<?php if (!$modeTambah && !$modePreview): ?>
    <a href="?tambah=1" class="bg-green-600 text-white px-4 py-2 rounded">
        ➕ Tambah Surat Baru
    </a>
<?php endif; ?>
</div>

<?php if ($dataPenduduk): ?>
<form method="POST" class="space-y-3">

<input type="hidden" name="nik" value="<?= $dataPenduduk['NIK'] ?>">

<label class="block text-sm font-medium mb-1">Jenis Permohonan</label>
<select name="jenis" required class="border p-2 rounded w-full">
    <option value="">-- Pilih --</option>
    <option value="baru">Pembuatan Baru</option>
    <option value="penggantian">Penggantian</option>
</select>

<label class="block text-sm font-medium mb-1">Pejabat</label>
<select name="pejabat" required class="border p-2 rounded w-full">
    <option value="">-- Pilih Pejabat --</option>
    <option value="kepala" <?= ($pejabat=='kepala')?'selected':'' ?>>Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
    <option value="sekdes" <?= ($pejabat=='sekdes')?'selected':'' ?>>Sekretaris <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
</select>

<button name="preview" class="bg-green-600 text-white px-6 py-2 rounded">
    Preview Surat
</button>

</form>
<?php endif; ?>
</table>


<?php endif; ?>
</div>

<!-- PREVIEW SURAT -->
<?php if ($modePreview && $dataPenduduk): ?>
<div id="printArea" class="max-w-3xl mx-auto bg-white p-6" contenteditable="true" style="font-family: 'Times New Roman', Times, serif; font-size:12px; outline: none;">

<!-- DUPLIKAT 2X (ATAS & BAWAH) -->
<?php for($i=0;$i<2;$i++): ?>

<div style="border:2px solid #000; padding:12px; margin-bottom:20px;">

<!-- HEADER -->
<div style="text-align:right; font-weight:bold;">F-1.21</div>

<h3 style="text-align:center; margin:5px 0;">
FORMULIR PERMOHONAN KARTU TANDA PENDUDUK (KTP) WARGA NEGARA INDONESIA
</h3>

<!-- DATA WILAYAH -->
<div class="form-row">
    <div class="label">PEMERINTAH PROVINSI</div>
    <div class="titik">:</div>
    <div class="box-kecil">33</div>
    <div class="box-input">JAWA TENGAH</div>
</div>

<div class="form-row">
    <div class="label">PEMERINTAH KAB/KOTA</div>
    <div class="titik">:</div>
    <div class="box-kecil">09</div>
    <div class="box-input">BOYOLALI</div>
</div>

<div class="form-row">
    <div class="label">KECAMATAN</div>
    <div class="titik">:</div>
    <div class="box-kecil">15</div>
    <div class="box-input">KLEGO</div>
</div>

<div class="form-row">
    <div class="label">KELURAHAN/DESA</div>
    <div class="titik">:</div>
    <div class="box-kecil">2008</div>
    <div class="box-input">BADE</div>
</div>

<br>

<!-- JENIS PERMOHONAN -->
<div class="form-row">
    <div class="label"><b><i>PERMOHONAN KTP</i></b></div>

    <div class="box-pilihan">A. Baru</div>
    <div class="box-pilihan">B. Perpanjangan</div>
    <div class="box-pilihan">
        <?= $keperluan=='Permohonan penggantian KTP'?'X':'' ?> C. Penggantian
    </div>
</div>

<br>

<!-- DATA PENDUDUK -->
<div class="form-row">
    <div class="label">1. Nama Lengkap</div>
    <div class="box-input"><?= strtoupper($dataPenduduk['NAMA_LGKP']) ?></div>
</div>

<div class="form-row">
    <div class="label">2. No. KK</div>
    <div class="box-input"><?= $dataPenduduk['NO_KK'] ?></div>
</div>

<div class="form-row">
    <div class="label">3. NIK Pemohon</div>
    <div class="box-input"><?= $dataPenduduk['NIK'] ?></div>
</div>

<div class="form-row">
    <div class="label">4. Alamat</div>
    <div class="box-input"><?= $dataPenduduk['DUSUN'] ?></div>
        RT:
    <div class="box-kecil"><?= $dataPenduduk['RT'] ?></div>

    RW:
    <div class="box-kecil"><?= $dataPenduduk['RW'] ?></div>

    Kode Pos:
    <div class="box-input" style="max-width:120px;">57385</div>
</div>

<br>

<!-- FOTO + TTD -->
<div style="display:flex; margin-top:20px;">

    <!-- KIRI: FOTO & TTD -->
    <div style="width:60%;">
        <table class="table-form">
            <tr>
                <td width="30%" align="center">
                    Pas Photo (2x3)<br><br>
                    <div style="
                        width:70px;
                        height:90px;
                        border:1px solid #000;
                        margin:auto;
                        border-radius:50%;">
                    </div>
                </td>

                <td width="30%" align="center">
                    Cap Jempol
                </td>

                <td align="center">
                    Specimen Tanda Tangan
                    <div style="height:80px; border:1px solid #000; margin-top:5px;"></div>
                </td>
            </tr>
        </table>

        <div style="font-size:11px; margin-top:5px;">
            Ket : Cap Jempol/Tanda Tangan
        </div>
    </div>

    <!-- KANAN: TANDA TANGAN PEMOHON -->
    <div style="width:40%; text-align:center;">
        <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, <?= date('d F Y') ?><br>
        Pemohon<br><br><br><br>

        <u>( <?= strtoupper($dataPenduduk['NAMA_LGKP']) ?> )</u>
    </div>

</div>

<br><br>

<table width="100%">
<tr>
<td style="text-align:center;">
Camat Klego<br><br><br><br>
<u>__________________</u>
</td>

<td style="text-align:center;">
Mengetahui,<br>
<?= $jabatanSurat ?><br><br><br>
<u><?= strtoupper($namaPejabat) ?></u>
</td>
</tr>
</table>

</div>

<!-- GARIS POTONG -->
<?php if($i==0): ?>
<hr style="border-top:2px dashed black;">
<?php endif; ?>

<?php endfor; ?>


<div class="mt-4 text-center">
<button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded">🖨️ Cetak Surat</button>
</div>
<?php endif; ?>

</div>
</body>
</html>
