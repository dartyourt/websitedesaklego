<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['error' => 'Akses ditolak. Silakan login kembali.']);
    exit;
}

// Pastikan direktori penyimpanan gambar editor ada
$targetDir = '../uploads/editor/';
if (!is_dir($targetDir)) {
    @mkdir($targetDir, 0777, true);
}

// Cari file upload dari input 'file' (custom AJAX kita) atau 'file_doc' atau standar TinyMCE 'file'
$fileObj = $_FILES['file'] ?? $_FILES['image'] ?? null;

if (!$fileObj || $fileObj['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Gagal mengunggah gambar. Pastikan ukuran file tidak melibihi batas server.']);
    exit;
}

$origName = basename($fileObj['name']);
$tmpName  = $fileObj['tmp_name'];
$fileSize = $fileObj['size'];

// Validasi ekstensi
$ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
$allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];

if (!in_array($ext, $allowedExts)) {
    echo json_encode(['error' => 'Format file tidak didukung. Harap upload gambar berekstensi JPG, PNG, WEBP, atau GIF.']);
    exit;
}

// Batasi ukuran gambar (maksimum 10 MB)
if ($fileSize > 10 * 1024 * 1024) {
    echo json_encode(['error' => 'Ukuran file gambar terlalu besar (Maksimal 10 MB).']);
    exit;
}

// Buat nama file unik agar tidak timpa-tindih
$newFileName = 'img_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
$destination = $targetDir . $newFileName;

if (move_uploaded_file($tmpName, $destination)) {
    // Kembalikan path relatif yang dapat dipanggil baik di admin maupun di public web
    $publicPath = 'uploads/editor/' . $newFileName;
    $adminViewPath = '../uploads/editor/' . $newFileName;
    
    // TinyMCE membutuhkan properti "location" dalam balasan JSON
    echo json_encode([
        'success' => true,
        'location' => $adminViewPath,
        'public_url' => $publicPath,
        'message' => 'Gambar berhasil diunggah'
    ]);
} else {
    echo json_encode(['error' => 'Gagal menyimpan file ke folder server uploads/editor/. Periksa hak akses folder.']);
}
