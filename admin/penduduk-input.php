


<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

$edit = false;
$data = [];

// Hapus data
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM penduduk WHERE id='$_GET[hapus]'");
    header("Location: penduduk.php");
    exit;
}

// Edit data
if (isset($_GET['edit'])) {
    $edit = true;

    $nik = mysqli_real_escape_string($koneksi, $_GET['edit']);

    $query = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        die("Data dengan NIK tersebut tidak ditemukan.");
    }
}

    


// Simpan / update data
if (isset($_POST['simpan']) || isset($_POST['update'])) {

    // Hitung usia di server side
    if(!empty($_POST['TANGGAL_LAHIR'])){
        $tgl_lahir = new DateTime($_POST['TANGGAL_LAHIR']);
        $today = new DateTime();
        $usia = $today->diff($tgl_lahir)->y;
    } else {
        $usia = NULL;
    }

    // Siapkan field query
    $field = "
        NIK='".mysqli_real_escape_string($koneksi, $_POST['NIK'])."',
        NO_KK='".mysqli_real_escape_string($koneksi, $_POST['NO_KK'])."',
        NAMA_LGKP='".mysqli_real_escape_string($koneksi, $_POST['NAMA_LGKP'])."',
        JENIS_KELAMIN='".mysqli_real_escape_string($koneksi, $_POST['JENIS_KELAMIN'])."',
        TMPT_LAHIR='".mysqli_real_escape_string($koneksi, $_POST['TEMPAT_LAHIR'])."',
        TGL_LAHIR='".mysqli_real_escape_string($koneksi, $_POST['TANGGAL_LAHIR'])."',
        USIA='$usia',
        DUSUN='".mysqli_real_escape_string($koneksi, $_POST['DUSUN'])."',
        RT='".mysqli_real_escape_string($koneksi, $_POST['NO_RT'])."',
        RW='".mysqli_real_escape_string($koneksi, $_POST['NO_RW'])."',
        SHDK='".mysqli_real_escape_string($koneksi, $_POST['SHDK'])."',
        STATUS_KAWIN='".mysqli_real_escape_string($koneksi, $_POST['STATUS_KAWIN'])."',
        PENDIDIKAN='".mysqli_real_escape_string($koneksi, $_POST['PENDIDIKAN'])."',
        AGAMA='".mysqli_real_escape_string($koneksi, $_POST['AGAMA'])."',
        PEKERJAAN='".mysqli_real_escape_string($koneksi, $_POST['PEKERJAAN'])."',
        NO_AKTA_LAHIR='".mysqli_real_escape_string($koneksi, $_POST['NO_AKTA_LAHIR'])."',
        NO_AKTA_KAWIN='".mysqli_real_escape_string($koneksi, $_POST['NO_AKTA_KAWIN'])."',
        NO_AKTA_CERAI='".mysqli_real_escape_string($koneksi, $_POST['NO_AKTA_CERAI'])."',
        NAMA_AYAH='".mysqli_real_escape_string($koneksi, $_POST['NAMA_AYAH'])."',
        NAMA_IBU='".mysqli_real_escape_string($koneksi, $_POST['NAMA_IBU'])."',
        BANTUAN='".mysqli_real_escape_string($koneksi, $_POST['BANTUAN'])."'
    ";

    if (isset($_POST['simpan'])) {
        if(mysqli_query($koneksi, "INSERT INTO penduduk SET $field")){
            $message = "✅ Data berhasil disimpan!";
            $data = []; // reset form
        } else {
            $message = "❌ Terjadi kesalahan: ".mysqli_error($koneksi);
        }
    } else {
        $nik = mysqli_real_escape_string($koneksi, $_POST['NIK']);

        if (mysqli_query($koneksi, "UPDATE penduduk SET $field WHERE NIK='$nik'")) {

        $message = "✅ Data berhasil diupdate!";

        $query = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");
        $data = mysqli_fetch_assoc($query);

        $edit = true;
        } else {
            $message = "❌ Terjadi kesalahan: ".mysqli_error($koneksi);
        }
    }
}


