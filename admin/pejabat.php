<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pejabat WHERE id=$id");
    header("Location: pejabat.php");
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM pejabat ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pejabat Surat</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Data Pejabat Surat</h1>
        <div class="space-x-2">
            <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            <a href="pejabat-form.php" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Pejabat</a>
        </div>
    </div>
    
    <table class="w-full border-collapse border">
        <tr class="bg-gray-200">
            <th class="border p-2">Urutan</th>
            <th class="border p-2">Nama Lengkap</th>
            <th class="border p-2">Jabatan</th>
            <th class="border p-2">NIP</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($q)): ?>
        <tr class="text-center">
            <td class="border p-2"><?= $row['urutan'] ?></td>
            <td class="border p-2 text-left"><?= htmlspecialchars($row['nama']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($row['jabatan']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($row['nip'] ?? '-') ?></td>
            <td class="border p-2">
                <?= $row['status'] == 1 ? '<span class="text-green-600 font-bold">Aktif</span>' : '<span class="text-red-600">Nonaktif</span>' ?>
            </td>
            <td class="border p-2">
                <a href="pejabat-form.php?id=<?= $row['id'] ?>" class="text-yellow-600 hover:underline">Edit</a> | 
                <a href="pejabat.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" class="text-red-600 hover:underline">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-400 rounded text-sm text-yellow-800">
        <strong>PENTING:</strong> Kode Jabatan harus diisi sesuai kebutuhan aplikasi cetak surat (Contoh: <code>kepala</code> atau <code>sekdes</code>).
    </div>
</div>
</body>
</html>
