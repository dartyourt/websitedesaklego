<?php
/** Utilitas upload gambar yang dipakai seluruh panel admin. */
function app_base_path(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $directory = rtrim(str_replace('\\', '/', dirname($script)), '/');
    if (preg_match('#/admin$#i', $directory)) {
        $directory = rtrim(str_replace('\\', '/', dirname($directory)), '/');
    }
    return $directory === '/' ? '' : $directory;
}

function public_upload_url(string $relativePath): string
{
    return app_base_path() . '/uploads/' . ltrim(str_replace('\\', '/', $relativePath), '/');
}

function upload_image(array $file, string $directory, string $prefix = 'image_'): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return [false, 'Tidak ada gambar yang berhasil diunggah.'];
    if (($file['size'] ?? 0) < 1 || $file['size'] > 10 * 1024 * 1024) return [false, 'Ukuran gambar maksimal 10 MB.'];
    $info = @getimagesize($file['tmp_name'] ?? '');
    $extensions = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/gif'=>'gif', 'image/webp'=>'webp', 'image/bmp'=>'bmp'];
    if ($info === false || !isset($extensions[$info['mime'] ?? ''])) return [false, 'File yang dipilih bukan gambar JPG, PNG, GIF, WEBP, atau BMP yang valid.'];
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) return [false, 'Folder penyimpanan gambar tidak dapat dibuat.'];
    $filename = $prefix . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extensions[$info['mime']];
    if (!move_uploaded_file($file['tmp_name'], rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $filename)) return [false, 'Gambar gagal disimpan ke server.'];
    return [true, $filename];
}

function normalize_content_image_urls(string $html): string
{
    $prefix = app_base_path() . '/uploads/';
    return preg_replace_callback('/\\bsrc\\s*=\\s*(["\'])([^"\']+)\\1/i', function ($m) use ($prefix) {
        $url = str_replace('\\', '/', html_entity_decode($m[2], ENT_QUOTES, 'UTF-8'));
        if (preg_match('#^(?:\.\./|\./)*uploads/(.+)$#i', $url, $path)) return 'src=' . $m[1] . $prefix . ltrim($path[1], '/') . $m[1];
        return $m[0];
    }, $html);
}

/**
 * Mencari resolusi path gambar yang tahan terhadap perbedaan huruf besar/kecil (case-sensitivity di Linux Docker)
 * dan spasi pada penamaan file, serta mencari otomatis di folder berita, editor, maupun root uploads.
 */
function resolve_uploaded_image(string $filename, array $subdirs = ['berita', 'editor', 'umkm', 'perangkat', '']): string
{
    $filename = trim($filename);
    if (empty($filename)) return '';
    if (preg_match('#^(http://|https://|data:image)#i', $filename)) return $filename;
    
    // Hapus prefix uploads/ atau folder internal jika ada di DB
    $baseName = basename($filename);
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..', '/\\') . '/';
    $baseAppPath = ltrim(app_base_path(), '/');
    $prefixPath = ($baseAppPath !== '' ? $baseAppPath . '/' : '');
    
    // Cek di masing-masing subdirectory di bawah uploads/
    foreach ($subdirs as $sub) {
        $relDir = 'uploads' . ($sub !== '' ? '/' . $sub : '');
        $testRelPath = $relDir . '/' . $baseName;
        $fullPath = $docRoot . $prefixPath . $testRelPath;
        
        // 1. Cek langsung (exact match)
        if (file_exists($fullPath)) {
            return str_replace(' ', '%20', $testRelPath);
        }
        
        // 2. Cek case-insensitive (penting untuk Linux/Docker yang membedakan huruf besar/kecil)
        $dirPath = dirname($fullPath);
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);
            foreach ($files as $f) {
                if ($f !== '.' && $f !== '..' && strcasecmp($f, $baseName) === 0) {
                    return str_replace(' ', '%20', $relDir . '/' . $f);
                }
            }
        }
    }

    // Default fallback jika file belum dipindahkan atau sedang diimpor
    $defaultFolder = (strpos($filename, '/') !== false) ? 'uploads/' . ltrim($filename, '/') : 'uploads/berita/' . $filename;
    return str_replace(' ', '%20', $defaultFolder);
}
