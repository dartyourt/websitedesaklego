<?php
session_start();
include '../config/database.php';
require_once __DIR__ . '/../config/upload_helper.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

if (isset($_POST['simpan'])) {
    $logoUploadError = '';
    $nama_desa = mysqli_real_escape_string($conn, $_POST['nama_desa']);
    $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
    $kabupaten = mysqli_real_escape_string($conn, $_POST['kabupaten']);
    $provinsi  = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $kode_pos  = mysqli_real_escape_string($conn, $_POST['kode_pos']);
    $telepon   = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat    = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    // Proses Logo
    $logo_query = "";
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        [$uploaded, $nama_baru] = upload_image($_FILES['logo'], __DIR__ . '/../assets/img', 'logo_desa_');
        if ($uploaded) {
            $logo_query = ", logo='$nama_baru'";
        } else {
            $logoUploadError = $nama_baru;
        }
    }
    
    $query = "UPDATE profil_desa SET 
                nama_desa='$nama_desa', 
                kecamatan='$kecamatan', 
                kabupaten='$kabupaten', 
                provinsi='$provinsi', 
                kode_pos='$kode_pos', 
                telepon='$telepon', 
                email='$email', 
                alamat='$alamat' 
                $logo_query 
              WHERE id=1";
              
    if(mysqli_query($conn, $query)){
        $pesan = $logoUploadError ? 'Profil diperbarui, tetapi logo tidak diunggah: ' . $logoUploadError : 'Profil berhasil diperbarui!';
        header("Location: profil.php?pesan=" . urlencode($pesan));
    } else {
        header("Location: profil.php?pesan=Gagal memperbarui profil: " . mysqli_error($conn));
    }
}
?>
