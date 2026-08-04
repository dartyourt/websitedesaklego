<?php
$host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : "localhost";
$port = getenv('DB_PORT') !== false ? (int)getenv('DB_PORT') : 3306;
$user = getenv('DB_USER') !== false ? getenv('DB_USER') : "root";
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$db   = getenv('DB_NAME') !== false ? getenv('DB_NAME') : "desa_klego";

$conn = mysqli_connect($host, $user, $pass, $db, $port);
$koneksi = $conn;

if (!$conn) {
  die("Koneksi database gagal");
}

// Pastikan seluruh nama, alamat, dan konten berbahasa Indonesia tersimpan utuh.
mysqli_set_charset($conn, 'utf8mb4');

$queryProfil = mysqli_query($conn, "SELECT * FROM profil_desa WHERE id=1");
if ($queryProfil && mysqli_num_rows($queryProfil) > 0) {
    $APP_PROFIL = mysqli_fetch_assoc($queryProfil);
    $APP_PROFIL['kabupaten'] = preg_replace('/^(kabupaten|kab\.)\s+/i', '', $APP_PROFIL['kabupaten']);
    $APP_PROFIL['kecamatan'] = preg_replace('/^(kecamatan|kec\.)\s+/i', '', $APP_PROFIL['kecamatan']);
} else {
    $APP_PROFIL = [
        'nama_desa' => 'Desa Anda',
        'alamat' => 'Alamat Desa',
        'kecamatan' => 'Kecamatan',
        'kabupaten' => 'Kabupaten',
        'provinsi' => 'Provinsi',
        'kode_pos' => '00000',
        'telepon' => '-',
        'email' => 'email@desa.com',
        'logo' => 'logo.png'
    ];
}
$APP_PROFIL['nama_desa_clean'] = preg_replace('/^(desa|kelurahan)\s+/i', '', $APP_PROFIL['nama_desa']);
