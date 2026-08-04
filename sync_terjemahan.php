<?php
/**
 * Script Otomasi Sinkronisasi & Migrasi Terjemahan (Bahasa & Jepang/Inggris)
 * Mode 1 (Lokal XAMPP): ?action=export -> Mengekspor dari database lokal ke terjemahan_data.sql
 * Mode 2 (Server Docker): ?action=import -> Menginjeksi terjemahan_data.sql ke Database Server
 */
include_once __DIR__ . '/config/database.php';
include_once __DIR__ . '/config/lang_helper.php';

$action = $_GET['action'] ?? ($argv[1] ?? 'status');
$sqlFile = __DIR__ . '/terjemahan_data.sql';

if ($action === 'export') {
    echo "=== EXPORT DATA TERJEMAHAN DESA KLEGO ===\n<br>";
    if (!$conn) {
        die("Koneksi database gagal.\n");
    }

    $out = "-- File Migrasi Terjemahan Desa Klego (Termasuk Editan Jepang & Inggris)\n";
    $out .= "-- Di-generate pada " . date('Y-m-d H:i:s') . "\n\n";
    
    // Export tabel master_bahasa
    $qB = @mysqli_query($conn, "SELECT * FROM master_bahasa");
    if ($qB && mysqli_num_rows($qB) > 0) {
        while ($r = mysqli_fetch_assoc($qB)) {
            $kode = mysqli_real_escape_string($conn, $r['kode']);
            $nama = mysqli_real_escape_string($conn, $r['nama']);
            $bendera = mysqli_real_escape_string($conn, $r['bendera']);
            $is_def = (int)$r['is_default'];
            $status = (int)$r['status'];
            $out .= "INSERT INTO master_bahasa (kode, nama, bendera, is_default, status) VALUES ('$kode', '$nama', '$bendera', $is_def, $status) ON DUPLICATE KEY UPDATE nama='$nama', bendera='$bendera', is_default=$is_def, status=$status;\n";
        }
        echo "[OK] Tabel master_bahasa berhasil dicekam.<br>\n";
    }

    // Export tabel terjemahan_konten
    $qK = @mysqli_query($conn, "SELECT * FROM terjemahan_konten");
    if ($qK && mysqli_num_rows($qK) > 0) {
        $jml = mysqli_num_rows($qK);
        while ($r = mysqli_fetch_assoc($qK)) {
            $kat = mysqli_real_escape_string($conn, $r['kategori']);
            $ref = (int)$r['referensi_id'];
            $kunci = mysqli_real_escape_string($conn, $r['kunci']);
            $lang = mysqli_real_escape_string($conn, $r['kode_bahasa']);
            $teks = mysqli_real_escape_string($conn, $r['teks_terjemahan']);
            $out .= "INSERT INTO terjemahan_konten (kategori, referensi_id, kunci, kode_bahasa, teks_terjemahan) VALUES ('$kat', $ref, '$kunci', '$lang', '$teks') ON DUPLICATE KEY UPDATE teks_terjemahan='$teks';\n";
        }
        echo "[OK] Ditemukan dan disimpan $jml baris terjemahan (termasuk bahasa Jepang/Inggris) dari database.<br>\n";
    } else {
        echo "[INFO] Belum ada item di tabel terjemahan_konten.<br>\n";
    }

    file_put_contents($sqlFile, $out);
    echo "<b>[SUCCESS] Berhasil mengekspor seluruh data terjemahan ke file terjemahan_data.sql!</b><br>\n";
    if (isset($_GET['action'])) {
        echo "<br><a href='index.php' style='color:#165f36;font-weight:bold;'>&larr; Kembali ke Beranda</a>";
    }

} elseif ($action === 'import') {
    echo "=== IMPORT & SINKRONISASI DATA TERJEMAHAN ===\n<br>";
    if (!file_exists($sqlFile)) {
        die("<b>[ERROR]</b> File terjemahan_data.sql belum tersedia di server. Pastikan Anda sudah mengupload / git pull update terbaru.\n");
    }
    
    $sql = file_get_contents($sqlFile);
    $queries = explode(';', $sql);
    $count = 0;
    foreach ($queries as $q) {
        $q = trim($q);
        if (!empty($q) && strpos($q, '--') !== 0) {
            mysqli_query($conn, $q);
            $count++;
        }
    }
    echo "<b>[SUCCESS] Berhasil mengimpor dan menyertakan $count instruksi pembaruan terjemahan ke database server ini!</b><br>\n";
    if (isset($_GET['action'])) {
        echo "<br><a href='index.php' style='color:#165f36;font-weight:bold;'>&larr; Lihat Hasil di Website Depan</a>";
    }
} else {
    echo "<div style='font-family:sans-serif;padding:30px;line-height:1.6;'>";
    echo "<h3>🔄 Alat Sinkronisasi Terjemahan Desa Klego</h3>";
    echo "<p>Pilih tindakan di bawah ini sesuai posisi server Anda:</p>";
    echo "<ul>";
    echo "<li><a href='?action=export' style='color:blue;font-weight:bold;'>1. EXPORT Terjemahan dari Database Lokal</a> (Gunakan di XAMPP komputer Anda setelah selesai merubah bahasa)</li>";
    echo "<li><a href='?action=import' style='color:green;font-weight:bold;'>2. IMPORT Terjemahan ke Server Ini</a> (Gunakan di Server / Cloudflare setelah git pull untuk menyinkronkan tulisan Jepang/asing)</li>";
    echo "</ul>";
    echo "</div>";
}
?>