// Fetch Wilayah Data
$qDusun = mysqli_query($koneksi, "SELECT * FROM wilayah_dusun ORDER BY nama ASC");
$wilayahData = [];
while ($d = mysqli_fetch_assoc($qDusun)) {
    $dusun_id = $d['id'];
    $wilayahData[$dusun_id] = [
        'nama' => $d['nama'],
        'rws' => []
    ];
    
    $qRW = mysqli_query($koneksi, "SELECT * FROM wilayah_rw WHERE dusun_id=$dusun_id ORDER BY rw ASC");
    while ($rw = mysqli_fetch_assoc($qRW)) {
        $rw_id = $rw['id'];
        $rts = [];
        $qRT = mysqli_query($koneksi, "SELECT * FROM wilayah_rt WHERE rw_id=$rw_id ORDER BY rt ASC");
        while ($rt = mysqli_fetch_assoc($qRT)) {
            $rts[] = $rt['rt'];
        }
        $wilayahData[$dusun_id]['rws'][$rw['rw']] = $rts;
    }
}
$wilayahJson = json_encode($wilayahData);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $edit ? 'Edit' : 'Tambah' ?> Penduduk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <div class="w-64 bg-blue-800 text-white p-6">
        <h2 class="text-xl font-bold mb-6">Dashboard Admin</h2>
        <ul>
            <li class="mb-2"><a href="index.php" class="hover:underline">Beranda</a></li>
            <li class="mb-2"><a href="penduduk.php" class="hover:underline">Data Penduduk</a></li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4"><?= $edit ? 'Edit' : 'Tambah' ?> Data Penduduk</h1>

        <!-- Tombol Kembali -->
        <a href="penduduk.php" class="bg-gray-600 text-white p-2 rounded mb-4 inline-block">Kembali ke Data Penduduk</a>

        <?php if(isset($message) && $message != ''): ?>
    <div class="mb-9 p-3 rounded text-white <?= strpos($message,'✅')!==false ? 'bg-green-500' : 'bg-red-500' ?>">
        <?= $message ?>
    </div>
