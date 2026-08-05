<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

/* ======================
   PROSES HAPUS DATA
====================== */
if (isset($_GET['hapus'])) {

    $nik = mysqli_real_escape_string($koneksi, $_GET['hapus']);

    mysqli_begin_transaction($koneksi);

    try {

        // Ambil data dari tabel penduduk
        $q = mysqli_query($koneksi, "SELECT * FROM penduduk WHERE NIK='$nik'");

        if(mysqli_num_rows($q) == 0){
            throw new Exception("Data tidak ditemukan");
        }

        $data = mysqli_fetch_assoc($q);

        // Tambahkan informasi penghapusan
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $data['deleted_by'] = $_SESSION['login'];

        // Simpan ke tabel penduduk_dihapus
        $kolom = array_keys($data);

        $nilai = [];
        foreach($data as $v){
            $nilai[] = "'" . mysqli_real_escape_string($koneksi, $v) . "'";
        }

        $sqlInsert = "INSERT INTO penduduk_dihapus (`".implode("`,`",$kolom)."`)
                      VALUES (".implode(",",$nilai).")";

        if(!mysqli_query($koneksi,$sqlInsert)){
            throw new Exception(mysqli_error($koneksi));
        }

        // Hapus dari tabel utama
        if(!mysqli_query($koneksi,"DELETE FROM penduduk WHERE NIK='$nik'")){
            throw new Exception(mysqli_error($koneksi));
        }

        mysqli_commit($koneksi);

        echo "<script>
                alert('Data berhasil dipindahkan ke Data Penduduk Dihapus');
                window.location='penduduk.php';
              </script>";

    } catch(Exception $e){

        mysqli_rollback($koneksi);

        echo "<script>
                alert('".$e->getMessage()."');
              </script>";
    }
}

/* ======================
   PROSES RESET DATA
====================== */
if (isset($_GET['reset']) && $_GET['reset'] == 'true') {
    mysqli_query($koneksi, "TRUNCATE TABLE penduduk");
    echo "<script>
            alert('Berhasil mengosongkan / me-reset seluruh data penduduk!');
            window.location='penduduk.php';
          </script>";
    exit;
}

/* ======================
   PENCARIAN
====================== */

$where = [];

if(!empty($_GET['nik']))
    $where[] = "NIK LIKE '%".mysqli_real_escape_string($koneksi,$_GET['nik'])."%'";

if(!empty($_GET['kk']))
    $where[] = "NO_KK LIKE '%".mysqli_real_escape_string($koneksi,$_GET['kk'])."%'";

if(!empty($_GET['nama']))
    $where[] = "NAMA_LGKP LIKE '%".mysqli_real_escape_string($koneksi,$_GET['nama'])."%'";

if(!empty($_GET['jk']))
    $where[] = "JENIS_KELAMIN='".mysqli_real_escape_string($koneksi,$_GET['jk'])."'";

if(!empty($_GET['tempat']))
    $where[] = "TMPT_LAHIR LIKE '%".mysqli_real_escape_string($koneksi,$_GET['tempat'])."%'";

if(!empty($_GET['tgl']))
    $where[] = "TGL_LAHIR='".mysqli_real_escape_string($koneksi,$_GET['tgl'])."'";

if(!empty($_GET['agama']))
    $where[] = "AGAMA='".mysqli_real_escape_string($koneksi,$_GET['agama'])."'";

if(!empty($_GET['pekerjaan']))
    $where[] = "PEKERJAAN LIKE '%".mysqli_real_escape_string($koneksi,$_GET['pekerjaan'])."%'";

if(!empty($_GET['ayah']))
    $where[] = "NAMA_AYAH LIKE '%".mysqli_real_escape_string($koneksi,$_GET['ayah'])."%'";

if(!empty($_GET['ibu']))
    $where[] = "NAMA_IBU LIKE '%".mysqli_real_escape_string($koneksi,$_GET['ibu'])."%'";

if(!empty($_GET['rt']))
    $where[] = "RT='".mysqli_real_escape_string($koneksi,$_GET['rt'])."'";

