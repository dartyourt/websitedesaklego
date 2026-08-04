<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $tahun    = $_POST['tahun'];
    $jenis    = $_POST['jenis'];
    $kelompok = $_POST['kelompok'];
    $kategori = $_POST['kategori'];
    $jumlah   = $_POST['jumlah'];

    mysqli_query($conn, "
      INSERT INTO keuangan (tahun, jenis, kelompok, kategori, jumlah)
      VALUES ('$tahun','$jenis','$kelompok','$kategori','$jumlah')
    ");

    header("Location: keuangan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Keuangan</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">

<h2 class="font-bold text-lg mb-4">Tambah Data Keuangan</h2>

<form method="post" class="space-y-3">

  <input type="number" name="tahun" value="<?= date('Y') ?>"
    class="border p-2 w-full rounded" required>

  <select name="jenis" class="border p-2 w-full rounded" required>
    <option value="">-- Pilih Jenis --</option>
    <option value="apbdes">APBDes</option>
    <option value="realisasi">Realisasi</option>
  </select>

  <select name="kelompok" class="border p-2 w-full rounded" required>
    <option value="">-- Pilih Kelompok --</option>
    <option value="pendapatan">Pendapatan</option>
    <option value="belanja">Belanja</option>
    <option value="pembiayaan">Pembiayaan</option>
  </select>

  <select name="ketegori" class="border p-2 w-full rounded" required>
    <option value="">-- Pilih sumber dana --</option>
    <option value="PAD">PAD</option>
    <option value="ADD">ADD</option>
    <option value="DD">DD</option>
    <option value="PBH">PBH</option>
    <option value="PBP">PBP</option>
    <option value="PBK">PBK</option>
    <option value="DLL">DLL</option>
  </select>
  <input type="text" name="kategori" placeholder="Kategori"
    class="border p-2 w-full rounded" required>

  <input type="number" name="jumlah" placeholder="Jumlah (Rp)"
    class="border p-2 w-full rounded" required>

  <div class="flex justify-between">
    <a href="keuangan.php"
       class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
       Batal
    </a>

    <button type="submit" name="simpan"
       class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
       Simpan
    </button>
  </div>

</form>
</div>

</body>
</html>
