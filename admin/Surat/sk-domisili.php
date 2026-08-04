
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


// Cegah error kalau kosong
$namaKades  = $kades['nama']  ?? '-';
$nipKades   = $kades['nip']   ?? '';
$namaSekdes = $sekdes['nama'] ?? '-';
$nipSekdes  = $sekdes['nip']  ?? '';
$camat = mysqli_fetch_assoc(mysqli_query($koneksi,
"SELECT * FROM pejabat WHERE jabatan='camat' LIMIT 1"));

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
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM sk_dom_luar_bade");
$totalSurat = mysqli_fetch_assoc($qTotal);

/* ============================================
   DAFTAR SURAT TERBIT
============================================ */
$qSurat = mysqli_query($koneksi, "
    SELECT id, nomor_surat, tgl_surat, nama_manual
    FROM sk_dom_luar_bade
    ORDER BY id DESC
");







/* ============================================
   VARIABEL
============================================ */
$ttdCamat = false;
$dataPenduduk = null;
$modePreview = false;
$modeCetak = false;
$modeTambah = false;

if (isset($_GET['tambah'])) {
    $modeTambah = true;
}
$hasilCari = null;
$jabatan = '';
$namaPejabat = '';
$nik_manual = '';
$nomor = '';
$keperluan = '';
$nik = '';
$rw = '';
$sejak = '';
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
        SELECT * 
        FROM sk_dom_luar_bade
        WHERE id = '$id'
    ");

    $dataPenduduk = mysqli_fetch_assoc($q);
    if ($dataPenduduk) {

        // =============================
        // BIODATA
        // =============================
        $nama        = $dataPenduduk['nama_manual'] ?? '';
        $jk          = $dataPenduduk['jk'] ?? '';
        $nama_ayah   = $dataPenduduk['nama_ayah'] ?? '';
        $tmpt_lahir  = $dataPenduduk['tmpt_lahir'] ?? '';
        $tgl_lahir   = $dataPenduduk['tgl_lahir'] ?? '';
        $agama       = $dataPenduduk['agama'] ?? '';
        $pekerjaan   = $dataPenduduk['pekerjaan'] ?? '';
        $alamat      = $dataPenduduk['alamat'] ?? '';
        $nik_manual  = $dataPenduduk['nik_manual'] ?? '';
        $no_kk       = $dataPenduduk['no_kk'] ?? '';
        $rw          = $dataPenduduk['RW'] ?? '';
        $sejak       = $dataPenduduk['sejak_tinggal'] ?? '';

        // =============================
        // SURAT
        // =============================
        $nomor       = $dataPenduduk['nomor_surat'] ?? '';
        $keperluan   = $dataPenduduk['keperluan'] ?? '';
        $pejabat     = $dataPenduduk['pejabat'] ?? '';
        $ttdCamat    = $dataPenduduk['ttd_camat'] ?? 0;
        // =============================
        // PEJABAT
        // =============================
        if ($pejabat == 'kepala' && !empty($kades)) {
            $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
            $namaPejabat  = $kades['nama'];
        } elseif ($pejabat == 'sekdes' && !empty($sekdes)) {
            $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
            $namaPejabat  = $sekdes['nama'];
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
        $modeCetak   = true;
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
if (isset($_POST['preview'])) {
    $modePreview = true;
    $modeCetak   = true;

    $nama       = $_POST['nama'];
    $jk         = $_POST['jk'];
    $nama_ayah  = $_POST['nama_ayah'] ?? '';
    $tmpt_lahir = $_POST['tmpt_lahir'];
    $tgl_lahir  = $_POST['tgl_lahir'];
    $agama      = $_POST['agama'];
    $pekerjaan  = $_POST['pekerjaan'];
    $alamat     = $_POST['alamat'];
    $nik_manual        = $_POST['nik_manual'];
    $no_kk      = $_POST['no_kk'];
    $nomor      = $_POST['nomor_surat'];
    $keperluan  = $_POST['keperluan'];
    $pejabat    = $_POST['pejabat'];
    $pejabat = $_POST['pejabat'] ?? 'kepala';
    $rw    = $_POST['RW'] ?? '';
    $sejak = $_POST['sejak_tinggal'] ?? null;
    $ttd_camat = isset($_POST['ttd_camat']) ? 1 : 0;

    if ($pejabat == 'kepala' && !empty($kades)) {
    $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $kades['nama'];
    }
    elseif ($pejabat == 'sekdes' && !empty($sekdes)) {
    $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $sekdes['nama'];
    }
    $tgl        = date('Y-m-d');

    // Tentukan pejabat
    if ($pejabat == 'kepala' && !empty($kades)) {
        $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $kades['nama'];
    } elseif ($pejabat == 'sekdes' && !empty($sekdes)) {
        $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
        $namaPejabat  = $sekdes['nama'];
    }

    // Simpan ke database
    mysqli_query($koneksi,"
    INSERT INTO sk_dom_luar_bade
(nama_manual, nik_manual, nama_ayah, no_kk, jk, tmpt_lahir, tgl_lahir,
agama, pekerjaan, alamat, nomor_surat, keperluan, pejabat,
tgl_surat, RW, sejak_tinggal, ttd_camat)

VALUES
('$nama','$nik_manual','$nama_ayah','$no_kk','$jk','$tmpt_lahir','$tgl_lahir',
'$agama','$pekerjaan','$alamat','$nomor','$keperluan','$pejabat',
'$tgl','$rw','$sejak','$ttd_camat')
    ");
}







$kades = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT nama FROM pejabat WHERE jabatan='kepala' LIMIT 1"
));

$sekdes = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT nama FROM pejabat WHERE jabatan='sekdes' LIMIT 1"
));