if(!empty($_GET['rw']))
    $where[] = "RW='".mysqli_real_escape_string($koneksi,$_GET['rw'])."'";

if(!empty($_GET['status']))
    $where[] = "STATUS_KAWIN='".mysqli_real_escape_string($koneksi,$_GET['status'])."'";

if(!empty($_GET['dusun']))
    $where[] = "DUSUN LIKE '%".mysqli_real_escape_string($koneksi,$_GET['dusun'])."%'";

if(!empty($_GET['pendidikan']))
    $where[] = "PENDIDIKAN LIKE '%".mysqli_real_escape_string($koneksi,$_GET['pendidikan'])."%'";

$whereSQL = '';

if(count($where)>0){
    $whereSQL = 'WHERE '.implode(' AND ',$where);
}

/* ======================
   QUERY DATA UTAMA
====================== */
$sql = "
    SELECT * FROM penduduk
    $whereSQL
    ORDER BY 
        NO_KK ASC,
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
        NAMA_LGKP ASC
";
$query = mysqli_query($koneksi, $sql);

/* ======================
   REKAP DATA
====================== */
$qRekap = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total,
        SUM(JENIS_KELAMIN IN ('LAKI-LAKI','LK')) AS laki,
        SUM(JENIS_KELAMIN IN ('PEREMPUAN','PR')) AS perempuan
    FROM penduduk
    $whereSQL
");

$rekap = mysqli_fetch_assoc($qRekap);

