<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Menyurat <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="bg-blue-700 text-white p-4 flex justify-between items-center">
    <h1 class="font-bold text-lg">Surat Menyurat <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h1>
    <div class="space-x-4">
        <a href="surat-template.php" class="bg-blue-800 hover:bg-blue-900 px-4 py-2 rounded text-sm font-bold">⚙️ Kelola Template Surat</a>
        <a href="index.php" class="text-sm hover:underline">← Kembali</a>
    </div>
</div>

<!-- KONTEN -->
<div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">

  <!-- SK Umum -->
  <a href="surat/sk-umum.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📄 Surat Keterangan Umum</h2>
    <p class="text-sm text-gray-600">Surat keterangan umum desa</p>
  </a>

  <!-- SKTM -->
  <a href="surat/sktm.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📄 Surat Keterangan Tidak Mampu</h2>
    <p class="text-sm text-gray-600">SKTM</p>
  </a>

  <!-- SK Domisili Desa -->
  <a href="Surat/sk-domisili-lokal.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📄 SK Domisili <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></h2>
    <p class="text-sm text-gray-600">Domisili warga <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></p>
  </a>

  <!-- SK Domisili Luar Desa -->
  <a href="surat/sk-domisili.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📄 SK Domisili Luar Desa</h2>
    <p class="text-sm text-gray-600">Domisili warga luar desa (Manual)</p>
  </a>

  <!-- SK Usaha -->
  <a href="surat/sk-usaha.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📄 Surat Keterangan Usaha</h2>
    <p class="text-sm text-gray-600">SKU</p>
  </a>

  <!-- F.121 Pengantar -->
  <a href="surat/sp-umum.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📑 F.121 Pengantar Umum</h2>
    <p class="text-sm text-gray-600">Surat Pengantar</p>
  </a>

  <!-- F.121 Pengantar KTP-->
  <a href="surat/sp-ktp.php" class="bg-white p-6 rounded shadow hover:bg-blue-50 block">
    <h2 class="font-bold text-lg">📑 F.121 Pengantar KTP</h2>
    <p class="text-sm text-gray-600">Pengantar KTP</p>
  </a>

  <?php
  // Looping untuk template dinamis
  $qTpl = mysqli_query($koneksi, "SELECT * FROM surat_template ORDER BY id DESC");
  while ($tpl = mysqli_fetch_assoc($qTpl)):
  ?>
  <a href="surat-cetak-dinamis.php?template_id=<?= $tpl['id'] ?>" class="bg-green-50 border-green-200 border p-6 rounded shadow hover:bg-green-100 block">
    <div class="flex justify-between items-start">
        <h2 class="font-bold text-lg text-green-800">📄 <?= htmlspecialchars($tpl['nama_surat']) ?></h2>
        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Kustom</span>
    </div>
    <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($tpl['kode_surat'] ?: 'Tanpa Kode') ?></p>
  </a>
  <?php endwhile; ?>

</div>

</body>
</html>