$pejabat = $_POST['ttd'] ?? 'kepala'; // pilihan dari form

if ($pejabat == 'sekdes' && !empty($sekdes)) {
    $jabatanSurat = "a/n Kepala " . $APP_PROFIL['nama_desa'] . "<br>Sekretaris " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $sekdes['nama'] ?? '-';
} else {
    $jabatanSurat = "Kepala " . $APP_PROFIL['nama_desa'];
    $namaPejabat  = $kades['nama'] ?? '-';
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

<h1 class="text-xl font-bold text-center mb-4">📄 SURAT KETERANGAN DOMISILI LUAR <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h1>

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
<td class="border p-2"><?= htmlspecialchars($r['nama_manual']) ?></td>
<td class="border p-2"><?= htmlspecialchars($r['nomor_surat']) ?></td>
<td class="border p-2"><?= date('d-m-Y', strtotime($r['tgl_surat'])) ?></td>
<td class="border p-2 text-center">
<a href="?cetak_ulang=<?= $r['id'] ?>" class="text-blue-600">Cetak</a>
</td>
</tr>
<?php } ?>
</table>
</div>
<br>

<?php if (!$modeTambah && !$modePreview && !$modeCetak) { ?>
<div class="mt-4">
    <a href="?tambah=1"
       class="bg-green-600 text-white px-4 py-2 rounded">
       ➕ Tambah Surat Baru
    </a>
</div>
<?php } ?>

