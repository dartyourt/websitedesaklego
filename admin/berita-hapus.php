<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil data untuk mengecek file gambar
    $cek = @mysqli_query($conn, "SELECT foto FROM berita WHERE id = $id");
    $data = $cek ? mysqli_fetch_assoc($cek) : null;
    if ($data && !empty($data['foto'])) {
        $file = __DIR__ . '/../uploads/berita/' . $data['foto'];
        if (file_exists($file) && is_file($file)) {
            @unlink($file);
        }
    }

    $delete = @mysqli_query($conn, "DELETE FROM berita WHERE id = $id");
    if ($delete) {
        echo "<script>alert('Berita berhasil dihapus!'); window.location='berita.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus berita: " . mysqli_error($conn) . "'); window.location='berita.php';</script>";
    }
} else {
    header("Location: berita.php");
}
exit;
