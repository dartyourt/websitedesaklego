# BUKU PANDUAN PENGGUNA (USER MANUAL) & SOP OPERASIONAL
## SISTEM INFORMASI & PORTAL RESMI DESA KLEGO

---

**Informasi Dokumen**
* **Judul Sistem**: Portal Keterbukaan Informasi & WebGIS Desa Klego, Kecamatan Klego, Kabupaten Boyolali.
* **Versi Dokumen**: 2.0 (Edisi Pembaruan Terintegrasi APBDes & Peta WebGIS)
* **Sasaran Pembaca**: Perangkat Desa, Admin CMS, Operator Balai Desa, dan Masyarakat Umum.

---

## 1. PENGANTAR SISTEM & TUJUAN PANDUAN

Sistem Website Desa Klego dirancang sebagai pusat informasi terpadu yang memadukan pelayanan publik, transparansi keuangan pemerintah desa (APBDes), pemetaan spasial fasilitas umum (WebGIS), direktori potensi ekonomi (UMKM), serta keterbukaan dokumen regulasi (JDIH).

Tujuan dari buku panduan ini adalah:
1. Memberikan pedoman langkah-demi-langkah (SOP) bagi Perangkat Desa dalam memvalidasi, menambah, memperbarui, dan mengelola seluruh konten website secara mandiri tanpa memerlukan pemrograman rumit.
2. Memastikan seluruh data sektoral (Pertanian, Keuangan APBDes, Stunting, Sejarah, Peta, dan UMKM) tetap akurat dan akuntabel sesuai kueri dan file sumber data balai desa.

---

## 2. KREDENSIAL AKSES & JALUR RAHASIA ADMINISTRATOR (CMS PERANGKAT DESA)

> [!IMPORTANT]
> **Kebijakan Keamanan Portal Admin (Stealth Mode)**
> Sesuai standar keamanan sistem pemerintahan desa, tombol atau tautan untuk login ke **Portal Admin TIDAK DITAMPILKAN secara umum baik pada Header maupun Footer** di halaman beranda warga. Hal ini bertujuan untuk melindungi panel kontrol dari upaya pencurian kredensial atau penetrasi dari luar.

### 2.1. Cara Mengakreditasi dan Masuk (Login) ke Panel Admin
Hanya Perangkat Desa atau Operator Resmi yang mengetahui jalur akses rahasia menuju halaman otentikasi.

* **Alamat URL Server Produksi**: `http://klego.dayoumu.my.id/login.php`
* **Alamat URL Lokal / offline**: `http://localhost/desa-desa/login.php`

#### Kredensial Bawaan (Default Login) untuk Perangkat Desa:
| Parameter | Nilai Akses Resmi | Keterangan Sistem |
| :--- | :--- | :--- |
| **Username** | `admin` | Username administrator utama desa |
| **Password** | `admin` | Password standar yang akan dienkripsi otomatis saat login |

> [!TIP]
> **Fitur Keamanan Enkripsi Otomatis (Bcrypt Hashing Upgrade)**
> Ketika Anda login pertama kali menggunakan kata sandi bawaan (`admin`), sistem keamanan otentikasi kami akan secara otomatis mengubah sandi Anda di database menjadi sandi terenkripsi berlapis (Bcrypt Hash), sehingga terhindar dari pembacaan database ilegal. Sangat disarankan untuk segera mengganti password bawaan melalu menu profil setelah berhasil masuk.

---

## 3. PANDUAN PENGOPERASIAN FITUR CMS (KHUSUS PERANGKAT DESA)

Setelah berhasil login, Perangkat Desa akan disuguhi Dasbor Admin interaktif bertema **Hijau Emerald & Emas (Emerald & Gold Theme)** yang melambangkan warna kemakmuran dan identitas resmi Kabupaten Boyolali.

### 3.1. Manajemen Keuangan & APBDesa (Infografis Statistik)
Fitur ini mengendalikan grafik lingkar, diagram batang, dan angka transparansi keuangan pada halaman depan web desa yang bersumber dari data verifikasi akuntansi desa (Folder Naila & Satria).