<!-- FORM INPUT -->
<?php if ($modeTambah && !$modePreview && !$modeCetak) { ?>
<form method="POST" class="space-y-3">

<label class="block text-sm font-medium">Nama Lengkap</label>
<input type="text" name="nama" required class="border p-2 rounded w-full" style="text-transform: uppercase;"
          oninput="this.value=this.value.toUpperCase();">


<label class="block text-sm font-medium">Jenis Kelamin</label>
<select name="jk" required class="border p-2 rounded w-full">
<option value="">-- Pilih --</option>
<option value="Laki-laki">LAKI-LAKI</option>
<option value="Perempuan">PEREMPUAN</option>
</select>

<label>Nama Ayah</label>
<input type="text" name="nama_ayah" class="border p-2 w-full" style="text-transform: uppercase;"
          oninput="this.value=this.value.toUpperCase();">

<label class="block text-sm font-medium">Tempat Lahir</label>
<input type="text" name="tmpt_lahir" required class="border p-2 rounded w-full" style="text-transform: uppercase;"
          oninput="this.value=this.value.toUpperCase();">

<label class="block text-sm font-medium">Tanggal Lahir</label>
<input type="date" name="tgl_lahir" required class="border p-2 rounded w-full">

<label class="block text-sm font-medium">Agama</label>
<select name="agama" required class="border p-2 rounded w-full">
<option value="">-- Pilih --</option>
<option value="ISLAM">ISLAM</option>
<option value="KRISTEN">KRISTEN</option>
<option value="KATHOLIK">KATHOLIK</option>
<option value="HINDU">HINDU</option>
<option value="BUDHA">BUDHA</option>
<option value="KONGHUCHU">KONGHUCHU</option>
<option value="KEPERCAYAAN">KEPERCAYAAN</option>
</select>


<label class="block text-sm font-medium">Pekerjaan</label>
<select name="pekerjaan" required class="border p-2 rounded w-full">
<option value="">-- Pilih --</option>
    <option value="BELUM/TIDAK BEKERJA">BELUM/TIDAK BEKERJA</option>
    <option value="MENGURUS RUMAH TANGGA">MENGURUS RUMAH TANGGA</option>
    <option value="PELAJAR/MAHASISWA">PELAJAR/MAHASISWA</option>
    <option value="PENSIUNAN">PENSIUNAN</option>
    <option value="PEGAWAI NEGERI SIPIL">PEGAWAI NEGERI SIPIL</option>
    <option value="TENTARA NASIONAL INDONESIA">TENTARA NASIONAL INDONESIA</option>
    <option value="KEPOLISIAN RI">KEPOLISIAN RI</option>
    <option value="PERDAGANGAN">PERDAGANGAN</option>
    <option value="PETANI/PEKEBUN">PETANI/PEKEBUN</option>
    <option value="PETERNAK">PETERNAK</option>
    <option value="NELAYAN/PERIKANAN">NELAYAN/PERIKANAN</option>
    <option value="INDUSTRI">INDUSTRI</option>
    <option value="KONSTRUKSI">KONSTRUKSI</option>
    <option value="TRANSPORTASI">TRANSPORTASI</option>
    <option value="KARYAWAN SWASTA">KARYAWAN SWASTA</option>
    <option value="KARYAWAN BUMN">KARYAWAN BUMN</option>
    <option value="KARYAWAN BUMD">KARYAWAN BUMD</option>
    <option value="KARYAWAN HONORER">KARYAWAN HONORER</option>
    <option value="BURUH HARIAN LEPAS">BURUH HARIAN LEPAS</option>
    <option value="BURUH TANI/PERKEBUNAN">BURUH TANI/PERKEBUNAN</option>
    <option value="BURUH NELAYAN/PERIKANAN">BURUH NELAYAN/PERIKANAN</option>
    <option value="BURUH PETERNAKAN">BURUH PETERNAKAN</option>
    <option value="PEMBANTU RUMAH TANGGA">PEMBANTU RUMAH TANGGA</option>
    <option value="TUKANG CUKUR">TUKANG CUKUR</option>
    <option value="TUKANG LISTRIK">TUKANG LISTRIK</option>
    <option value="TUKANG BATU">TUKANG BATU</option>
    <option value="TUKANG KAYU">TUKANG KAYU</option>
    <option value="TUKANG SOL SEPATU">TUKANG SOL SEPATU</option>
    <option value="TUKANG LAS/PANDAI BESI">TUKANG LAS/PANDAI BESI</option>
    <option value="TUKANG JAHIT">TUKANG JAHIT</option>
    <option value="TUKANG GIGI">TUKANG GIGI</option>
    <option value="PENATA RIAS">PENATA RIAS</option>
    <option value="PENATA BUSANA">PENATA BUSANA</option>
    <option value="PENATA RAMBUT">PENATA RAMBUT</option>
    <option value="MEKANIK">MEKANIK</option>
    <option value="SENIMAN">SENIMAN</option>
    <option value="TABIB">TABIB</option>
    <option value="PARAJI">PARAJI</option>
    <option value="PERANCANG BUSANA">PERANCANG BUSANA</option>
    <option value="PENTERJEMAH">PENTERJEMAH</option>
    <option value="IMAM MESJID">IMAM MESJID</option>
    <option value="PENDETA">PENDETA</option>
    <option value="PASTOR">PASTOR</option>
    <option value="WARTAWAN">WARTAWAN</option>
    <option value="USTADZ/MUBALIGH">USTADZ/MUBALIGH</option>
    <option value="JURU MASAK">JURU MASAK</option>
    <option value="PROMOTOR ACARA">PROMOTOR ACARA</option>
    <option value="ANGGOTA DPR-RI">ANGGOTA DPR-RI</option>
    <option value="ANGGOTA DPD">ANGGOTA DPD</option>
    <option value="ANGGOTA BPK">ANGGOTA BPK</option>
    <option value="PRESIDEN">PRESIDEN</option>
    <option value="WAKIL PRESIDEN">WAKIL PRESIDEN</option>
    <option value="ANGGOTA MAHKAMAH KONSTITUSI">ANGGOTA MAHKAMAH KONSTITUSI</option>
    <option value="ANGGOTA KABINET/KEMENTERIAN">ANGGOTA KABINET/KEMENTERIAN</option>
    <option value="DUTA BESAR">DUTA BESAR</option>
    <option value="GUBERNUR">GUBERNUR</option>
    <option value="WAKIL GUBERNUR">WAKIL GUBERNUR</option>
    <option value="BUPATI">BUPATI</option>
    <option value="WAKIL BUPATI">WAKIL BUPATI</option>
    <option value="WALIKOTA">WALIKOTA</option>
    <option value="WAKIL WALIKOTA">WAKIL WALIKOTA</option>
    <option value="ANGGOTA DPRD PROVINSI">ANGGOTA DPRD PROVINSI</option>
    <option value="ANGGOTA DPRD KABUPATEN/KOTA">ANGGOTA DPRD KABUPATEN/KOTA</option>
    <option value="DOSEN">DOSEN</option>
    <option value="GURU">GURU</option>
    <option value="PILOT">PILOT</option>
    <option value="PENGACARA">PENGACARA</option>
    <option value="NOTARIS">NOTARIS</option>
    <option value="ARSITEK">ARSITEK</option>
    <option value="AKUNTAN">AKUNTAN</option>
    <option value="KONSULTAN">KONSULTAN</option>
    <option value="DOKTER">DOKTER</option>
    <option value="BIDAN">BIDAN</option>
    <option value="PERAWAT">PERAWAT</option>
    <option value="APOTEKER">APOTEKER</option>
    <option value="PSIKIATER/PSIKOLOG">PSIKIATER/PSIKOLOG</option>
    <option value="PENYIAR TELEVISI">PENYIAR TELEVISI</option>
    <option value="PENYIAR RADIO">PENYIAR RADIO</option>
    <option value="PELAUT">PELAUT</option>
    <option value="PENELITI">PENELITI</option>
    <option value="SOPIR">SOPIR</option>
    <option value="PIALANG">PIALANG</option>
    <option value="PARANORMAL">PARANORMAL</option>
    <option value="PEDAGANG">PEDAGANG</option>
    <option value="PERANGKAT DESA">PERANGKAT DESA</option>
    <option value="KEPALA DESA">KEPALA DESA</option>
    <option value="BIARAWATI">BIARAWATI</option>
    <option value="WIRASWASTA">WIRASWASTA</option>
    <option value="LAINNYA">LAINNYA</option>
</select>




<label class="block text-sm font-medium">Alamat</label>
<textarea name="alamat" required class="border p-2 rounded w-full" style="text-transform: uppercase;"
          oninput="this.value=this.value.toUpperCase();"></textarea>


<label class="block text-sm font-medium">RW Sekarang di <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></label>
<select name="RW" required class="border p-2 rounded w-full">
<option value="">-- Pilih --</option>
<option value="001">001</option>
<option value="002">002</option>
<option value="003">003</option>
<option value="004">004</option>
</select>



<label class="block text-sm font-medium">NIK</label>
<input
    type="text"
    name="nik_manual"
    class="border p-2 rounded w-full"
    maxlength="16"
    pattern="[0-9]{16}"
    inputmode="numeric"
    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
    required>

<label class="block text-sm font-medium">No KK</label>
<input
    type="text"
    name="no_kk"
    class="border p-2 rounded w-full"
    maxlength="16"
    pattern="[0-9]{16}"
    inputmode="numeric"
    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
    required>

<label class="block text-sm font-medium">Tinggal di <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?> Sejak</label>
<input type="date" name="sejak_tinggal"class="border p-2 rounded w-full" required>

<label class="block text-sm font-medium">Nomor Surat</label>
<input type="text" name="nomor_surat" required class="border p-2 rounded w-full">

<label class="block text-sm font-medium">Keperluan</label>
<textarea name="keperluan" required class="border p-2 rounded w-full" style="text-transform: uppercase;"
          oninput="this.value=this.value.toUpperCase();"></textarea>


<label class="block text-sm font-medium">Pejabat Penandatangan</label>
<select name="pejabat" required class="border p-2 rounded w-full">
<option value="">-- Pilih Pejabat --</option>
<?php if(!empty($kades)) : ?>
<option value="kepala">Kepala <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
<?php endif; ?>
<?php if(!empty($sekdes)) : ?>
<option value="sekdes">Sekretaris <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></option>
<?php endif; ?>
</select>
<label><input type="checkbox" name="ttd_camat" value="1">Tanda Tangan Camat</label>


<?php } ?>
<br>
<?php if ($modeTambah && !$modePreview && !$modeCetak) { ?>
<button type="submit" name="preview"
class="bg-green-600 text-white px-4 py-2 rounded">
📄 Preview Surat
</button>
<?php } ?>

