<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

if (isset($_POST['simpan'])) {
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
        $nama_file = $_FILES['logo']['name'];
        $tmp_file  = $_FILES['logo']['tmp_name'];
        $ext       = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_baru = "logo_desa_" . time() . "." . $ext;
        $path      = "../assets/img/" . $nama_baru;
        
        if (move_uploaded_file($tmp_file, $path)) {
            $logo_query = ", logo='$nama_baru'";
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
        header("Location: profil.php?pesan=Profil berhasil diperbarui!");
    } else {
        header("Location: profil.php?pesan=Gagal memperbarui profil: " . mysqli_error($conn));
    }
}
?>
