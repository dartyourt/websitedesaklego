<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$tahun = $_GET['tahun'] ?? date('Y');

$data = mysqli_query($conn, "
  SELECT * FROM keuangan
  WHERE tahun='$tahun'
  ORDER BY tahun DESC, jenis, kelompok
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Keuangan</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto mt-8 bg-white p-6 rounded shadow">

  <!-- HEADER -->
<div class="bg-gray-100 border-b">
  <div class="max-w-6xl mx-auto px-4 py-4 flex gap-3">
    
    <a href="index.php"
       class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
      ← Kembali ke Dashboard
    </a>

    <a href="keuangan-tambah.php"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      + Tambah Keuangan
    </a>
  </div>

  <!-- FILTER TAHUN -->
  <form method="get" class="mb-4">
    <label class="font-semibold">Tahun:</label>
    <select name="tahun" onchange="this.form.submit()" class="border px-2 py-1 rounded">
      <?php for($y=date('Y');$y>=2020;$y--): ?>
        <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>>
          <?= $y ?>
        </option>
      <?php endfor; ?>
    </select>
  </form>

  <!-- TABEL -->
  <div class="overflow-x-auto">
  <table class="w-full border text-sm">
    <thead class="bg-gray-200">
      <tr>
        <th class="border px-2 py-2">No</th>
        <th class="border px-2">Tahun</th>
        <th class="border px-2">Jenis</th>
        <th class="border px-2">Kelompok</th>
        <th class="border px-2">Kategori</th>
        <th class="border px-2 text-right">Jumlah (Rp)</th>
        <th class="border px-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
      <tr class="hover:bg-gray-50">
        <td class="border px-2 text-center"><?= $no++ ?></td>
        <td class="border px-2"><?= $row['tahun'] ?></td>
        <td class="border px-2 capitalize"><?= $row['jenis'] ?></td>
        <td class="border px-2 capitalize"><?= $row['kelompok'] ?></td>
        <td class="border px-2"><?= $row['kategori'] ?></td>
        <td class="border px-2 text-right">
          <?= number_format($row['jumlah'],0,',','.') ?>
        </td>
        <td class="border px-2 text-center">
          <a href="keuangan_edit.php?id=<?= $row['id'] ?>"
             class="text-blue-600 hover:underline">Edit</a>
          |
          <a href="keuangan_hapus.php?id=<?= $row['id'] ?>"
             onclick="return confirm('Hapus data ini?')"
             class="text-red-600 hover:underline">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>

</div>

</body>
</html>
