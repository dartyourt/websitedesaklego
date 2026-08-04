<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'add_dusun') {
    $nama = strtoupper(mysqli_real_escape_string($koneksi, $_POST['nama_dusun']));
    if (!empty($nama)) {
        mysqli_query($koneksi, "INSERT INTO wilayah_dusun (nama) VALUES ('$nama')");
    }
    header("Location: wilayah.php");
    exit;
}

if ($action === 'del_dusun') {
    $id = (int)$_GET['id'];
    mysqli_query($koneksi, "DELETE FROM wilayah_dusun WHERE id=$id");
    // Cascading delete for RW and RT
    $rws = mysqli_query($koneksi, "SELECT id FROM wilayah_rw WHERE dusun_id=$id");
    while($rw = mysqli_fetch_assoc($rws)) {
        mysqli_query($koneksi, "DELETE FROM wilayah_rt WHERE rw_id={$rw['id']}");
    }
    mysqli_query($koneksi, "DELETE FROM wilayah_rw WHERE dusun_id=$id");
    header("Location: wilayah.php");
    exit;
}

if ($action === 'add_rw') {
    $dusun_id = (int)$_POST['dusun_id'];
    $rw = mysqli_real_escape_string($koneksi, $_POST['rw']);
    if (!empty($rw) && $dusun_id > 0) {
        mysqli_query($koneksi, "INSERT INTO wilayah_rw (dusun_id, rw) VALUES ($dusun_id, '$rw')");
    }
    header("Location: wilayah.php");
    exit;
}

if ($action === 'del_rw') {
    $id = (int)$_GET['id'];
    mysqli_query($koneksi, "DELETE FROM wilayah_rw WHERE id=$id");
    mysqli_query($koneksi, "DELETE FROM wilayah_rt WHERE rw_id=$id");
    header("Location: wilayah.php");
    exit;
}

if ($action === 'add_rt') {
    $rw_id = (int)$_POST['rw_id'];
    $rt = mysqli_real_escape_string($koneksi, $_POST['rt']);
    if (!empty($rt) && $rw_id > 0) {
        mysqli_query($koneksi, "INSERT INTO wilayah_rt (rw_id, rt) VALUES ($rw_id, '$rt')");
    }
    header("Location: wilayah.php");
    exit;
}

if ($action === 'del_rt') {
    $id = (int)$_GET['id'];
    mysqli_query($koneksi, "DELETE FROM wilayah_rt WHERE id=$id");
    header("Location: wilayah.php");
    exit;
}

header("Location: wilayah.php");
exit;
