<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- HEADER -->
<div class="bg-blue-700 text-white p-4 flex justify-between">
  <h1 class="font-bold">Admin <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h1>
  <div>
    <?= $_SESSION['nama']; ?> |
    <a href="../logout.php" class="underline">Logout</a>
  </div>
</div>

<!-- MENU -->
<div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

  <a href="penduduk.php" class="bg-white p-6 rounded shadow hover:bg-blue-50">
    <h2 class="text-lg font-semibold">Penduduk</h2>
    <p class="text-sm text-gray-600">Kelola data penduduk</p>
  </a>

  <a href="keuangan.php" class="bg-white p-6 rounded shadow hover:bg-blue-50">
    <h2 class="text-lg font-semibold">Keuangan</h2>
    <p class="text-sm text-gray-600">APBDes & Realisasi</p>
  </a>

  <a href="laporan.php" class="bg-white p-6 rounded shadow hover:bg-blue-50">
    <h2 class="text-lg font-semibold">Laporan</h2>
    <p class="text-sm text-gray-600">Laporan Desa</p>
  </a>

</div>

</body>
</html>
