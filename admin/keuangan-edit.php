<?php
include '../config/database.php';

$id = $_GET['id'];
$q = mysqli_query($conn,"SELECT * FROM keuangan WHERE id='$id'");
$d = mysqli_fetch_assoc($q);

if ($_POST) {
  mysqli_query($conn,"
    UPDATE keuangan SET
    tahun='$_POST[tahun]',
    jenis='$_POST[jenis]',
    kelompok='$_POST[kelompok]',
    kategori='$_POST[kategori]',
    jumlah='$_POST[jumlah]'
    WHERE id='$id'
  ");
  header("Location: keuangan.php");
}
?>

<form method="post" class="max-w-md mx-auto mt-10 bg-white p-6 shadow rounded space-y-3">
  <h2 class="font-bold text-lg">Edit Keuangan</h2>

  <input name="tahun" value="<?= $d['tahun'] ?>" class="border p-2 w-full">
  <input name="kategori" value="<?= $d['kategori'] ?>" class="border p-2 w-full">
  <input name="jumlah" value="<?= $d['jumlah'] ?>" class="border p-2 w-full">

  <button class="bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
</form>
