<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

include 'config/database.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? $_SESSION['username'] ?? '');
$password_baru = $_POST['password_baru'] ?? '';

if ($password_baru == '' || $username == '') {
  header("Location: admin/index.php?error=kosong");
  exit;
}

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

mysqli_query($koneksi,
  "UPDATE admin SET password='$hash' WHERE username='$username'"
);

header("Location: login.php?msg=password_updated");
exit;
