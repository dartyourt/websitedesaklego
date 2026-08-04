<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

$id = $_GET['id'] ?? null;
$data = ['nama'=>'', 'jabatan'=>'', 'nip'=>'', 'urutan'=>'0', 'status'=>'1'];

if ($id) {
    $q = mysqli_query($conn, "SELECT * FROM pejabat WHERE id=".(int)$id);
    if($r = mysqli_fetch_assoc($q)) $data = $r;
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, strtolower(trim($_POST['jabatan'])));
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $urutan = (int)$_POST['urutan'];
    $status = (int)$_POST['status'];

    if ($id) {
        mysqli_query($conn, "UPDATE pejabat SET nama='$nama', jabatan='$jabatan', nip='$nip', urutan=$urutan, status=$status WHERE id=".(int)$id);
    } else {
        mysqli_query($conn, "INSERT INTO pejabat (nama, jabatan, nip, urutan, status) VALUES ('$nama', '$jabatan', '$nip', $urutan, $status)");
    }
    header("Location: pejabat.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Pejabat Surat</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4"><?= $id ? 'Edit' : 'Tambah' ?> Pejabat Surat</h1>
    
    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block font-bold mb-1">Nama Lengkap (beserta Gelar)</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" class="w-full border p-2 rounded" required>
        </div>
        <div>
            <label class="block font-bold mb-1">Kode Jabatan</label>
            <input type="text" name="jabatan" value="<?= htmlspecialchars($data['jabatan']) ?>" class="w-full border p-2 rounded" required placeholder="kepala / sekdes / kaur">
            <p class="text-xs text-gray-500 mt-1">Harus huruf kecil tanpa spasi (misal: kepala, sekdes)</p>
        </div>
        <div>
            <label class="block font-bold mb-1">NIP (Opsional)</label>
            <input type="text" name="nip" value="<?= htmlspecialchars($data['nip'] ?? '') ?>" class="w-full border p-2 rounded" placeholder="Kosongkan jika tidak ada">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-1">Urutan Tampil</label>
                <input type="number" name="urutan" value="<?= htmlspecialchars($data['urutan']) ?>" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-bold mb-1">Status</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <a href="pejabat.php" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
            <button type="submit" name="simpan" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
</body>
</html>
