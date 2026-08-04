<?php
/**
 * Script Otomasi Sinkronisasi & Migrasi Terjemahan (JSON Engine - Anti Crash Semicolon/HTML)
 * Mode 1 (Lokal XAMPP): ?action=export -> Menyimpan dari database lokal ke terjemahan_data.json
 * Mode 2 (Server Docker): ?action=import -> Menginjeksi terjemahan_data.json ke Database Server
 */
include_once __DIR__ . '/config/database.php';
include_once __DIR__ . '/config/lang_helper.php';

$action = $_GET['action'] ?? ($argv[1] ?? 'status');
$jsonFile = __DIR__ . '/terjemahan_data.json';

if ($action === 'export') {
    echo "=== EXPORT DATA TERJEMAHAN DESA KLEGO ===\n<br>";
    if (!$conn) {
        die("Koneksi database gagal.\n");
    }

    $data = [
        'generated_at' => date('Y-m-d H:i:s'),
        'master_bahasa' => [],
        'terjemahan_konten' => []
    ];
    
    // Export tabel master_bahasa
    $qB = @mysqli_query($conn, "SELECT * FROM master_bahasa");
    if ($qB && mysqli_num_rows($qB) > 0) {
        while ($r = mysqli_fetch_assoc($qB)) {
            $data['master_bahasa'][] = $r;
        }
        echo "[OK] Tabel master_bahasa berhasil diekspor (" . count($data['master_bahasa']) . " item).<br>\n";
    }

    // Export tabel terjemahan_konten
    $qK = @mysqli_query($conn, "SELECT * FROM terjemahan_konten");
    if ($qK && mysqli_num_rows($qK) > 0) {
        while ($r = mysqli_fetch_assoc($qK)) {
            $data['terjemahan_konten'][] = $r;
        }
        echo "[OK] Tabel terjemahan_konten berhasil diekspor (" . count($data['terjemahan_konten']) . " item, termasuk Jepang/Inggris).<br>\n";
    }

    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<b>[SUCCESS] Berhasil mengekspor seluruh data ke file terjemahan_data.json dengan format anti-crash!</b><br>\n";
    if (isset($_GET['action'])) {
        echo "<br><a href='index.php' style='color:#165f36;font-weight:bold;'>&larr; Kembali ke Beranda</a>";
    }

} elseif ($action === 'import') {
    echo "=== IMPORT & SINKRONISASI DATA TERJEMAHAN ===\n<br>";
    if (!file_exists($jsonFile)) {
        die("<b>[ERROR]</b> File terjemahan_data.json belum tersedia di server. Pastikan Anda sudah mengupload / git pull update terbaru.\n");
    }
    
    $raw = file_get_contents($jsonFile);
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        die("<b>[ERROR]</b> Format file JSON tidak valid.\n");
    }

    $countB = 0;
    foreach (($data['master_bahasa'] ?? []) as $r) {
        $kode = mysqli_real_escape_string($conn, $r['kode']);
        $nama = mysqli_real_escape_string($conn, $r['nama']);
        $bendera = mysqli_real_escape_string($conn, $r['bendera']);
        $is_def = (int)$r['is_default'];
        $status = (int)$r['status'];
        mysqli_query($conn, "INSERT INTO master_bahasa (kode, nama, bendera, is_default, status) VALUES ('$kode', '$nama', '$bendera', $is_def, $status) ON DUPLICATE KEY UPDATE nama='$nama', bendera='$bendera', is_default=$is_def, status=$status");
        $countB++;
    }

    $countK = 0;
    foreach (($data['terjemahan_konten'] ?? []) as $r) {
        $kat = mysqli_real_escape_string($conn, $r['kategori']);
        $ref = (int)$r['referensi_id'];
        $kunci = mysqli_real_escape_string($conn, $r['kunci']);
        $lang = mysqli_real_escape_string($conn, $r['kode_bahasa']);
        $teks = mysqli_real_escape_string($conn, $r['teks_terjemahan']);
        $res = mysqli_query($conn, "INSERT INTO terjemahan_konten (kategori, referensi_id, kunci, kode_bahasa, teks_terjemahan) VALUES ('$kat', $ref, '$kunci', '$lang', '$teks') ON DUPLICATE KEY UPDATE teks_terjemahan='$teks'");
        if ($res) {
            $countK++;
        } else {
            echo "[WARN] Gagal memasukkan item $kunci ($lang): " . mysqli_error($conn) . "<br>\n";
        }
    }

    echo "<b>[SUCCESS] Berhasil mengimpor dan menyertakan $countB bahasa dan $countK instruksi terjemahan (termasuk bahasa Jepang/asing) ke database server ini!</b><br>\n";
    if (isset($_GET['action'])) {
        echo "<br><a href='index.php' style='color:#165f36;font-weight:bold;'>&larr; Lihat Hasil di Website Depan</a>";
    }
} else {
    echo "<div style='font-family:sans-serif;padding:30px;line-height:1.6;'>";
    echo "<h3>🔄 Alat Sinkronisasi Terjemahan Desa Klego (JSON Engine)</h3>";
    echo "<p>Pilih tindakan di bawah ini sesuai posisi server Anda:</p>";
    echo "<ul>";
    echo "<li><a href='?action=export' style='color:blue;font-weight:bold;'>1. EXPORT Terjemahan dari Database Lokal</a> (Gunakan di XAMPP komputer Anda setelah selesai merubah bahasa)</li>";
    echo "<li><a href='?action=import' style='color:green;font-weight:bold;'>2. IMPORT Terjemahan ke Server Ini</a> (Gunakan di Server / Cloudflare setelah git pull untuk menyinkronkan tulisan Jepang/asing)</li>";
    echo "</ul>";
    echo "</div>";
}
?>
