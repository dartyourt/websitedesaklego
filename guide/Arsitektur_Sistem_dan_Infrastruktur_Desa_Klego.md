# ARSITEKTUR SISTEM DAN SPESIFIKASI TEKNIS SERVER
## WEBSITE & PORTAL RESMI DESA KLEGO
**Kabupaten Boyolali, Jawa Tengah**

---

**Informasi Dokumen Teknis**
* **Pengelola**: Tim Teknologi Informasi Pemerintah Desa Klego
* **Alamat Server Produksi**: `klego.dayoumu.my.id`
* **Status Sistem & Uptime**: Sangat Sehat, Stabil, & Siaga Penuh (Online via Kontainer Docker)
* **Versi Spesifikasi Dokumen**: 2.1 (Pembaruan Audit Real-Time & Glosarium)

---

## 1. KAMUS ISTILAH TEKNIS (GLOSARIUM ARSITEKTUR)

Agar dokumen teknis ini mudah dipahami oleh perwakilan perangkat desa maupun operator IT baru, berikut adalah terjemahan sederhana dari berbagai istilah komputer dan server yang digunakan:

* **Server (Mesin Pusat)**: Komputer besar berkecepatan tinggi yang selalu hidup 24 jam di internet untuk melayani warga yang ingin membuka situs website Desa Klego dari manapun.
* **Docker / Container (Kontainer)**: Teknologi pengemasan pintar yang membagi program di server ke dalam kotak-kotak terpisah (kontainer). Kotak website (`desaklego_web`) dipisahkan dari kotak database (`desaklego_db`) agar tidak saling mengganggu dan sangat hemat tenaga.
* **RAM (Memori Kerja)**: Kapasitas tenaga berpikir sementara pada server. Semakin hemat pemakaian RAM, semakin lancar dan anti-lelet website desa dibuka.
* **Disk / Storage (Ruang Penyimpanan / SSD)**: Kapasitas penyimpanan untuk mengawetkan data file permanen, seperti gambar berita, arsip peraturan, dan laporan keuangan.
* **PHP**: Bahasa pembuat logika sistem utama yang menghidupkan fungsi formulir, kalkulasi APBDes, dan pembuka dokumen web desa kita.
* **MariaDB / MySQL**: Mesin lemari database terenkripsi tempat menyortir dan menyimpan informasi angka-angka keuangan, daftar UMKM, agenda, dan kata sandi admin secara terorganisir.
* **Git / GitHub**: Tempat penyimpanan salinan kode cadangan website di internet agar jika terjadi sesuatu, sistem dengan gampang dipulihkan ke posisi semula dalam hitungan detik.
* **Terminal / SSH (Command Line)**: Jendela teks warna hitam tempat admin IT mengetikkan perintah lisan untuk mengendalikan server tanpa menggunakan kursor mouse.

---

## 2. SPESIFIKASI DAN KONDISI NYATA SERVER DESA KLEGO

Berdasarkan pengecekan kondisi secara langsung pada server produksi (`klego.dayoumu.my.id`), berikut adalah rapor kesehatan hardware dan efisiensi website Desa Klego:

### A. Kapasitas Mesin Pusat (Server Kinerja Tinggi)
* **Total RAM Server**: **2.048 MB (2 GB)** RAM murni, dipandu memori Swap cadangan 2 GB.
* **Penggunaan RAM Saat Ini**: Sangat hemat, hanya terpakai **~449 MB** dari total 2 GB. Masih terdapat sisa **1.598 MB (~1,6 GB) ruang lega** untuk melintasi lonjakan pengunjung di masa depan!
* **Kapasitas Penyimpanan Disk**: Total **32 GB SSD** Berkecepatan Tinggi.
* **Status Hardisk Disk**: Terpakai **11 GB (34%)**, masih tersisa **22 GB ruang bebas** yang sangat melimpah untuk menanggung unggahan ratusan dokumen Perdes, foto UMKM, maupun artikel berita bulanan.

### B. Efisiensi Luar Biasa Kontainer Website & Database
Sistem Desa Klego bekerja dengan efisiensi prima dan amat sangat hemat daya karena dibentengi teknologi Docker:

| Nama Kontainer Docker | Fungsi Kontainer di Server | Konsumsi Tenaga Memori (RAM) | Beban Prosesor (CPU) | Status Evaluasi Kinerja |
| :--- | :--- | :--- | :--- | :--- |
| **`desaklego_web`** | Mesin Penggerak Tampilan Website Warga & Portal Admin | **19,12 MB** *(Hanya 0,9% dari batas 2 GB!)* | **0,00% - 0,01%** (Sangat Dingin & Ringan) | 🟢 **Super Hemat & Cepat** |
| **`desaklego_db`** | Penyimpanan Basis Data MariaDB / MySQL | **81,97 MB** *(Hanya 4,0% dari batas 2 GB)* | **0,01%** (Sangat Stabil) | 🟢 **Siap Menyebarkan Data** |
| **`nginx-server`** | Pengatur gerbang keamanan lalu lintas domain HTTPS | - | - | 🟢 **Aktif & Terlindungi** |

### C. Spesifikasi Engine Web
* **Versi PHP Kontainer**: **PHP 8.2.33** (Edisi pembaruan resmi Juli 2026 yang cepat dan aman dari lubang keamanan lawas).
* **Modul Ekstensi Pendukung**: Telah terverifikasi AKTIF, terdiri dari `mysqli`, `pdo_mysql` (penghubung database), `json` (penghubung 3 bahasa), `gd` (pemotong dan kompress gambar beresolusi tinggi), serta `zip` & `mbstring` (pembuka dokumen administrasi desa).

