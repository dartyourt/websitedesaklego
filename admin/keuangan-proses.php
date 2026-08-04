<?php
include '../config/database.php';

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
