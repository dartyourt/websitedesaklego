<?php
include '../config/database.php';

$kk = mysqli_real_escape_string($koneksi, $_GET['kk']);

// Ambil data seluruh anggota keluarga
$query = mysqli_query($koneksi, "
SELECT *
FROM penduduk
WHERE NO_KK='$kk'
ORDER BY
CASE SHDK
    WHEN 'KEPALA KELUARGA' THEN 1
    WHEN 'ISTRI' THEN 2
    WHEN 'ANAK' THEN 3
    WHEN 'CUCU' THEN 4
    WHEN 'ORANG TUA' THEN 5
    WHEN 'MERTUA' THEN 6
    WHEN 'FAMILI LAIN' THEN 7
    ELSE 99
END,
NAMA_LGKP
");

// Ambil data Kepala Keluarga
$qKK = mysqli_query($koneksi,"
SELECT *
FROM penduduk
WHERE NO_KK='$kk'
AND SHDK='KEPALA KELUARGA'
LIMIT 1
");

$kkData = mysqli_fetch_assoc($qKK);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lembar Data Keluarga</title>

<script>
window.onload = function(){
    window.print();
}
</script>

<style>

@page{
    size:A4 landscape;
    margin:10mm;
}

body{
    font-family:Arial, Helvetica, sans-serif;
    font-size:11px;
    color:#000;
}

.judul{
    width:480px;
    margin:0 auto 25px auto;
    text-align:center;
    border:1px solid #000;
    font-size:18px;
    font-weight:bold;
    padding:4px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    border:1px solid #000;
    padding:4px;
    text-align:center;
    font-size:10px;
    background:#e6e6e6;
}

td{
    border:1px solid #000;
    padding:3px;
    font-size:10px;
}

.info td{
    border:none;
    padding:2px 4px;
    vertical-align:top;
}

.center{
    text-align:center;
}

.right{
    text-align:right;
}

.bold{
    font-weight:bold;
}

.kotak{
    border:1px solid #000;
    padding:2px 5px;
}

.spasi{
    height:10px;
}

</style>

</head>

<body>

<div class="judul">
    No. <?= $kk ?>
</div>
<table class="info" style="margin-bottom:10px;">
<tr>

    <!-- KOLOM KIRI -->
    <td style="width:55%;">

        <table class="info" style="width:100%;">

            <tr>
                <td style="width:170px;">Nama Kepala Keluarga</td>
                <td style="width:15px;">:</td>
                <td class="kotak bold"><?= $kkData['NAMA_LGKP'] ?></td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td class="kotak"><?= $kkData['DUSUN'] ?></td>
            </tr>

            <tr>
                <td>RT / RW</td>
                <td>:</td>
                <td class="kotak">
                    <?= $kkData['RT'] ?> / <?= $kkData['RW'] ?>
                </td>
            </tr>

            <tr>
                <td>Kelurahan / Desa</td>
                <td>:</td>
                <td class="kotak"><?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa'])) ?></td>
            </tr>

        </table>

    </td>

    <!-- KOLOM KANAN -->
    <td style="width:45%;">

        <table class="info" style="width:100%;">

            <tr>
                <td style="width:130px;">Kecamatan</td>
                <td style="width:15px;">:</td>
                <td class="kotak">KLEGO</td>
            </tr>

            <tr>
                <td>Kabupaten / Kota</td>
                <td>:</td>
                <td class="kotak">BOYOLALI</td>
            </tr>

            <tr>
                <td>Kode Pos</td>
                <td>:</td>
                <td class="kotak">57385</td>
            </tr>

            <tr>
                <td>Provinsi</td>
                <td>:</td>
                <td class="kotak">JAWA TENGAH</td>
            </tr>

        </table>

    </td>

</tr>
</table>




<table style="margin-top:10px;">

<thead>

<tr>

    <th rowspan="2" style="width:35px">No</th>

    <th rowspan="2" style="width:120px">NIK</th>

    <th rowspan="2">Nama Lengkap</th>

    <th rowspan="2" style="width:90px">Tanggal<br>Lahir</th>

    <th rowspan="2" style="width:120px">Tempat Lahir</th>

    <th rowspan="2" style="width:90px">Agama</th>

    <th colspan="2">Nama Orang Tua</th>

</tr>

<tr>

    <th style="width:180px">Ayah</th>

    <th style="width:180px">Ibu</th>

</tr>

<tr class="center bold">

    <td></td>
    <td>(1)</td>
    <td>(2)</td>
    <td>(3)</td>
    <td>(4)</td>
    <td>(5)</td>
    <td>(6)</td>
    <td>(7)</td>

</tr>

</thead>

<tbody>


<?php
$no = 1;

mysqli_data_seek($query, 0);

while($d = mysqli_fetch_assoc($query)){
?>

<tr>

<td class="center"><?= $no++ ?></td>

<td><?= htmlspecialchars($d['NIK']) ?></td>

<td><?= htmlspecialchars($d['NAMA_LGKP']) ?></td>

<td class="center">
<?= !empty($d['TGL_LAHIR']) ? date('d-m-Y',strtotime($d['TGL_LAHIR'])) : '-' ?>
</td>

<td><?= htmlspecialchars($d['TMPT_LAHIR']) ?></td>

<td><?= htmlspecialchars($d['AGAMA']) ?></td>

<td><?= htmlspecialchars($d['NAMA_AYAH']) ?></td>

<td><?= htmlspecialchars($d['NAMA_IBU']) ?></td>

</tr>

<?php } ?>

</tbody>

</table>
<br>

<table>

<thead>

<tr>

    <th rowspan="2" style="width:35px">No</th>

    <th rowspan="2">Jenis Pekerjaan</th>

    <th rowspan="2">Pendidikan</th>

    <th rowspan="2" style="width:80px">Jenis<br>Kelamin</th>

    <th rowspan="2" style="width:120px">Status<br>Perkawinan</th>

    <th rowspan="2" style="width:140px">Status Hubungan<br>Dalam Keluarga</th>

    <th rowspan="2" style="width:90px">Kewarganegaraan</th>

    <th rowspan="2" style="width:120px">No. Paspor</th>

    <th rowspan="2" style="width:120px">No. KITAS/<br>KITAP</th>

</tr>

<tr></tr>

<tr class="center bold">

    <td></td>
    <td>(8)</td>
    <td>(9)</td>
    <td>(10)</td>
    <td>(11)</td>
    <td>(12)</td>
    <td>(13)</td>
    <td>(14)</td>
    <td>(15)</td>

</tr>

</thead>

<tbody>

<?php
$no = 1;

mysqli_data_seek($query, 0);

while($d = mysqli_fetch_assoc($query)){
?>

<tr>

<td class="center"><?= $no++ ?></td>

<td><?= htmlspecialchars($d['PEKERJAAN']) ?></td>

<td><?= htmlspecialchars($d['PENDIDIKAN']) ?></td>

<td class="center"><?= htmlspecialchars($d['JENIS_KELAMIN']) ?></td>

<td><?= htmlspecialchars($d['STATUS_KAWIN']) ?></td>

<td><?= htmlspecialchars($d['SHDK']) ?></td>

<td class="center">WNI</td>

<td>-</td>

<td>-</td>

</tr>


<?php } ?>

</table>
<table style="border:none; width:100%;">

<tr>
<br><br>
    <td style="border:none; width:60%; vertical-align:top;">

        <b>Keterangan :</b><br>
        Lembar Data Keluarga ini dicetak dari Sistem Informasi Administrasi Penduduk Desa
        dan digunakan sebagai data pendukung administrasi pemerintahan desa.

    </td>

    <td style="border:none; width:40%; text-align:center; vertical-align:top;">

        <?= strtoupper(htmlspecialchars($APP_PROFIL['nama_desa'])) ?>, <?= date('d F Y') ?><br>
        Mengetahui,<br>
        <b>KEPALA <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></b>

        <br><br><br><br><br>

        <b><u>HARYONO</u></b>

    </td>

</tr>

</table>

</body>
</html>