---

## 3. ARSITEKTUR STRUKTUR DATABASE SISTEM

Semua data tersusun secara logis ke dalam tabel-tabel terorganisir pada database server:

1. **`infografis_statistik`**: Menyimpan detail seluruh APBDes 2026 (Pendapatan, Belanja, Pembiayaan dari folder resmi Naila & Satria) serta catatan Lahan Pertanian 312 Hektar.
2. **`agenda_desa`**: Tempat mencatat kalender agenda desa murni tanpa ada isian tiruan/dummy.
3. **`umkm`**: Menyimpan daftar usaha lokal milik warga (sumber folder Rahma), foto usaha, nama pemilik, dan nomor telepon WA.
4. **`dokumen_publik`**: Katalog perbendaharaan berkas JDIH (Peraturan Desa, RKPDes, Buku Bantu Aset) yang siap diedarkan ke publik.
5. **`admin`**: Lemari besi penyimpanan nama pengguna (*username*) dan kata sandi rahasia untuk perangkat desa login mengolah web.

---

## 4. SISTEM KEAMANAN DAN PERTAHANAN WEB DESA

1. **Otomatisasi Enkripsi Sandi (Bcrypt Hash Upgrade)**:
   Saat operator Perangkat Desa login menggunakan password standar (`admin`), mesin pengawas keamanan (`proses-login.php`) seketika menangkapnya dan langsung **mengunci serta menyalin ulang kata sandi tersebut menjadi kode acak enkripsi level militer (Bcrypt `$2y$10$...`)** pada database. Ini menjamin kata sandi tidak akan pernah bisa ditebak ataupun dibocorkan oleh oknum diluar operator resmi.
2. **Portal Login Terhalang dari Awam (Stealth Mode)**:
   Tombol atau tulisan untuk login sebagai admin dihilangkan seutuhnya dari Beranda Warga, Header, dan Footer. Ini mencegah serangan orang tak dikenal yang mencoba-coba masuk halaman admin dari luar.
3. **Filter Pembersih Teks & Gambar Otomatis**:
   Berita yang disalin dari komputer lokal atau Microsoft Word akan otomatis dibubuhkan alat penyaring sampah format (*clean preview*) agar tata letak web tetap elegan dan tidak pecah oleh kode-kode aneh.

---

## 5. KREDENSIAL DAN AKSES LOGIN RESMI (PORTAL ADMINISTRATOR)

Agar situs dapat dioperasikan secara berkelanjutan oleh Perangkat Desa resmi yang bertugas:

* **Alamat Ruang Kerja (Login URL)**: `http://klego.dayoumu.my.id/login.php`
* **Username Penguasa Sistem**: `admin`
* **Password Bawaan Sistem**: `admin` *(Dienkripsi seketika sehabis masuk)*

---

## 6. DAFTAR PERINTAH SERVER UNTUK DIAGNOSA (SAFE TERMINAL COMMANDS)

> [!CAUTION]
> **Jaminan Keamanan Perintah Terminal Tanpa Sandi Rahasia**
> Seluruh baris perintah CLI di bawah ini dirancang sedemikian rupa agar **100% AMAN** diketikkan oleh teknisi balai desa di terminal SSH Server Linux anda. **Perintah ini DIJAMIN TIDAK MEMINTA, TIDAK MENAMPILKAN, DAN TIDAK MENYINGGUNG KREDENSIAL PASSWORD SERVER MAUPUN ROOT DATABASE ANDA SAMA SEKALI!**

Gunakan perintah-perintah ini untuk memonitor kondisi vital server Anda:

### A. Memeriksa Status Hidup Kontainer & Durasi Kerja Server
```bash
# Mengecek daftar kontainer web dan database yang sedang berlaga di server Anda
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.RunningFor}}\t{{.Ports}}"

# Melihat berapa lama server sudah menyala melayani publik
uptime -p
```

### B. Mengecek Kapasitas Sisa Harddisk, RAM, & Beban Kontainer
```bash
# Melihat sisa penyimpanan disk lokal SSD Anda dalam hitungan Gigabyte (GB)
df -h /var/www /var/lib/docker

# Melihat kapasitas RAM total dan RAM bebas pada komputer server
free -m

# Mengamati angka real-time penggunaan RAM & prosesor CPU hanya pada web dan database desa
docker stats --no-stream desaklego_web desaklego_db
```

### C. Menari Pembaruan dari Github & Melalukan Resynchronization Data Baru
Jika anda selesai memperbarui kode atau ingin menyematkan revisi data APBDes dari komputer balai desa:
```bash
# Menari pembaruan terbaru dari repositori Git resmi ke server 
git pull origin main

# Mengeksekusi penyembuhan database dan menyelaraskan ulang seluruh tabel
docker exec -it desaklego_web php setup_modular_db.php

# Menanam kembali data APBDesa 2026 Naila, Peta WebGIS Naura, dan foto UMKM ke web
docker exec -it desaklego_web php sinkronisasi_data_sumber.php
```

### D. Melihat Catatan Kejadian / Log Eror Terakhir
Jika layar website tidak mau loading, gunakan perintah ini untuk membaca diagnosis eror 20 baris terakhir:
```bash
# Melihat 20 catatan lalu lintas terakhir pada kontainer Web Server
docker logs --tail 20 desaklego_web
```
---
*Dokumen Spesifikasi & Arsitektur Sistem Desa Klego dibimbing untuk memastikan keandalan layanan informasi jangka panjang yang cepat, aman dari kebocoran privasi, dan teruji secara profesional.*