1. **Akses Menu**: Pada dasbor admin, klik menu **"Keuangan & APBDesa"** (`infografis.php`).
2. **Struktur Kelompok Data**: Data dibagi dalam 4 pilar utama:
   * `Pendapatan APBDes 2026` (Pendapatan Asli Desa, Transfer, dll)
   * `Belanja APBDes 2026` (Pemerintahan, Pembangunan, Pembinaan, Pemberdayaan, Darurat)
   * `Pembiayaan APBDes 2026` (Penerimaan & Pengeluaran)
   * `SILPA & Aset 2025` (Saldo SILPA, Nilai Buku Aset Tetap, dan Lahan Pertanian 312 Hektar).
3. **Cara Menambah Data Baru**:
   * Klik tombol hijau **"+ Tambah Data APBDes / Infografis"**.
   * Pilih **Kategori Keuangan** yang tepat dari dropdown.
   * Masukkan **Uraian / Label Parameter** (misal: *Pembangunan Infrastruktur Jalan*).
   * Masukkan **Nilai Nominal / Angka** dalam bilangan utuh tanpa titik atau koma (misal untuk Rp 1.500.000.000 ketikan `1500000000`).
   * Tentukan **Warna Grafik** (hex color picker) yang harmonis agar tabel visual terender indah di layar warga.
   * Klik **Simpan Data APBDes**.
4. **Cara Mengubah (Edit) atau Menghapus (Hapus)**:
   * Pada tabel item di bawah kategori yang sesuai, klik tombol biru **Edit** untuk merevisi nominal saat ada Perubahan Anggaran (PAK), atau tombol merah **Hapus** untuk membatalkan item dari publikasi.

---

### 3.2. Manajemen Agenda & Kegiatan Resmi Desa
Agenda desa berfungsi memberitahukan warga mengenai acara rapat bulanan, jadwal posyandu, kerja bakti, maupun penyaluran bantuan langsung.

1. **Akses Menu**: Klik kartu **"Agenda Kegiatan Desa"** (`agenda.php`).
2. **Kebijakan Data Nyata**: Sistem ini telah dirancang bebas dari data tiruan (dummy). Semua yang muncul di web adalah agenda akurat yang Anda isikan.
3. **Menambah Agenda**:
   * Klik **"+ Tambah Agenda Baru"**.
   * Lengkapi **Judul Kegiatan**, **Tanggal**, **Waktu Execution**, dan **Lokasi** (misal: *Balai Desa Klego*).
   * Tekan tombol **Simpan Agenda**. Beranda warga akan seketika memperbarui kalender informasinya.

---

### 3.3. Manajemen Berita & Artikel Kegiatan Warga
1. **Akses Menu**: Klik **"Berita & Artikel"** (`berita.php`).
2. **Fitur Clean Preview Otomatis**: Ketika Anda menulis artikel berita dengan teks panjang atau menyertakan gambar, sistem frontend secara otomatis memotong ringkasan (*snippet*) untuk kartu beranda dengan memfilter tag HTML (membuang simbol acak seperti `&ndash;` atau tampilan layout rusak), menjaga estetika beranda tetap rapi.
3. **Unggah Foto Berita**: Gunakan file berekstensi `.jpg`, `.png`, atau `.webp`. Sistem telah dibekali penampung jalur error tahan banting (*fallback resolver*) agar foto tetap tayang sempurna baik saat dijalankan di Windows Lokal maupun kontainer Docker Linux Server Produksi.

---

### 3.4. Manajemen Dokumen Regulasi (JDIH & Pembendaharaan)
Pusat data dokumen memberikan layanan unduh gratis kepada warga terhadap berkas Peraturan Desa (Perdes), RPJMDes, RKPDes, Laporan Realisasi, dan Buku Bantu Aset (Folder Satria & seluruh tim pendukung).

1. **Akses Menu**: Klik menu Dokumen atau kelola dari sinkronisasi sistem.
2. **Struktur Kategori Otomatis**: Dokumen diindeks berurutan sesuai kategori keilmuan: *Kesehatan & Stunting (Ayu)*, *APBDes 2026 (Naila)*, *Profil & Sejarah (Citra & Rheina)*, *Peta & Wilayah (Naura)*, *Potensi & UMKM (Rahma)*, serta *Potensi Pertanian (Shafa)*.

