<?php
session_start();
include '../config/database.php';
require_once __DIR__ . '/../config/upload_helper.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

$id = $_GET['id'] ?? null;
$data = ['nama'=>'', 'jabatan'=>'', 'foto'=>''];

if ($id) {
    $q = mysqli_query($conn, "SELECT * FROM perangkat WHERE id=".(int)$id);
    if($r = mysqli_fetch_assoc($q)) $data = $r;
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    
    $fotoQuery = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        [$uploaded, $fotoName] = upload_image($_FILES['foto'], __DIR__ . '/../uploads/perangkat', 'perangkat_');
        if (!$uploaded) {
            $uploadError = $fotoName;
        } else {
            $fotoQuery = ", foto='$fotoName'";
        
        // Hapus foto lama jika update
        if($id && $data['foto'] != '' && file_exists("../uploads/perangkat/".$data['foto'])) {
            unlink("../uploads/perangkat/".$data['foto']);
        }
        }
    }

    if (!empty($uploadError)) {
        // Tetap di halaman form agar pengguna tahu file mana yang bermasalah.
    } elseif ($id) {
        mysqli_query($conn, "UPDATE perangkat SET nama='$nama', jabatan='$jabatan' $fotoQuery WHERE id=".(int)$id);
    } else {
        $fotoBaru = isset($fotoName) ? $fotoName : '';
        mysqli_query($conn, "INSERT INTO perangkat (nama, jabatan, foto) VALUES ('$nama', '$jabatan', '$fotoBaru')");
    }
    header("Location: perangkat.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Perangkat Desa</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4"><?= $id ? 'Edit' : 'Tambah' ?> Perangkat Desa</h1>
    <?php if (!empty($uploadError)): ?><div class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700"><?= htmlspecialchars($uploadError) ?></div><?php endif; ?>
    
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block font-bold mb-1">Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" class="w-full border p-2 rounded" required>
        </div>
        <div>
            <label class="block font-bold mb-1">Jabatan</label>
            <input type="text" name="jabatan" value="<?= htmlspecialchars($data['jabatan']) ?>" class="w-full border p-2 rounded" required placeholder="Contoh: Kepala Desa">
        </div>
        <div>
            <label class="block font-bold mb-1">Foto Perangkat</label>
            <?php if($data['foto']): ?>
                <div class="mb-2">
                    <img src="../uploads/perangkat/<?= $data['foto'] ?>" class="h-20 object-contain border p-1 bg-gray-50">
                </div>
            <?php endif; ?>
            <input type="file" name="foto" accept="image/*" class="w-full border p-2 rounded">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
        </div>
        
        <div class="flex justify-end gap-2 mt-4">
            <a href="perangkat.php" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
            <button type="submit" name="simpan" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
</body>
</html>
