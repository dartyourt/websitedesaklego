<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengaturan Profil Desa</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 p-4">

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">⚙️ Pengaturan Profil Desa</h1>
        <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Kembali ke Dashboard</a>
    </div>

    <?php if(isset($_GET['pesan'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?= htmlspecialchars($_GET['pesan']) ?>
        </div>
    <?php endif; ?>

    <form action="profil-proses.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-1">Nama Desa</label>
                <input type="text" name="nama_desa" value="<?= htmlspecialchars($APP_PROFIL['nama_desa']) ?>" class="w-full border p-2 rounded" required>
            </div>
            
            <div>
                <label class="block font-bold mb-1">Kecamatan</label>
                <input type="text" name="kecamatan" value="<?= htmlspecialchars($APP_PROFIL['kecamatan']) ?>" class="w-full border p-2 rounded" required>
            </div>
            
            <div>
                <label class="block font-bold mb-1">Kabupaten / Kota</label>
                <input type="text" name="kabupaten" value="<?= htmlspecialchars($APP_PROFIL['kabupaten']) ?>" class="w-full border p-2 rounded" required>
            </div>
            
            <div>
                <label class="block font-bold mb-1">Provinsi</label>
                <input type="text" name="provinsi" value="<?= htmlspecialchars($APP_PROFIL['provinsi']) ?>" class="w-full border p-2 rounded" required>
            </div>
            
            <div>
                <label class="block font-bold mb-1">Kode Pos</label>
                <input type="text" name="kode_pos" value="<?= htmlspecialchars($APP_PROFIL['kode_pos']) ?>" class="w-full border p-2 rounded">
            </div>
            
            <div>
                <label class="block font-bold mb-1">Telepon / HP</label>
                <input type="text" name="telepon" value="<?= htmlspecialchars($APP_PROFIL['telepon']) ?>" class="w-full border p-2 rounded">
            </div>
            
            <div class="col-span-2">
                <label class="block font-bold mb-1">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($APP_PROFIL['email']) ?>" class="w-full border p-2 rounded">
            </div>
            
            <div class="col-span-2">
                <label class="block font-bold mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" class="w-full border p-2 rounded" required><?= htmlspecialchars($APP_PROFIL['alamat']) ?></textarea>
            </div>
            
            <div class="col-span-2 border p-4 bg-gray-50 rounded">
                <label class="block font-bold mb-1">Ganti Logo Desa (Opsional)</label>
                <div class="flex items-center gap-4">
                    <img src="../assets/img/<?= htmlspecialchars($APP_PROFIL['logo']) ?>" alt="Logo Lama" class="w-16 h-16 object-contain bg-white border p-1 rounded">
                    <input type="file" name="logo" accept="image/png, image/jpeg" class="w-full border p-2 rounded bg-white">
                </div>
                <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti logo. Gunakan format PNG transparan untuk hasil terbaik.</p>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" name="simpan" class="bg-blue-600 text-white px-6 py-3 rounded font-bold hover:bg-blue-700 w-full">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

</body>
</html>
