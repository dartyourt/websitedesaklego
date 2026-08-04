<?php
session_start();
include '../config/database.php';
require_once 'SimpleXLSX.php'; // Include SimpleXLSX
use Shuchkin\SimpleXLSX;

if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

if (isset($_POST['import'])) {
    
    // Validasi file
    if ($_FILES['file_csv']['error'] == UPLOAD_ERR_OK) {
        
        $tmpName = $_FILES['file_csv']['tmp_name'];
        $fileName = $_FILES['file_csv']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $sukses = 0;
        $gagal = 0;

        $rows = [];

        // Parse file based on extension
        if ($ext === 'csv') {
            if (($handle = fopen($tmpName, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rows[] = $data;
                }
                fclose($handle);
            } else {
                echo "<script>alert('Gagal membuka file CSV'); window.location='penduduk.php';</script>";
                exit;
            }
        } elseif ($ext === 'xlsx') {
            if ( $xlsx = SimpleXLSX::parse($tmpName) ) {
                $rows = $xlsx->rows();
            } else {
                $error = SimpleXLSX::parseError();
                echo "<script>alert('Gagal membaca file XLSX: $error'); window.location='penduduk.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file tidak didukung. Harap upload .csv atau .xlsx'); window.location='penduduk.php';</script>";
            exit;
        }

        // Fungsi konversi tanggal Excel serial atau string ke format Y-m-d
        function parseDate($val) {
            if (empty($val)) return '';
            $val = trim($val);
            // Sudah format tanggal string
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $val)) {
                return substr($val, 0, 10);
            }
            // Format DD/MM/YYYY atau DD-MM-YYYY
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $val, $m)) {
                return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            }
            // Excel serial date (angka)
            if (is_numeric($val) && $val > 1) {
                $num = (float)$val;
                if ($num >= 60) $num -= 1; // koreksi bug Excel 1900
                $base = mktime(0, 0, 0, 12, 31, 1899);
                $ts = $base + ($num * 86400);
                return date('Y-m-d', $ts);
            }
            return '';
        }

        // Kolom XLS datapenduduk.xls (0-indexed):
        // 0:NIK, 1:NO_KK, 2:NAMA_LGKP, 3:JENIS_KELAMIN, 4:TANGGAL_LAHIR,
        // 5:UMUR, 6:TEMPAT_LAHIR, 7:ALAMAT/DUSUN, 8:NO_RT, 9:NO_RW,
        // 10:KELURAHAN(skip), 11:SHDK, 12:STATUS_KAWIN, 13:PENDIDIKAN,
        // 14:AGAMA, 15:PEKERJAAN, 16:GOLONGAN_DARAH(skip), 17:AKTA_LAHIR(skip),
        // 18:NO_AKTA_LAHIR, 19:AKTA_KAWIN(skip), 20:NO_AKTA_KAWIN,
        // 21:AKTA_CERAI(skip), 22:NO_AKTA_CERAI, 23:NAMA_AYAH, 24:NAMA_IBU

        // Loop rows and insert
        foreach ($rows as $data) {
            $nik = mysqli_real_escape_string($koneksi, $data[0] ?? '');
            
            // Lewati jika NIK kosong atau header
            if (empty($nik) || !is_numeric($nik)) {
                continue;
            }

            $no_kk        = mysqli_real_escape_string($koneksi, $data[1] ?? '');
            $nama         = mysqli_real_escape_string($koneksi, $data[2] ?? '');
            $jk           = mysqli_real_escape_string($koneksi, $data[3] ?? '');
            $tgl_lahir    = mysqli_real_escape_string($koneksi, parseDate($data[4] ?? ''));
            $usia         = mysqli_real_escape_string($koneksi, $data[5] ?? '');
            $tempat_lahir = mysqli_real_escape_string($koneksi, $data[6] ?? '');
            $dusun        = mysqli_real_escape_string($koneksi, $data[7] ?? '');
            $rt           = mysqli_real_escape_string($koneksi, $data[8] ?? '');
            $rw           = mysqli_real_escape_string($koneksi, $data[9] ?? '');
            // col 10 = KELURAHAN (skip)
            $shdk         = mysqli_real_escape_string($koneksi, $data[11] ?? '');
            $status_kawin = mysqli_real_escape_string($koneksi, $data[12] ?? '');
            $pendidikan   = mysqli_real_escape_string($koneksi, $data[13] ?? '');
            $agama        = mysqli_real_escape_string($koneksi, $data[14] ?? '');
            $pekerjaan    = mysqli_real_escape_string($koneksi, $data[15] ?? '');
            // col 16 = GOLONGAN_DARAH (skip), col 17 = AKTA_LAHIR (skip)
            $no_akta_lahir  = mysqli_real_escape_string($koneksi, $data[18] ?? '');
            // col 19 = AKTA_KAWIN (skip)
            $no_akta_kawin  = mysqli_real_escape_string($koneksi, $data[20] ?? '');
            // col 21 = AKTA_CERAI (skip)
            $no_akta_cerai  = mysqli_real_escape_string($koneksi, $data[22] ?? '');
            $nama_ayah      = mysqli_real_escape_string($koneksi, $data[23] ?? '');
            $nama_ibu       = mysqli_real_escape_string($koneksi, $data[24] ?? '');

            // Kalkulasi usia jika kosong
            if (empty($usia) && !empty($tgl_lahir)) {
                try {
                    $tgl = new DateTime($tgl_lahir);
                    $today = new DateTime();
                    $usia = $today->diff($tgl)->y;
                } catch (Exception $e) {
                    $usia = '';
                }
            }

            // Cek apakah NIK sudah ada
            $cek = mysqli_query($koneksi, "SELECT id FROM penduduk WHERE NIK='$nik'");
            
            if (mysqli_num_rows($cek) > 0) {
                $gagal++;
            } else {
                $tgl_sql = !empty($tgl_lahir) ? "'$tgl_lahir'" : "NULL";
                $sql = "INSERT INTO penduduk (
                    NIK, NO_KK, NAMA_LGKP, JENIS_KELAMIN, TMPT_LAHIR, TGL_LAHIR, USIA, 
                    DUSUN, RT, RW, SHDK, STATUS_KAWIN, PENDIDIKAN, AGAMA, PEKERJAAN, 
                    NO_AKTA_LAHIR, NO_AKTA_KAWIN, NO_AKTA_CERAI, NAMA_AYAH, NAMA_IBU
                ) VALUES (
                    '$nik', '$no_kk', '$nama', '$jk', '$tempat_lahir', $tgl_sql, '$usia',
                    '$dusun', '$rt', '$rw', '$shdk', '$status_kawin', '$pendidikan', '$agama', '$pekerjaan',
                    '$no_akta_lahir', '$no_akta_kawin', '$no_akta_cerai', '$nama_ayah', '$nama_ibu'
                )";
                
                if (mysqli_query($koneksi, $sql)) {
                    $sukses++;
                } else {
                    $gagal++;
                }
            }
        }
        
        echo "<script>
            alert('Import Selesai! Berhasil: $sukses baris, Gagal / Duplikat: $gagal baris');
            window.location='penduduk.php';
        </script>";
            
    } else {
        echo "<script>alert('Silakan pilih file CSV atau XLSX terlebih dahulu'); window.location='penduduk.php';</script>";
    }
} else {
    header("Location: penduduk.php");
    exit;
}
?>
