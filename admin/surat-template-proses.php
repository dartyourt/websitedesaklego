<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $nama_surat = mysqli_real_escape_string($koneksi, $_POST['nama_surat']);
    $kode_surat = mysqli_real_escape_string($koneksi, $_POST['kode_surat']);
    $konten_html = mysqli_real_escape_string($koneksi, $_POST['konten_html']);
    
    if ($action === 'add') {
        mysqli_query($koneksi, "INSERT INTO surat_template (nama_surat, kode_surat, konten_html) VALUES ('$nama_surat', '$kode_surat', '$konten_html')");
    } else {
        $id = (int)$_POST['id'];
        mysqli_query($koneksi, "UPDATE surat_template SET nama_surat='$nama_surat', kode_surat='$kode_surat', konten_html='$konten_html' WHERE id=$id");
    }
    header("Location: surat-template.php");
    exit;
}

if ($action === 'delete') {
    $id = (int)$_GET['id'];
    mysqli_query($koneksi, "DELETE FROM surat_template WHERE id=$id");
    header("Location: surat-template.php");
    exit;
}

header("Location: surat-template.php");
exit;
