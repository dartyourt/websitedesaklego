<?php
/**
 * Mengimpor data terverifikasi dari folder data/ ke database.
 * Aman dijalankan berulang: data APBDes sumber diperbarui, dokumen tidak digandakan.
 */
require __DIR__ . '/config/database.php';

function db_escape(string $value): string { global $conn; return mysqli_real_escape_string($conn, $value); }
function run_query(string $sql): void { global $conn; if (!mysqli_query($conn, $sql)) throw new RuntimeException(mysqli_error($conn)); }
function doc_category(string $source): string {
    return match ($source) {
        'Sumber data Ayu' => 'Kesehatan & Stunting',
        'Sumber data Naila_APBDesa' => 'APBDes 2026',
        'Sumber Data Citra' => 'Profil & Sejarah Desa',
        'Sumber Data Naura' => 'Peta & Wilayah Desa',
        'Sumber Data Rahma' => 'Potensi & UMKM Desa',
        'Sumber Data Rheina' => 'Profil & Dokumentasi Desa',
        'Sumber data shafa' => 'Potensi Pertanian Desa',
        default => 'Dokumen Desa',
    };
}

try {
    mysqli_begin_transaction($conn);

    run_query("CREATE TABLE IF NOT EXISTS umkm (
        id INT AUTO_INCREMENT PRIMARY KEY, nama_usaha VARCHAR(255) NOT NULL, pemilik VARCHAR(255) DEFAULT NULL,
        telepon VARCHAR(30) DEFAULT NULL, produk TEXT DEFAULT NULL, jenis VARCHAR(100) DEFAULT NULL,
        alamat TEXT DEFAULT NULL, deskripsi TEXT DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL,
        sumber VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_nama_usaha (nama_usaha)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    // Angka resmi APBDes 2026 dari workbook Sumber data Naila_APBDesa.
    run_query("DELETE FROM infografis_statistik WHERE tahun IN ('2025', '2026')");
    $stats = [
        ['Pendapatan APBDes 2026', 'Pendapatan Asli Desa', 357550000, 'Rp', '2026', '#165f36', 1],
        ['Pendapatan APBDes 2026', 'Pendapatan Transfer', 1440238000, 'Rp', '2026', '#2e9e5b', 2],
        ['Pendapatan APBDes 2026', 'Lain-lain Pendapatan Yang Sah', 2500000, 'Rp', '2026', '#c4891f', 3],
        ['Belanja APBDes 2026', 'Penyelenggaraan Pemerintahan Desa', 907361852, 'Rp', '2026', '#1e40af', 1],
        ['Belanja APBDes 2026', 'Pelaksanaan Pembangunan Desa', 805142500, 'Rp', '2026', '#10b981', 2],
        ['Belanja APBDes 2026', 'Pembinaan Kemasyarakatan Desa', 268541000, 'Rp', '2026', '#f59e0b', 3],
        ['Belanja APBDes 2026', 'Pemberdayaan Masyarakat Desa', 22141000, 'Rp', '2026', '#8ecba5', 4],
        ['Belanja APBDes 2026', 'Penanggulangan Bencana, Darurat dan Mendesak', 112310000, 'Rp', '2026', '#dc2626', 5],
        ['Pembiayaan APBDes 2026', 'Penerimaan Pembiayaan', 350088352, 'Rp', '2026', '#7c3aed', 1],
        ['Pembiayaan APBDes 2026', 'Pengeluaran Pembiayaan', 34880000, 'Rp', '2026', '#be123c', 2],
        ['SILPA & Aset 2025', 'Saldo Akhir Kas / SILPA 2025', 350276263, 'Rp', '2025', '#047857', 1],
        ['SILPA & Aset 2025', 'Nilai Aset Tetap 2025', 37240430950, 'Rp', '2025', '#d97706', 2],
    ];
    foreach ($stats as [$kategori, $label, $nilai, $satuan, $tahun, $warna, $urutan]) {
        run_query("INSERT INTO infografis_statistik (kategori,label,nilai,satuan,tahun,warna,urutan) VALUES ('" . db_escape($kategori) . "','" . db_escape($label) . "',$nilai,'$satuan','$tahun','$warna',$urutan)");
    }

    // Ringkasan halaman publik dari naskah Sejarah Desa Klego (Sumber Data Citra).
    $sejarah = '<h3>Sejarah Desa Klego</h3><p>Desa Klego berada di Kecamatan Klego, Kabupaten Boyolali, Jawa Tengah. Sebagai ibu kota kecamatan, desa ini berkembang sebagai pusat pemerintahan, perekonomian, pendidikan, dan kehidupan sosial masyarakat.</p><h3>Legenda dan asal-usul nama</h3><p>Menurut cerita yang diwariskan masyarakat, nama Klego berkaitan dengan perjalanan Nyi Ageng Serang pada masa Perang Diponegoro. Sebutan ini diyakini berasal dari ungkapan Jawa <em>“kle-kle ora tego”</em>, yang menggambarkan kenangan mendalam bagi orang yang pernah singgah di wilayah ini.</p><h3>Sejarah pemerintahan</h3><p>Pemerintahan desa telah berjalan sejak sebelum 1930. Kepemimpinan kemudian antara lain dipegang Rononggolo (1930–1942), Rono Pratiwo (1942–1950), Suyoto (1950–1952), Harso Lumakso (1952–1965), Jamari (1967–1985), Sarjono (1985–2001), Daryanto (2001–2007), dan Guntur Heru Suprapto sejak 2007.</p><p>Nilai gotong royong, kebersamaan, dan pelayanan kepada masyarakat tetap menjadi bagian dari identitas Desa Klego.</p>';
    run_query("UPDATE halaman_statis SET konten='" . db_escape($sejarah) . "' WHERE slug='sejarah-visi-misi'");
    $potensi = '<h3>Potensi UMKM Desa Klego</h3><p>Direktori UMKM berikut bersumber dari pendataan Desa Klego dan ditampilkan langsung dari database. Informasi usaha dapat diperbarui melalui admin agar selalu akurat.</p>';
    run_query("UPDATE halaman_statis SET konten='" . db_escape($potensi) . "' WHERE slug='potensi-desa'");

    run_query("DELETE FROM keuangan WHERE tahun='2026'");
    foreach ($stats as [$kategori, $label, $nilai, $satuan, $tahun]) {
        if ($tahun === '2026') run_query("INSERT INTO keuangan (tahun,jenis,kelompok,kategori,jumlah) VALUES ('2026','APBDes','" . db_escape($kategori) . "','" . db_escape($label) . "','$nilai')");
    }

    $umkm = [
        ['Novi’s Kitchen','Sunarti','081331449874','Nasi Kuning, Nasi Gudangan, Nasi Box, Nasi Tumpeng, Cotot, Getuk, Lemet, Aneka Snack, Pempek','Makanan','Kedokan RT 16 RW 04','Usaha kuliner untuk kebutuhan harian maupun acara.','1. Novi_s Kitchen.jpg'],
        ['Kedai Es Nak Co Ger','Liyo Stowati','087788606821','Aneka Minuman Teh, Es Teler, Es Campur, Mojito','Kedai Minuman','Dukuh Klego, RT 04 RW 01','Menyediakan minuman segar untuk berbagai suasana.','2. Kedai Es Nak Co Ger.png'],
        ['Aneka Snack','Uji Permulani','082226733132','Macam-macam snack','Makanan Ringan','Ruko Karanganyar, Klego','Pilihan camilan gurih dan renyah untuk sajian acara maupun buah tangan.','3. Aneka Snack.jpeg'],
        ['Martabak Bangka 94','Fendi','082328857940','Martabak manis dan martabak telur','Makanan','Jl. Raya Karanggede-Gemolong No. 3, Ngembat, Klego','Martabak hangat dengan beragam pilihan rasa dan isian.','4. Martabak Bangka 94.jpeg'],
        ['Tahu Crispy & Ayam Crispy','Muhammad Khoirul Humam','08812480734','Tahu Crispy dan Ayam Crispy','Makanan','Ngembat, Klego','Camilan dan hidangan tahu serta ayam berbumbu pilihan.','5. Tahu Crispy & Ayam Crispy.jpeg'],
        ['Delia Bakery','Delia','081575890910','Aneka roti, roti ulang tahun, snack box, hantaran','Snack','Klego RT 04 RW 01, Boyolali','Roti, kue ulang tahun, snack box, serta paket hantaran.','6. Delia Bakery.jpg'],
    ];
    $sourceImages = __DIR__ . '/data/Sumber Data Rahma/Foto UMKM';
    $targetImages = __DIR__ . '/uploads/umkm';
    if (!is_dir($targetImages)) mkdir($targetImages, 0775, true);
    run_query("DELETE FROM umkm WHERE sumber='Sumber Data Rahma'");
    foreach ($umkm as [$nama,$pemilik,$telepon,$produk,$jenis,$alamat,$deskripsi,$foto]) {
        $destination = 'umkm_' . preg_replace('/[^a-z0-9]+/i', '_', pathinfo($foto, PATHINFO_FILENAME)) . '.' . strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        if (is_file($sourceImages . '/' . $foto)) copy($sourceImages . '/' . $foto, $targetImages . '/' . $destination);
        $values = array_map('db_escape', [$nama,$pemilik,$telepon,$produk,$jenis,$alamat,$deskripsi,$destination]);
        run_query("INSERT INTO umkm (nama_usaha,pemilik,telepon,produk,jenis,alamat,deskripsi,foto,sumber) VALUES ('{$values[0]}','{$values[1]}','{$values[2]}','{$values[3]}','{$values[4]}','{$values[5]}','{$values[6]}','{$values[7]}','Sumber Data Rahma') ON DUPLICATE KEY UPDATE pemilik=VALUES(pemilik),telepon=VALUES(telepon),produk=VALUES(produk),jenis=VALUES(jenis),alamat=VALUES(alamat),deskripsi=VALUES(deskripsi),foto=VALUES(foto),sumber=VALUES(sumber)");
    }

    // Daftarkan seluruh dokumen non-Satria agar dapat dicari dan diunduh dari portal.
    $roots = ['Sumber data Ayu','Sumber data Naila_APBDesa','Sumber Data Citra','Sumber Data Naura','Sumber Data Rahma','Sumber Data Rheina','Sumber data shafa'];
    foreach ($roots as $root) {
        $directory = __DIR__ . '/data/' . $root;
        if (!is_dir($directory)) continue;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg','jpeg','png','gif','webp'], true)) continue;
            $relative = 'data/' . str_replace('\\', '/', substr($file->getPathname(), strlen(__DIR__ . '/data/')));
            $relativeEsc = db_escape($relative);
            $title = db_escape(trim(preg_replace('/\s+/', ' ', str_replace(['_','-'], ' ', pathinfo($file->getFilename(), PATHINFO_FILENAME)))));
            $category = db_escape(doc_category($root));
            $check = mysqli_query($conn, "SELECT id FROM dokumen_publik WHERE file_path='$relativeEsc'");
            if ($check && mysqli_num_rows($check)) {
                run_query("UPDATE dokumen_publik SET judul='$title', kategori='$category', file_type='$extension', file_size=" . $file->getSize() . " WHERE file_path='$relativeEsc'");
            } else {
                run_query("INSERT INTO dokumen_publik (judul,kategori,file_path,file_type,file_size,keterangan,tanggal) VALUES ('$title','$category','$relativeEsc','$extension'," . $file->getSize() . ",'Dokumen sumber data Desa Klego.',CURDATE())");
            }
        }
    }
    mysqli_commit($conn);
    echo "[OK] Sinkronisasi selesai: APBDes 2026, 6 UMKM, foto UMKM, dan dokumen non-Satria telah diimpor.\n";
} catch (Throwable $e) {
    mysqli_rollback($conn);
    fwrite(STDERR, '[ERROR] ' . $e->getMessage() . "\n");
    exit(1);
}
