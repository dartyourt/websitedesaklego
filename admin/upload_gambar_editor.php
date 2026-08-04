<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/upload_helper.php';

if (!isset($_SESSION['login'])) {
    echo json_encode(['error' => 'Akses ditolak. Silakan login kembali.']);
    exit;
}

// Cari file upload dari input custom atau standar TinyMCE.
$fileObj = $_FILES['file'] ?? $_FILES['image'] ?? null;
[$success, $result] = upload_image($fileObj ?? [], __DIR__ . '/../uploads/editor', 'img_');
if ($success) {
    $publicUrl = public_upload_url('editor/' . $result);
    echo json_encode([
        'success' => true,
        'location' => $publicUrl,
        'public_url' => $publicUrl,
        'message' => 'Gambar berhasil diunggah'
    ]);
} else {
    echo json_encode(['error' => $result]);
}
