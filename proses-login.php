<?php
session_start();
include 'config/database.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username == '' || $password == '') {
  header("Location: login.php?error=kosong");
  exit;
}

$clean_username = mysqli_real_escape_string($koneksi, $username);
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$clean_username'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  header("Location: login.php?error=username");
  exit;
}

// Cek password hash bcrypt (atau fallback ke plaintext dan otomatis upgrade ke bcrypt)
$password_match = false;
if (password_verify($password, $data['password'])) {
    $password_match = true;
} elseif ($password === $data['password']) {
    $password_match = true;
    // Otomatis upgrade password plaintext ke bcrypt hash agar aman ke depannya
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    $admin_id = (int)$data['id'];
    mysqli_query($koneksi, "UPDATE admin SET password='$new_hash' WHERE id=$admin_id");
}

if (!$password_match) {
  header("Location: login.php?error=password");
  exit;
}

$_SESSION['login'] = true;
$_SESSION['nama']  = $data['nama'];
$_SESSION['username'] = $data['username'];


header("Location: admin/index.php");
exit;