</form>
<?php if ($modeCetak) { ?>
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
        <tr><td width="5%">1.</td><td width="30%">Nama Lengkap</td><td>: <?= strtoupper(htmlspecialchars($nama)) ?></td></tr>
        <tr><td>2.</td><td>Jenis Kelamin</td><td>: <?= strtoupper(htmlspecialchars($jk)) ?></td></tr>
        <tr><td>3.</td><td>Bin/Binti</td><td>: <?= strtoupper(htmlspecialchars($nama_ayah)) ?></td></tr>
        <tr><td>4.</td><td>Tempat / Tanggal Lahir</td><td>: <?= strtoupper(htmlspecialchars($tmpt_lahir)) ?>/ <?= strtoupper(tglIndonesia($tgl_lahir)) ?></td></tr>
        <tr><td>5.</td><td>Agama</td><td>: <?= strtoupper(htmlspecialchars($agama)) ?></td></tr>
        <tr><td>6.</td><td>Warganegara</td><td>: INDONESIA</td></tr>
        <tr><td>7.</td><td>NIK</td><td>: <?= strtoupper(htmlspecialchars($nik_manual)) ?></td></tr>
        <tr><td>8.</td><td>No. KK</td><td>: <?= strtoupper(htmlspecialchars($no_kk)) ?></td></tr>
        <tr><td>9.</td><td>Pekerjaan</td><td>: <?= strtoupper(htmlspecialchars($pekerjaan)) ?></td></tr>
        <tr><td>10.</td><td>Alamat</td><td>: <?= strtoupper(htmlspecialchars($alamat)) ?> </td></tr>
    </table>
    <p class="indent-8 text-sm text-justify mb-6">Berdasarkan Surat Keterangan dari Ketua RW <?= htmlspecialchars($rw ?? '') ?> <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?> Tanggal <?php $tglSurat = $dataPenduduk['tgl_surat'] ?? date('Y-m-d');?><?= tglIndonesia($tglSurat) ?> , bahwa yang bersangkutan telah tinggal di <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, Kecamatan <?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>, Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten']) ?> sejak Tanggal <?= !empty($sejak) ? tglIndonesia($sejak): '' ?>, surat ini dibuat untuk keperluan <?= nl2br(htmlspecialchars($keperluan)) ?> </p>

    <p class="text-sm text-justify mb-6">
        Demikian surat keterangan domisili ini dibuat dengan sebenarnya, agar dapat dipergunakan sebagaimana mestinya.
    </p>


    <!-- Tanda Tangan -->
    <table width="100%" style="margin-top:40px;">
<tr>

<td width="50%" valign="top" style="height:200px;">
<!-- CAMAT -->
<?php if (!empty($camat['nama'])) { ?>
<p style="text-align:center;">
Mengetahui,<br>
Camat Klego
<br><br><br><br>
<b><?= $camat['nama']; ?></b></p>
<p style="text-align:center;">
<?= htmlspecialchars($camat['gol'] ?? '') ?><br>
NIP. <?= htmlspecialchars($camat['nip'] ?? '') ?>
</p>
<?php } ?>
</td>

<td width="50%" valign="top" style="height:200px;">
<!-- KADES -->
<p style="text-align:center;">
<?php
$tglSurat = $dataPenduduk['tgl_surat'] ?? date('Y-m-d');
?>
<?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>, <?= tglIndonesia($tglSurat) ?><br>
<?= $jabatanSurat ?>
</p>

<br><br><br>

<p style="text-align:center;">
<strong><?= htmlspecialchars($namaPejabat) ?></strong>
</p>
</td>

</tr>
</table>

    </div>
<?php } ?>
<?php if ($modeCetak) { ?>
<div class="mt-6 text-center no-print">

    <!-- Tombol Cetak -->
    <button onclick="window.print()" 
    class="bg-blue-600 text-white px-6 py-2 rounded mr-2">
        🖨️ Cetak Surat
    </button>

    <!-- Tombol Tambah Surat -->
    <a href="sk-domisili.php" 
    class="bg-green-600 text-white px-6 py-2 rounded ml-2">
    ➕ Tambah Surat Baru
    </a>

</div>
<?php } ?>

</div>

</body>
</html>
