<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    // Hapus foto lama
    $qFoto = mysqli_query($conn, "SELECT foto FROM perangkat WHERE id=$id");
    if($rFoto = mysqli_fetch_assoc($qFoto)){
        if(file_exists("../uploads/perangkat/".$rFoto['foto']) && $rFoto['foto'] != '') {
            unlink("../uploads/perangkat/".$rFoto['foto']);
        }
    }
    mysqli_query($conn, "DELETE FROM perangkat WHERE id=$id");
    header("Location: perangkat.php");
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM perangkat ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Perangkat Desa</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Data Perangkat Desa (Aparatur)</h1>
        <div class="space-x-2">
            <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            <a href="perangkat-form.php" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Perangkat</a>
        </div>
    </div>
    
    <table class="w-full border-collapse border">
        <tr class="bg-gray-200">
            <th class="border p-2">Foto</th>
            <th class="border p-2">Nama Lengkap</th>
            <th class="border p-2">Jabatan</th>
            <th class="border p-2">Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($q)): ?>
        <tr class="text-center">
            <td class="border p-2">
                <?php if($row['foto']): ?>
                    <img src="../uploads/perangkat/<?= $row['foto'] ?>" class="h-16 object-contain mx-auto">
                <?php else: ?>
                    <span class="text-gray-400">Tidak ada</span>
                <?php endif; ?>
            </td>
            <td class="border p-2 text-left"><?= htmlspecialchars($row['nama']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($row['jabatan']) ?></td>
            <td class="border p-2">
                <a href="perangkat-form.php?id=<?= $row['id'] ?>" class="text-yellow-600 hover:underline">Edit</a> | 
                <a href="perangkat.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" class="text-red-600 hover:underline">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <div class="mt-4 p-4 bg-blue-50 border border-blue-400 rounded text-sm text-blue-800">
        Data di atas akan ditampilkan pada halaman utama website.
    </div>
</div>
</body>
</html>
