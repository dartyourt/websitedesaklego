<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

include 'config/database.php';

$username = mysqli_real_escape_string($koneksi, trim($_POST['username'] ?? ''));
$password = $_POST['password'] ?? '';
$nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama'] ?? ''));

if ($username == '' || $password == '' || $nama == '') {
  header("Location: admin/admin.php?error=kosong");
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

mysqli_query($koneksi,
  "INSERT INTO admin (username, password, nama)
   VALUES ('$username', '$hash', '$nama')"
);

header("Location: admin/admin.php?success=tambah");
exit;