<?php endif; ?>



        <!-- FORM INPUT -->
        <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded shadow">

            <input type="text" name="NIK" placeholder="NIK" maxlength="16" inputmode="numeric" pattern="[0-9]{16}" class="border rounded p-2 w-full" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            <input type="text" name="NO_KK" placeholder="NO_KK" maxlength="16" inputmode="numeric" pattern="[0-9]{16}" class="border rounded p-2 w-full" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            <input type="text" name="NAMA_LGKP" placeholder="NAMA LENGKAP" class="border rounded p-2 w-full" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
            <select name="JENIS_KELAMIN" class="border rounded p-2 w-full" required>
            <option value="">Jenis Kelamin</option>
            <option value="LK" <?= ($edit && $data['JENIS_KELAMIN']=='LK') ? 'selected' : '' ?>>LK</option>
            <option value="PR" <?= ($edit && $data['JENIS_KELAMIN']=='PR') ? 'selected' : '' ?>>PR</option>
            </select>

            <input type="text"
            name="TEMPAT_LAHIR"
            placeholder="Tempat Lahir"
            value="<?= $edit ? htmlspecialchars($data['TMPT_LAHIR']) : '' ?>"
            class="border p-2 rounded"
            style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">

            <input type="date"
            name="TANGGAL_LAHIR"
            id="tanggal_lahir"
            value="<?= $edit ? $data['TGL_LAHIR'] : '' ?>"
            class="border p-2 rounded">

            <input type="number" name="USIA" id="usia" placeholder="Usia" 
            value="<?= $edit ? $data['USIA'] : '' ?>" 
            class="border p-2 rounded" readonly>
            
            <select name="DUSUN" id="dusun" class="border p-2 rounded" required>
                <option value="">Pilih Dusun</option>
                <?php foreach($wilayahData as $id => $d): ?>
                    <option value="<?= htmlspecialchars($d['nama']) ?>" <?= $edit && $data['DUSUN']==$d['nama'] ? 'selected' : '' ?>><?= htmlspecialchars($d['nama']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="NO_RW" id="rw" class="border p-2 rounded" required>
                <option value="">Pilih RW</option>
            </select>

            <select name="NO_RT" id="rt" class="border p-2 rounded" required>
                <option value="">Pilih RT</option>
            </select>
            


            <select name="SHDK" class="border p-2 rounded" required>
            <option value="">Pilih Keluarga</option>
            <option value="KEPALA KELUARGA" <?= $edit && $data['SHDK']=='KEPALA KELUARGA'?'selected':'' ?>>KEPALA KELUARGA</option>
            <option value="ISTRI" <?= $edit && $data['SHDK']=='ISTRI'?'selected':'' ?>>ISTRI</option>
            <option value="ANAK" <?= $edit && $data['SHDK']=='ANAK'?'selected':'' ?>>ANAK</option>
            <option value="CUCU" <?= $edit && $data['SHDK']=='CUCU'?'selected':'' ?>>CUCU</option>
            <option value="ORANG TUA" <?= $edit && $data['SHDK']=='ORANG TUA'?'selected':'' ?>>ORANG TUA</option>
            <option value="MERTUA" <?= $edit && $data['SHDK']=='MERTUA'?'selected':'' ?>>MERTUA</option>
            <option value="FAMILI LAIN" <?= $edit && $data['SHDK']=='FAMILI LAIN'?'selected':'' ?>>FAMILI LAIN</option>
            </select>


            <select name="STATUS_KAWIN" class="border p-2 rounded" required>
            <option value="">Pilih Status</option>
            <option value="KAWIN" <?= $edit && $data['STATUS_KAWIN']=='KAWIN'?'selected':'' ?>>KAWIN</option>
            <option value="BELUM KAWIN" <?= $edit && $data['STATUS_KAWIN']=='BELUM KAWIN'?'selected':'' ?>>BELUM KAWIN</option>
            <option value="CERAI HIDUP" <?= $edit && $data['STATUS_KAWIN']=='CERAI HIDUP'?'selected':'' ?>>CERAI HIDUP</option>
            <option value="CERAI MATI" <?= $edit && $data['STATUS_KAWIN']=='CERAI MATI'?'selected':'' ?>>CERAI MATI</option>
            </select>


            <select name="PENDIDIKAN" class="border p-2 rounded" required>
            <option value="">Pilih Pendidikan</option>
            <option value="Belum/Tidak Sekolah" <?= $edit && $data['PENDIDIKAN']=='Belum/Tidak Sekolah'?'selected':'' ?>>Belum/Tidak Sekolah</option>
            <option value="Belum Tamat SD/Sederajat" <?= $edit && $data['PENDIDIKAN']=='Belum Tamat SD/Sederajat'?'selected':'' ?>>Belum Tamat SD/Sederajat</option>
            <option value="Tamat SD/Sederajat" <?= $edit && $data['PENDIDIKAN']=='Tamat SD/Sederajat'?'selected':'' ?>>Tamat SD/Sederajat</option>
            <option value="SLTA/Sederajat" <?= $edit && $data['PENDIDIKAN']=='SLTA/Sederajat'?'selected':'' ?>>SLTA/Sederajat</option>
            <option value="Diploma I/II" <?= $edit && $data['PENDIDIKAN']=='Diploma I/II'?'selected':'' ?>>Diploma I/II</option>
            <option value="Akademi/Diploma III/S. Muda" <?= $edit && $data['PENDIDIKAN']=='Akademi/Diploma III/S. Muda'?'selected':'' ?>>Akademi/Diploma III/S. Muda</option>
            <option value="Diploma IV/Strata I" <?= $edit && $data['PENDIDIKAN']=='Diploma IV/Strata I'?'selected':'' ?>>Diploma IV/Strata I</option>
            <option value="Strata II" <?= $edit && $data['PENDIDIKAN']=='Strata II'?'selected':'' ?>>Strata II</option>
            <option value="Strata III" <?= $edit && $data['PENDIDIKAN']=='Strata III'?'selected':'' ?>>Strata III</option>
            </select>


            <select name="AGAMA" class="border p-2 rounded" required>
            <option value="">Pilih Agama</option>
            <option value="ISLAM" <?= $edit && $data['AGAMA']=='ISLAM'?'selected':'' ?>>ISLAM</option>
            <option value="KRISTEN" <?= $edit && $data['AGAMA']=='KRISTEN'?'selected':'' ?>>KRISTEN</option>
            <option value="KATHOLIK" <?= $edit && $data['AGAMA']=='KATHOLIK'?'selected':'' ?>>KATHOLIK</option>
            <option value="HINDU" <?= $edit && $data['AGAMA']=='HINDU'?'selected':'' ?>>HINDU</option>
            <option value="BUDHA" <?= $edit && $data['AGAMA']=='BUDHA'?'selected':'' ?>>BUDHA</option>
            <option value="KONGHUCHU" <?= $edit && $data['AGAMA']=='KONGHUCHU'?'selected':'' ?>>KONGHUCHU</option>
            <option value="KEPERCAYAAN" <?= $edit && $data['AGAMA']=='KEPERCAYAAN'?'selected':'' ?>>KEPERCAYAAN</option>
            </select>

            <select name="PEKERJAAN" class="border p-2 rounded" required>
            <option value="">Pilih Pekerjaan</option>
            <option value="BELUM/TIDAK BEKERJA" <?= $edit && $data['PEKERJAAN']=='BELUM/TIDAK BEKERJA'?'selected':'' ?>>BELUM/TIDAK BEKERJA</option>
            <option value="MENGURUS RUMAH TANGGA" <?= $edit && $data['PEKERJAAN']=='MENGURUS RUMAH TANGGA'?'selected':'' ?>>MENGURUS RUMAH TANGGA</option>
            <option value="PELAJAR/MAHASISWA" <?= $edit && $data['PEKERJAAN']=='PELAJAR/MAHASISWA'?'selected':'' ?>>PELAJAR/MAHASISWA</option>
            <option value="PENSIUNAN" <?= $edit && $data['PEKERJAAN']=='PENSIUNAN'?'selected':'' ?>>PENSIUNAN</option>
            <option value="PEGAWAI NEGERI SIPIL" <?= $edit && $data['PEKERJAAN']=='PEGAWAI NEGERI SIPIL'?'selected':'' ?>>PEGAWAI NEGERI SIPIL</option>
            <option value="TENTARA NASIONAL INDONESIA" <?= $edit && $data['PEKERJAAN']=='TENTARA NASIONAL INDONESIA'?'selected':'' ?>>TENTARA NASIONAL INDONESIA</option>
            <option value="KEPOLISIAN RI" <?= $edit && $data['PEKERJAAN']=='KEPOLISIAN RI'?'selected':'' ?>>KEPOLISIAN RI</option>
            <option value="PERDAGANGAN" <?= $edit && $data['PEKERJAAN']=='PERDAGANGAN'?'selected':'' ?>>PERDAGANGAN</option>
            <option value="PETANI/PEKEBUN" <?= $edit && $data['PEKERJAAN']=='PETANI/PEKEBUN'?'selected':'' ?>>PETANI/PEKEBUN</option>
            <option value="PETERNAK" <?= $edit && $data['PEKERJAAN']=='PETERNAK'?'selected':'' ?>>PETERNAK</option>
            <option value="NELAYAN/PERIKANAN" <?= $edit && $data['PEKERJAAN']=='NELAYAN/PERIKANAN'?'selected':'' ?>>NELAYAN/PERIKANAN</option>
            <option value="INDUSTRI" <?= $edit && $data['PEKERJAAN']=='INDUSTRI'?'selected':'' ?>>INDUSTRI</option>
            <option value="KONSTRUKSI" <?= $edit && $data['PEKERJAAN']=='KONSTRUKSI'?'selected':'' ?>>KONSTRUKSI</option>
            <option value="TRANSPORTASI" <?= $edit && $data['PEKERJAAN']=='TRANSPORTASI'?'selected':'' ?>>TRANSPORTASI</option>
            <option value="KARYAWAN SWASTA" <?= $edit && $data['PEKERJAAN']=='KARYAWAN SWASTA'?'selected':'' ?>>KARYAWAN SWASTA</option>
            <option value="KARYAWAN BUMN" <?= $edit && $data['PEKERJAAN']=='KARYAWAN BUMN'?'selected':'' ?>>KARYAWAN BUMN</option>
            <option value="KARYAWAN BUMD" <?= $edit && $data['PEKERJAAN']=='KARYAWAN BUMD'?'selected':'' ?>>KARYAWAN BUMD</option>
            <option value="KARYAWAN HONORER" <?= $edit && $data['PEKERJAAN']=='KARYAWAN HONORER'?'selected':'' ?>>KARYAWAN HONORER</option>
            <option value="BURUH HARIAN LEPAS" <?= $edit && $data['PEKERJAAN']=='BURUH HARIAN LEPAS'?'selected':'' ?>>BURUH HARIAN LEPAS</option>
            <option value="BURUH TANI/PERKEBUNAN" <?= $edit && $data['PEKERJAAN']=='BURUH TANI/PERKEBUNAN'?'selected':'' ?>>BURUH TANI/PERKEBUNAN</option>
            <option value="BURUH NELAYAN/PERIKANAN" <?= $edit && $data['PEKERJAAN']=='BURUH NELAYAN/PERIKANAN'?'selected':'' ?>>BURUH NELAYAN/PERIKANAN</option>
            <option value="BURUH PETERNAKAN" <?= $edit && $data['PEKERJAAN']=='BURUH PETERNAKAN'?'selected':'' ?>>BURUH PETERNAKAN</option>
            <option value="PEMBANTU RUMAH TANGGA" <?= $edit && $data['PEKERJAAN']=='PEMBANTU RUMAH TANGGA'?'selected':'' ?>>PEMBANTU RUMAH TANGGA</option>
            <option value="TUKANG CUKUR" <?= $edit && $data['PEKERJAAN']=='TUKANG CUKUR'?'selected':'' ?>>TUKANG CUKUR</option>
            <option value="TUKANG LISTRIK" <?= $edit && $data['PEKERJAAN']=='TUKANG LISTRIK'?'selected':'' ?>>TUKANG LISTRIK</option>
            <option value="TUKANG BATU" <?= $edit && $data['PEKERJAAN']=='TUKANG BATU'?'selected':'' ?>>TUKANG BATU</option>
            <option value="TUKANG KAYU" <?= $edit && $data['PEKERJAAN']=='TUKANG KAYU'?'selected':'' ?>>TUKANG KAYU</option>
            <option value="TUKANG SOL SEPATU" <?= $edit && $data['PEKERJAAN']=='TUKANG SOL SEPATU'?'selected':'' ?>>TUKANG SOL SEPATU</option>
            <option value="TUKANG LAS/PANDAI BESI" <?= $edit && $data['PEKERJAAN']=='TUKANG LAS/PANDAI BESI'?'selected':'' ?>>TUKANG LAS/PANDAI BESI</option>
            <option value="TUKANG JAHIT" <?= $edit && $data['PEKERJAAN']=='TUKANG JAHIT'?'selected':'' ?>>TUKANG JAHIT</option>
            <option value="TUKANG GIGI" <?= $edit && $data['PEKERJAAN']=='TUKANG GIGI'?'selected':'' ?>>TUKANG GIGI</option>
            <option value="PENATA RIAS" <?= $edit && $data['PEKERJAAN']=='PENATA RIAS'?'selected':'' ?>>PENATA RIAS</option>
            <option value="PENATA BUSANA" <?= $edit && $data['PEKERJAAN']=='PENATA BUSANA'?'selected':'' ?>>PENATA BUSANA</option>
            <option value="PENATA RAMBUT" <?= $edit && $data['PEKERJAAN']=='PENATA RAMBUT'?'selected':'' ?>>PENATA RAMBUT</option>
            <option value="MEKANIK" <?= $edit && $data['PEKERJAAN']=='MEKANIK'?'selected':'' ?>>MEKANIK</option>
            <option value="SENIMAN" <?= $edit && $data['PEKERJAAN']=='SENIMAN'?'selected':'' ?>>SENIMAN</option>
            <option value="TABIB" <?= $edit && $data['PEKERJAAN']=='TABIB'?'selected':'' ?>>TABIB</option>
            <option value="PARAJI" <?= $edit && $data['PEKERJAAN']=='PARAJI'?'selected':'' ?>>PARAJI</option>
            <option value="PERANCANG BUSANA" <?= $edit && $data['PEKERJAAN']=='PERANCANG BUSANA'?'selected':'' ?>>PERANCANG BUSANA</option>
            <option value="PENTERJEMAH" <?= $edit && $data['PEKERJAAN']=='PENTERJEMAH'?'selected':'' ?>>PENTERJEMAH</option>
            <option value="IMAM MESJID" <?= $edit && $data['PEKERJAAN']=='IMAM MESJID'?'selected':'' ?>>IMAM MESJID</option>
            <option value="PENDETA" <?= $edit && $data['PEKERJAAN']=='PENDETA'?'selected':'' ?>>PENDETA</option>
            <option value="PASTOR" <?= $edit && $data['PEKERJAAN']=='PASTOR'?'selected':'' ?>>PASTOR</option>
            <option value="WARTAWAN" <?= $edit && $data['PEKERJAAN']=='WARTAWAN'?'selected':'' ?>>WARTAWAN</option>
            <option value="USTADZ/MUBALIGH" <?= $edit && $data['PEKERJAAN']=='USTADZ/MUBALIGH'?'selected':'' ?>>USTADZ/MUBALIGH</option>
            <option value="JURU MASAK" <?= $edit && $data['PEKERJAAN']=='JURU MASAK'?'selected':'' ?>>JURU MASAK</option>
            <option value="PROMOTOR ACARA" <?= $edit && $data['PEKERJAAN']=='PROMOTOR ACARA'?'selected':'' ?>>PROMOTOR ACARA</option>
            <option value="ANGGOTA DPR-RI" <?= $edit && $data['PEKERJAAN']=='ANGGOTA DPR-RI'?'selected':'' ?>>ANGGOTA DPR-RI</option>
            <option value="ANGGOTA DPD" <?= $edit && $data['PEKERJAAN']=='ANGGOTA DPD'?'selected':'' ?>>ANGGOTA DPD</option>
            <option value="ANGGOTA BPK" <?= $edit && $data['PEKERJAAN']=='ANGGOTA BPK'?'selected':'' ?>>ANGGOTA BPK</option>
            <option value="PRESIDEN" <?= $edit && $data['PEKERJAAN']=='PRESIDEN'?'selected':'' ?>>PRESIDEN</option>
            <option value="WAKIL PRESIDEN" <?= $edit && $data['PEKERJAAN']=='WAKIL PRESIDEN'?'selected':'' ?>>WAKIL PRESIDEN</option>
            <option value="ANGGOTA MAHKAMAH KONSTITUSI" <?= $edit && $data['PEKERJAAN']=='ANGGOTA MAHKAMAH KONSTITUSI'?'selected':'' ?>>ANGGOTA MAHKAMAH KONSTITUSI</option>
            <option value="ANGGOTA KABINET/KEMENTERIAN" <?= $edit && $data['PEKERJAAN']=='ANGGOTA KABINET/KEMENTERIAN'?'selected':'' ?>>ANGGOTA KABINET/KEMENTERIAN</option>
            <option value="DUTA BESAR" <?= $edit && $data['PEKERJAAN']=='DUTA BESAR'?'selected':'' ?>>DUTA BESAR</option>
            <option value="GUBERNUR" <?= $edit && $data['PEKERJAAN']=='GUBERNUR'?'selected':'' ?>>GUBERNUR</option>
            <option value="WAKIL GUBERNUR" <?= $edit && $data['PEKERJAAN']=='WAKIL GUBERNUR'?'selected':'' ?>>WAKIL GUBERNUR</option>
            <option value="BUPATI" <?= $edit && $data['PEKERJAAN']=='BUPATI'?'selected':'' ?>>BUPATI</option>
            <option value="WAKIL BUPATI" <?= $edit && $data['PEKERJAAN']=='WAKIL BUPATI'?'selected':'' ?>>WAKIL BUPATI</option>
            <option value="WALIKOTA" <?= $edit && $data['PEKERJAAN']=='WALIKOTA'?'selected':'' ?>>WALIKOTA</option>
            <option value="WAKIL WALIKOTA" <?= $edit && $data['PEKERJAAN']=='WAKIL WALIKOTA'?'selected':'' ?>>WAKIL WALIKOTA</option>
            <option value="ANGGOTA DPRD PROVINSI" <?= $edit && $data['PEKERJAAN']=='ANGGOTA DPRD PROVINSI'?'selected':'' ?>>ANGGOTA DPRD PROVINSI</option>
            <option value="ANGGOTA DPRD KABUPATEN/KOTA" <?= $edit && $data['PEKERJAAN']=='ANGGOTA DPRD KABUPATEN/KOTA'?'selected':'' ?>>ANGGOTA DPRD KABUPATEN/KOTA</option>
            <option value="DOSEN" <?= $edit && $data['PEKERJAAN']=='DOSEN'?'selected':'' ?>>DOSEN</option>
            <option value="GURU" <?= $edit && $data['PEKERJAAN']=='GURU'?'selected':'' ?>>GURU</option>
            <option value="PILOT" <?= $edit && $data['PEKERJAAN']=='PILOT'?'selected':'' ?>>PILOT</option>
            <option value="PENGACARA" <?= $edit && $data['PEKERJAAN']=='PENGACARA'?'selected':'' ?>>PENGACARA</option>
            <option value="NOTARIS" <?= $edit && $data['PEKERJAAN']=='NOTARIS'?'selected':'' ?>>NOTARIS</option>
            <option value="ARSITEK" <?= $edit && $data['PEKERJAAN']=='ARSITEK'?'selected':'' ?>>ARSITEK</option>
            <option value="AKUNTAN" <?= $edit && $data['PEKERJAAN']=='AKUNTAN'?'selected':'' ?>>AKUNTAN</option>
            <option value="KONSULTAN" <?= $edit && $data['PEKERJAAN']=='KONSULTAN'?'selected':'' ?>>KONSULTAN</option>
            <option value="DOKTER" <?= $edit && $data['PEKERJAAN']=='DOKTER'?'selected':'' ?>>DOKTER</option>
            <option value="BIDAN" <?= $edit && $data['PEKERJAAN']=='BIDAN'?'selected':'' ?>>BIDAN</option>
            <option value="PERAWAT" <?= $edit && $data['PEKERJAAN']=='PERAWAT'?'selected':'' ?>>PERAWAT</option>
            <option value="APOTEKER" <?= $edit && $data['PEKERJAAN']=='APOTEKER'?'selected':'' ?>>APOTEKER</option>
            <option value="PSIKIATER/PSIKOLOG" <?= $edit && $data['PEKERJAAN']=='PSIKIATER/PSIKOLOG'?'selected':'' ?>>PSIKIATER/PSIKOLOG</option>
            <option value="PENYIAR TELEVISI" <?= $edit && $data['PEKERJAAN']=='PENYIAR TELEVISI'?'selected':'' ?>>PENYIAR TELEVISI</option>
            <option value="PENYIAR RADIO" <?= $edit && $data['PEKERJAAN']=='PENYIAR RADIO'?'selected':'' ?>>PENYIAR RADIO</option>
            <option value="PELAUT" <?= $edit && $data['PEKERJAAN']=='PELAUT'?'selected':'' ?>>PELAUT</option>
            <option value="PENELITI" <?= $edit && $data['PEKERJAAN']=='PENELITI'?'selected':'' ?>>PENELITI</option>
            <option value="SOPIR" <?= $edit && $data['PEKERJAAN']=='SOPIR'?'selected':'' ?>>SOPIR</option>
            <option value="PIALANG" <?= $edit && $data['PEKERJAAN']=='PIALANG'?'selected':'' ?>>PIALANG</option>
            <option value="PARANORMAL" <?= $edit && $data['PEKERJAAN']=='PARANORMAL'?'selected':'' ?>>PARANORMAL</option>
            <option value="PEDAGANG" <?= $edit && $data['PEKERJAAN']=='PEDAGANG'?'selected':'' ?>>PEDAGANG</option>
            <option value="PERANGKAT DESA" <?= $edit && $data['PEKERJAAN']=='PERANGKAT DESA'?'selected':'' ?>>PERANGKAT DESA</option>
            <option value="KEPALA DESA" <?= $edit && $data['PEKERJAAN']=='KEPALA DESA'?'selected':'' ?>>KEPALA DESA</option>
            <option value="BIARAWATI" <?= $edit && $data['PEKERJAAN']=='BIARAWATI'?'selected':'' ?>>BIARAWATI</option>
            <option value="WIRASWASTA" <?= $edit && $data['PEKERJAAN']=='WIRASWASTA'?'selected':'' ?>>WIRASWASTA</option>
            <option value="LAINNYA" <?= $edit && $data['PEKERJAAN']=='LAINNYA'?'selected':'' ?>>LAINNYA</option>
            </select>

            <input type="text" name="NO_AKTA_LAHIR" placeholder="No Akta Lahir" value="<?= $edit ? $data['NO_AKTA_LAHIR'] : '' ?>" class="border p-2 rounded" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">

            <input type="text" name="NO_AKTA_KAWIN" placeholder="No Akta Kawin" value="<?= $edit ? $data['NO_AKTA_KAWIN'] : '' ?>" class="border p-2 rounded" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
            <input type="text" name="NO_AKTA_CERAI" placeholder="No Akta Cerai" value="<?= $edit ? $data['NO_AKTA_CERAI'] : '' ?>" class="border p-2 rounded" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
            <input type="text" name="NAMA_AYAH" placeholder="Nama Ayah" value="<?= $edit ? $data['NAMA_AYAH'] : '' ?>" class="border p-2 rounded" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">

            <input type="text" name="NAMA_IBU" placeholder="Nama Ibu" value="<?= $edit ? $data['NAMA_IBU'] : '' ?>" class="border p-2 rounded" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
            <select name="BANTUAN" class="border p-2 rounded">
                <option value="">Bantuan</option>
                <option value="Ya" <?= $edit && $data['BANTUAN']=='Ya'?'selected':'' ?>>YA</option>
                <option value="Tidak" <?= $edit && $data['BANTUAN']=='Tidak'?'selected':'' ?>>TIDAK</option>
            </select>

            <button name="<?= $edit ? 'update' : 'simpan' ?>" class="bg-green-600 text-white p-2 rounded col-span-full">
                <?= $edit ? 'Simpan Perubahan' : 'Tambah Penduduk' ?>
            </button>
        </form>
    </div>
</div>
<script>
const wilayahData = <?= $wilayahJson ?>;
const dusunSelect = document.getElementById('dusun');
const rwSelect = document.getElementById('rw');
const rtSelect = document.getElementById('rt');

const selectedRw = "<?= $edit ? $data['RW'] : '' ?>";
const selectedRt = "<?= $edit ? $data['RT'] : '' ?>";

const allRws = [];
const allRts = [];

for(let id in wilayahData) {
    const dName = wilayahData[id].nama;
    for(let rw in wilayahData[id].rws) {
        allRws.push({rw: rw, dusun: dName});
        wilayahData[id].rws[rw].forEach(rt => {
            allRts.push({rt: rt, rw: rw, dusun: dName});
        });
    }
}

function renderDropdowns(trigger) {
    const dVal = dusunSelect.value;
    const rwVal = rwSelect.value;
    const rtVal = rtSelect.value;

    if (trigger === 'rt' && rtVal) {
        const t = allRts.find(i => i.rt === rtVal);
        if (t) {
            dusunSelect.value = t.dusun;
            rwSelect.innerHTML = '<option value="">Pilih RW</option>';
            allRws.filter(i => i.dusun === t.dusun).forEach(i => rwSelect.add(new Option(i.rw, i.rw)));
            rwSelect.value = t.rw;
            rtSelect.innerHTML = '<option value="">Pilih RT</option>';
            allRts.filter(i => i.rw === t.rw).forEach(i => rtSelect.add(new Option(i.rt, i.rt)));
            rtSelect.value = t.rt;
            return;
        }
    }
    
    if (trigger === 'rw' && rwVal) {
        const t = allRws.find(i => i.rw === rwVal);
        if (t) {
            dusunSelect.value = t.dusun;
            rwSelect.innerHTML = '<option value="">Pilih RW</option>';
            allRws.filter(i => i.dusun === t.dusun).forEach(i => rwSelect.add(new Option(i.rw, i.rw)));
            rwSelect.value = t.rw;
            rtSelect.innerHTML = '<option value="">Pilih RT</option>';
            allRts.filter(i => i.rw === t.rw).forEach(i => rtSelect.add(new Option(i.rt, i.rt)));
            if (allRts.find(i => i.rt === rtVal && i.rw === t.rw)) {
                rtSelect.value = rtVal;
            }
            return;
        }
    }

    rwSelect.innerHTML = '<option value="">Pilih RW</option>';
    const validRws = dVal ? allRws.filter(i => i.dusun === dVal) : allRws;
    validRws.forEach(i => rwSelect.add(new Option(i.rw, i.rw)));
    if (validRws.find(i => i.rw === rwVal)) rwSelect.value = rwVal;

    rtSelect.innerHTML = '<option value="">Pilih RT</option>';
    const validRts = allRts.filter(i => {
        if (dVal && i.dusun !== dVal) return false;
        if (rwSelect.value && i.rw !== rwSelect.value) return false;
        return true;
    });
    validRts.forEach(i => rtSelect.add(new Option(i.rt, i.rt)));
    if (validRts.find(i => i.rt === rtVal)) rtSelect.value = rtVal;
}

dusunSelect.addEventListener('change', () => renderDropdowns('dusun'));
rwSelect.addEventListener('change', () => renderDropdowns('rw'));
rtSelect.addEventListener('change', () => renderDropdowns('rt'));

renderDropdowns('init');

if (selectedRt) {
    rtSelect.value = selectedRt;
    renderDropdowns('rt');
} else if (selectedRw) {
    rwSelect.value = selectedRw;
    renderDropdowns('rw');
} else if (dusunSelect.value) {
    renderDropdowns('dusun');
}

const tanggalLahir = document.getElementById('tanggal_lahir');
const usia = document.getElementById('usia');

tanggalLahir.addEventListener('change', function() {
    if(this.value){
        const today = new Date();
        const birthDate = new Date(this.value);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        usia.value = age;
    } else {
        usia.value = '';
    }
});
</script>


</body>
</html>