---

### 3.5. Pengelola Bahasa & Sistem Terjemahan Otomatis (Indonesia - Inggris - Jepang)
Website Desa Klego bertaraf internasional dengan dukungan 3 bahasa guna mempermudah pengenalan potensi desa kepada wisatawan maupun investor global.

1. **Sinkronisasi Terjemahan**: Sistem membaca master kosakata dan kalimat dari `terjemahan_data.json` serta mengintegrasikannya langsung dengan tabel database `master_bahasa` dan `terjemahan_konten`.
2. **Cara Kerja di Lapangan**: Seluruh navigasi, sub-navbar, legenda tabel APBDes, judul sejarah, dan statistik demografi beralih secara dinamis ketika pengguna memilih bendera bahasa di pojok atas website, tanpa merusak format tata letak utama.

---

## 4. PANDUAN NAVIGASI PORTAL WARGA (FRONTEND PUBLIC)

### 4.1. Beranda & Peta Digital WebGIS Interaktif (Sumber Data Naura)
* **Lokasi Peta**: Tepat setelah blok *Statistik Warga & Wilayah* dan sebelum blok *Keterbukaan Informasi / Unduh Dokumen*.
* **Fitur Interaktif Warga**:
  * **Polygon Batas Wilayah**: Garis lingkar biru/teal menandakan batas administratif formal Desa Klego.
  * **Marker 18 Fasilitas Umum**: Warga dapat mengeklik simbol ikon spesifik untuk Masjid, Sekolah (SD/SMP/MIN), Puskesmas, Pasar Klego, Taman, Kantor Balai Desa, dan Lapangan.
  * **Popup Spesifik**: Klik pada marker untuk melihat informasi kategori serta menekan tombol **"Buka Google Maps"** untuk navigasi GPS langsung dari HP warga.
  * **Legenda Anti-Menumpuk**: Kontrol Layer (Peta Satelit Esri, Carto Light, OpenStreetMap) ditaruh terlipat (*collapsed*) di sudut **Kanan Atas**, sementara Legenda warna ikon berada di sudut **Kiri Bawah**, menjamin peta tetap jernih dan tak bertumpangan.

### 4.2. Eksplorasi Data APBDesa & Transparansi Keuangan
* Warga dapat membuka halaman menu **"Infografis Keuangan & Demografi"** atau mengamati ringkasan langsung di Beranda.
* Angka disajikan dengan format satuan nominal yang mudah dipahami (Rupiah murni, Juta Rupiah, atau satuan Hektar Lahan), menunjang kepercayaan masyarakat terhadap kepemimpinan desa yang akuntabel.

---

## 5. PEMECAHAN MASALAH (TROUBLESHOOTING) & KENDALA UMUM

| Kendala yang Dialami | Kemungkinan Penyebab | Solusi & Langkah Penanganan |
| :--- | :--- | :--- |
| **Lupa letak link login admin** | Tautan disembunyikan untuk keamanan sistem dari ancaman hacker | Ketik manual di browser: `<alamat-server>/login.php` lalu ketikan username `admin`. |
| **Data baru di tabel/Infografis tidak terupdate setelah git pull** | Kontainer Docker di server belum mensinggung (reseed) tabel MySQL baru | Minta admin IT menjalankan perintah reseed: `docker exec -it desaklego_web php sinkronisasi_data_sumber.php`. |
| **Gambar berita tidak tampil / patah** | Perbedaan kapitalisasi ekstensi file (misal `.JPG` vs `.jpg`) di Server OS Linux | Sistem penstabil gambar otomatis (`resolve_uploaded_image`) kami akan mengarahkan ulang ke versi huruf kecil/fallback resmi secara transparan. |
| **Teks berita berantakan / muncul simbol `&ndash;`** | Teks disalin langsung dari Microsoft Word beserta kode rahasia formatter | Fitur `clean_preview_text()` sudah kami aktifkan untuk menonaktifkan kode sampah Word secara instan pada ringkasan web. |