// Fetch Wilayah Data for Filter
$qWilayahDusun = mysqli_query($koneksi, "SELECT * FROM wilayah_dusun ORDER BY nama ASC");
$wilayahData = [];
while ($d = mysqli_fetch_assoc($qWilayahDusun)) {
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
<title>Data Penduduk</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 p-2">

<div class="bg-white p-3 rounded shadow">

<h1 class="text-xl font-bold mb-2">Data Penduduk <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Klego') ?></h1>

<div class="flex gap-2 mb-3 items-center flex-wrap">
    <a href="index.php" class="bg-gray-600 text-white px-3 py-2 rounded text-sm font-medium">Dashboard</a>
    <a href="penduduk-input.php" class="bg-green-600 text-white px-3 py-2 rounded text-sm font-medium">+ Tambah</a>
    
    <form action="penduduk-import-proses.php" method="POST" enctype="multipart/form-data" class="flex gap-2 ml-2">
        <input type="file" name="file_csv" accept=".csv, .xlsx" class="border p-1 rounded bg-gray-50 text-sm" required>
        <button type="submit" name="import" class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 font-medium">Import File</button>
    </form>

    <a href="penduduk.php?reset=true" onclick="return confirm('⚠️ PERHATIAN: Apakah Anda yakin ingin mengosongkan / mereset SELURUH data penduduk? Semua data akan dihapus dari tabel!')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded ml-auto text-sm font-semibold shadow flex items-center gap-1">🗑️ Reset / Kosongkan Data</a>
</div>







<details class="bg-green-100 border rounded mb-3" onclose="">

<summary class="cursor-pointer font-bold px-3 py-2 bg-green-200">
🔍 Pencarian Penduduk
</summary>

<div class="p-3">

<form method="GET">

<div class="grid grid-cols-5 gap-2 text-sm">

<input type="text" name="nik"
placeholder="NIK"
value="<?= $_GET['nik'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="text" name="kk"
placeholder="No KK"
value="<?= $_GET['kk'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="text" name="nama"
placeholder="Nama"
value="<?= $_GET['nama'] ?? '' ?>"
class="border p-1 rounded text-sm">

<select name="jk" class="border p-1 rounded text-sm">
    <option value="">Semua JK</option>

    <option value="LK"
        <?= (($_GET['jk'] ?? '') == 'LK') ? 'selected' : '' ?>>
        LK
    </option>

    <option value="PR"
        <?= (($_GET['jk'] ?? '') == 'PR') ? 'selected' : '' ?>>
        PR
    </option>
</select>

<select name="agama" class="border p-1 rounded text-sm">
    <option value="">Agama</option>

    <option value="ISLAM" <?= (($_GET['agama'] ?? '') == 'ISLAM') ? 'selected' : '' ?>>
        ISLAM
    </option>

    <option value="KRISTEN" <?= (($_GET['agama'] ?? '') == 'KRISTEN') ? 'selected' : '' ?>>
        KRISTEN
    </option>

    <option value="KATOLIK" <?= (($_GET['agama'] ?? '') == 'KATOLIK') ? 'selected' : '' ?>>
        KATOLIK
    </option>

    <option value="HINDU" <?= (($_GET['agama'] ?? '') == 'HINDU') ? 'selected' : '' ?>>
        HINDU
    </option>

    <option value="BUDDHA" <?= (($_GET['agama'] ?? '') == 'BUDDHA') ? 'selected' : '' ?>>
        BUDDHA
    </option>
</select>

<select name="dusun" id="filter_dusun" class="border p-1 rounded text-sm">
    <option value="">Semua Dusun</option>
    <?php foreach($wilayahData as $id => $d): ?>
        <option value="<?= htmlspecialchars($d['nama']) ?>" <?= (($_GET['dusun'] ?? '') == $d['nama']) ? 'selected' : '' ?>><?= htmlspecialchars($d['nama']) ?></option>
    <?php endforeach; ?>
</select>

<select name="rw" id="filter_rw" class="border p-1 rounded text-sm">
    <option value="">Semua RW</option>
</select>

<select name="rt" id="filter_rt" class="border p-1 rounded text-sm">
    <option value="">Semua RT</option>
</select>

<select name="status" class="border p-1 rounded text-sm">
    <option value="">Status Kawin</option>

    <option value="KAWIN"
        <?= (($_GET['status'] ?? '') == 'KAWIN') ? 'selected' : '' ?>>
        KAWIN
    </option>

    <option value="BELUM KAWIN"
        <?= (($_GET['status'] ?? '') == 'BELUM KAWIN') ? 'selected' : '' ?>>
        BELUM KAWIN
    </option>

    <option value="CERAI HIDUP"
        <?= (($_GET['status'] ?? '') == 'CERAI HIDUP') ? 'selected' : '' ?>>
        CERAI HIDUP
    </option>

    <option value="CERAI MATI"
        <?= (($_GET['status'] ?? '') == 'CERAI MATI') ? 'selected' : '' ?>>
        CERAI MATI
    </option>
</select>

<input type="text" name="pekerjaan"
placeholder="Pekerjaan"
value="<?= $_GET['pekerjaan'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="text" name="tempat"
placeholder="Tempat Lahir"
value="<?= $_GET['tempat'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="date" name="tgl"
value="<?= $_GET['tgl'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="text" name="pendidikan"
placeholder="Pendidikan"
value="<?= $_GET['pendidikan'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="text" name="ayah"
placeholder="Nama Ayah"
value="<?= $_GET['ayah'] ?? '' ?>"
class="border p-1 rounded text-sm">

<input type="text" name="ibu"
placeholder="Nama Ibu"
value="<?= $_GET['ibu'] ?? '' ?>"
class="border p-1 rounded text-sm">

</div>


<div class="mt-3 flex gap-2">

<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
Cari
</button>

<a href="penduduk.php"
class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded text-sm">
Reset
</a>

</div>

</form>

</div>

<script>
const wilayahFilterData = <?= $wilayahJson ?>;
const filterDusunSelect = document.getElementById('filter_dusun');
const filterRwSelect = document.getElementById('filter_rw');
const filterRtSelect = document.getElementById('filter_rt');

const filterSelectedRw = "<?= $_GET['rw'] ?? '' ?>";
const filterSelectedRt = "<?= $_GET['rt'] ?? '' ?>";

const filterAllRws = [];
const filterAllRts = [];

for(let id in wilayahFilterData) {
    const dName = wilayahFilterData[id].nama;
    for(let rw in wilayahFilterData[id].rws) {
        filterAllRws.push({rw: rw, dusun: dName});
        wilayahFilterData[id].rws[rw].forEach(rt => {
            filterAllRts.push({rt: rt, rw: rw, dusun: dName});
        });
    }
}

function updateFilterDropdowns(trigger) {
    const dVal = filterDusunSelect.value;
    const rwVal = filterRwSelect.value;
    const rtVal = filterRtSelect.value;

    if (trigger === 'rt' && rtVal) {
        const t = filterAllRts.find(i => i.rt === rtVal);
        if (t) {
            filterDusunSelect.value = t.dusun;
            filterRwSelect.innerHTML = '<option value="">Semua RW</option>';
            filterAllRws.filter(i => i.dusun === t.dusun).forEach(i => filterRwSelect.add(new Option(i.rw, i.rw)));
            filterRwSelect.value = t.rw;
            filterRtSelect.innerHTML = '<option value="">Semua RT</option>';
            filterAllRts.filter(i => i.rw === t.rw).forEach(i => filterRtSelect.add(new Option(i.rt, i.rt)));
            filterRtSelect.value = t.rt;
            return;
        }
    }
    
    if (trigger === 'rw' && rwVal) {
        const t = filterAllRws.find(i => i.rw === rwVal);
        if (t) {
            filterDusunSelect.value = t.dusun;
            filterRwSelect.innerHTML = '<option value="">Semua RW</option>';
            filterAllRws.filter(i => i.dusun === t.dusun).forEach(i => filterRwSelect.add(new Option(i.rw, i.rw)));
            filterRwSelect.value = t.rw;
            filterRtSelect.innerHTML = '<option value="">Semua RT</option>';
            filterAllRts.filter(i => i.rw === t.rw).forEach(i => filterRtSelect.add(new Option(i.rt, i.rt)));
            if (filterAllRts.find(i => i.rt === rtVal && i.rw === t.rw)) {
                filterRtSelect.value = rtVal;
            }
            return;
        }
    }

    filterRwSelect.innerHTML = '<option value="">Semua RW</option>';
    const validRws = dVal ? filterAllRws.filter(i => i.dusun === dVal) : filterAllRws;
    validRws.forEach(i => filterRwSelect.add(new Option(i.rw, i.rw)));
    if (validRws.find(i => i.rw === rwVal)) filterRwSelect.value = rwVal;

    filterRtSelect.innerHTML = '<option value="">Semua RT</option>';
    const validRts = filterAllRts.filter(i => {
        if (dVal && i.dusun !== dVal) return false;
        if (filterRwSelect.value && i.rw !== filterRwSelect.value) return false;
        return true;
    });
    validRts.forEach(i => filterRtSelect.add(new Option(i.rt, i.rt)));
    if (validRts.find(i => i.rt === rtVal)) filterRtSelect.value = rtVal;
}

filterDusunSelect.addEventListener('change', () => updateFilterDropdowns('dusun'));
filterRwSelect.addEventListener('change', () => updateFilterDropdowns('rw'));
filterRtSelect.addEventListener('change', () => updateFilterDropdowns('rt'));

updateFilterDropdowns('init');

if (filterSelectedRt) {
    filterRtSelect.value = filterSelectedRt;
    updateFilterDropdowns('rt');
} else if (filterSelectedRw) {
    filterRwSelect.value = filterSelectedRw;
    updateFilterDropdowns('rw');
} else if (filterDusunSelect.value) {
    updateFilterDropdowns('dusun');
}
</script>

</details>





































<div class="grid grid-cols-3 gap-3 mb-3 text-sm">
    <div class="bg-blue-100 p-3 rounded">
        Total<br>
        <b><?= $rekap['total'] ?></b>
    </div>

    <div class="bg-green-100 p-3 rounded">
        Laki-laki<br>
        <b><?= $rekap['laki'] ?></b>
    </div>

    <div class="bg-pink-100 p-3 rounded">
        Perempuan<br>
        <b><?= $rekap['perempuan'] ?></b>
    </div>
</div>

<div class="overflow-x-auto max-h-[70vh]">
<table class="w-full border text-xs">
<thead class="bg-gray-200 sticky top-0">
<tr>
    <th class="border border-gray-400 p-2">No</th>
            <th class="border border-gray-400 p-2">NIK</th>
            <th class="border border-gray-400 p-2">No KK</th>
            <th class="border border-gray-400 p-2 min-w-[140px]">Nama Lengkap</th>
            <th class="border border-gray-400 p-2">JK</th>
            <th class="border border-gray-400 p-2">Tempat Lahir</th>
            <th class="border border-gray-400 p-2">Tanggal Lahir</th>
            <th class="border border-gray-400 p-2">Usia</th>
            <th class="border border-gray-400 p-2">Dusun</th>
            <th class="border border-gray-400 p-2">RT/RW</th>
            <th class="border border-gray-400 p-2">SHDK</th>
            <th class="border border-gray-400 p-2">Agama</th>
            <th class="border border-gray-400 p-2">Pendidikan</th>
            <th class="border border-gray-400 p-2 min-w-[140px]">Pekerjaan</th>
            <th class="border border-gray-400 p-2">No Akte Lahir</th>
            <th class="border border-gray-400 p-2">Status Kawin</th>
            <th class="border border-gray-400 p-2">No Akte Kawin</th>
            <th class="border border-gray-400 p-2">No Akte Cerai</th>
            <th class="border border-gray-400 p-2">Nama Ayah</th>
            <th class="border border-gray-400 p-2">Nama Ibu</th>
            <th class="border border-gray-400 p-2">Bantuan</th>
            <th class="border border-gray-400 p-2">Aksi</th>


            <th class="border border-gray-400 p-2">Cetak</th>


</tr>
</thead>

<tbody>
<?php
$no = 1;
$lastKK = '';

while ($r = mysqli_fetch_assoc($query)) {

    $tgl = '-';
    $usia = '-';

    if (!empty($r['TGL_LAHIR'])) {
        $lahir = DateTime::createFromFormat('Y-m-d', $r['TGL_LAHIR']);
        if ($lahir) {
            $tgl = $lahir->format('d-m-Y');
            $usia = (new DateTime())->diff($lahir)->y . ' th';
        }
    }

    if ($r['NO_KK'] != $lastKK) {
        $lastKK = $r['NO_KK'];
        $no = 1;
        echo "<tr class='bg-blue-100 font-bold'><td colspan='23'>🏠 NO KK : {$r['NO_KK']}</td></tr>";
    }
?>
<tr class="hover:bg-gray-100">
            <td class="border border-gray-400 p-2"><?= $no++ ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NIK'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NO_KK'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NAMA_LGKP'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['JENIS_KELAMIN'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['TMPT_LAHIR'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($tgl ?? ($r['TGL_LAHIR'] ?? '')) ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($usia ?? ($r['USIA'] ?? '')) ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['DUSUN'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars(trim(($r['RT'] ?? '') . '/' . ($r['RW'] ?? ''))) ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['SHDK'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['AGAMA'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['PENDIDIKAN'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['PEKERJAAN'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NO_AKTA_LAHIR'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['STATUS_KAWIN'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NO_AKTA_KAWIN'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NO_AKTA_CERAI'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NAMA_AYAH'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['NAMA_IBU'] ?? '') ?></td>
            <td class="border border-gray-400 p-2"><?= htmlspecialchars($r['BANTUAN'] ?? '') ?></td>

            <td class="border border-gray-400 p-2 text-center whitespace-nowrap">
            <a href="penduduk-input.php?edit=<?= urlencode($r['NIK']) ?>"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">
                Edit
            </a>

            <a href="penduduk.php?hapus=<?= urlencode($r['NIK']) ?>"
            onclick="return confirm('Yakin ingin menghapus data ini?')"
            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                Hapus
            </a>
            </td>

            <td class="border border-gray-400 p-2 text-center">

            <?php if ($r['SHDK'] == 'KEPALA KELUARGA') { ?>

            <a href="cetak-kk.php?kk=<?= urlencode($r['NO_KK']) ?>"
            target="_blank"
            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
            Cetak
            </a>

            <?php } ?>

            </td>



</td>

</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
</body>
</html>
