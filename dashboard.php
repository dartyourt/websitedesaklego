<?php
session_start();

// Cek login
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- HEADER -->
<div class="bg-blue-700 text-white p-4 flex justify-between">
  <h1 class="font-bold">Admin <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h1>
  <div>
    Halo, <b><?= $_SESSION['nama']; ?></b>
    <a href="logout.php" class="ml-4 underline">Logout</a>
  </div>
</div>

<!-- KONTEN -->
<div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">

  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-semibold">Data Penduduk</h2>
    <p class="text-sm text-gray-600">Kelola data penduduk desa</p>
    <a href="data-penduduk.php" class="text-blue-600 text-sm">Buka</a>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-semibold">Surat Menyurat</h2>
    <p class="text-sm text-gray-600">Kelola surat masuk & keluar</p>
    <a href="surat.php" class="text-blue-600 text-sm">Buka</a>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-semibold">Laporan</h2>
    <p class="text-sm text-gray-600">Laporan desa</p>
    <a href="laporan.php" class="text-blue-600 text-sm">Buka</a>
  </div>

</div>

</body>
</html>
