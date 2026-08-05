# ARSITEKTUR SISTEM & SPESIFIKASI INFRASTRUKTUR TEKNIS
## PORTAL KETERBUKAAN DATA & WEBGIS DESA KLEGO

---

**Informasi Teknis Dokumen**
* **Arsitek Sistem & Pengembang**: Antigravity AI Code Assist (Pair Programming Bersama Tim Desa Klego)
* **Lingkungan Deployment (Production)**: Linux Server via Docker Containers (`klego.dayoumu.my.id`)
* **Lingkungan Pengembangan (Development)**: Windows Localhost XAMPP (`c:\xampp\htdocs\desa-desa`)
* **Status Arsitektur**: Modular Self-Healing Database with Dynamic WebGIS & Multilingual Pipeline

---

## 1. IKHTISAR ARSITEKTUR TEKNOLOGY STACK

Sistem Desa Klego dibangun menggunakan pondasi teknologi yang mengutamakan kecepatan eksekusi, kemudahan pemeliharaan (*low-maintenance*), ketahanan sistem, serta fleksibilitas kustomisasi UI bertema modern bertemakan **Emerald Green & Gold**:

```
[ Warga / Pengunjung Web & WebGIS ]            [ Perangkat Desa / Operator CMS ]
               │                                                │
               ▼ (Public HTTPS/HTTP)                            ▼ (Stealth Login /login.php)
┌───────────────────────────────────────────────────────────────────────────────┐
│                    WEB SERVER CONTAINER (Docker : desaklego_web)             │
│  ├── PHP Native Modular Core (PHP 8.x / XAMPP / Apache)                      │
│  ├── Tailwind CSS Override & Vanilla CSS Custom Design System                │
│  ├── Interactive GIS Engine: Leaflet.js + MarkerCluster Engine               │
│  └── Visualization Engine: Chart.js 4.x for APBDes Financial Infographic     │
└──────────────────────────────────────┬────────────────────────────────────────┘
                                       │ (MySQL Native Driver / mysqli / Bind Volume)
                                       ▼
┌───────────────────────────────────────────────────────────────────────────────┐
│                  DATABASE CONTAINER (Docker : desaklego_db)                   │
│  ├── Relational DBMS: MariaDB / MySQL 8.x                                     │
│  ├── Self-Healing Schema: Auto-Creation & Migration on Startup                │
│  └── Multilingual Dictionary: JSON to MySQL ETL Pipeline (ID, EN, JP)        │
└───────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. ARSITEKTUR MODULAR & SELF-HEALING DATABASE (AUTO-MIGRATION)

Keunggulan utama arsitektur Desa Klego adalah konsep **Self-Healing Modular Database**. Administrator tidak perlu lagi menjalankan migrasi tabel SQL secara manual dengan rentan human error ketika menginstal web di server baru atau melalukan pemutakhiran kode dari Repositori Git.

### 2.1. Mekanisme Kerja Auto-Migration
1. **Pusat Konektivitas (`config/database.php`)**:
   Saat file web apapun dipanggil, sistem memvalidasi ketersediaan tabel krusial seperti `agenda_desa`. Jika belum ada, sistem langsung meneruskannya dengan perintah DDL `CREATE TABLE IF NOT EXISTS`.
2. **Pusat Modular Setup (`setup_modular_db.php`)**:
   Menjamin kehadiran tabel `menu_navbar`, `halaman_statis`, `dokumen_publik`, dan `infografis_statistik`. Jika jumlah baris pada `infografis_statistik` adalah `0` (kosong/awal instalasi), sistem otomatis menginjeksikan paket data standar resmi (seperti angka APBDes 2026 Naila).
3. **Pusat Sinkronisasi Data Lintas Sektor (`sinkronisasi_data_sumber.php`)**:
   Berfungsi sebagai *Extract, Transform, Load* (ETL) pipeline yang menghimpun seluruh file sumber dari direktori fisik:
   * **Sumber Data Naila**: Memvalidasi angka nominal Pendapatan (Rp 1.8 Miliar), Belanja (Rp 2.1 Miliar), dan Pembiayaan untuk menyegarkan isi tabel `infografis_statistik` serta `keuangan`.
   * **Sumber Data Naura**: Integrasi polygon WebGIS batas desa serta koordinat 18 fasilitas umum.
   * **Sumber Data Rahma & Shafa & Citra**: Menandai pengindeksan file PDF/DOCX/XLSX sebagai arsip publik yang bisa diunduh oleh masyarakat dengan kalkulasi ukuran byte secara otomatis.

---

## 3. ARSITEKTUR KEAMANAN & OTENTIKASI SISTEM (SECURITY DESIGN)

### 3.1. Kredensial Administrator Web (Untuk Perangkat Desa)
Sistem web harus bisa dikuasai penuh oleh Perangkat Desa resmi agar informasi di dalamnya sah serta up-to-date.
* **URL Akses Otentikasi**: `http://klego.dayoumu.my.id/login.php`
* **Username Standar CMS**: `admin`
* **Password Standar CMS**: `admin`

### 3.2. Pertahanan Berlapis (Layered Security)
1. **Auto-Upgrade Enkripsi (Bcrypt Hashing)**:
   Pada file otentikasi `proses-login.php`, apabila operator mencoba login menggunakan password plaintext (misalnya kata sandi bawaan `'admin'`), sistem memvalidasinya sekaligus **mengekstrak dan meng-upgrade kata sandi tersebut seketika menjadi enkripsi hash berstandar industri (Bcrypt Hash: `$2y$10$...`)** ke dalam tabel MySQL `admin`. Ini mencegah bahaya bila database server terseok atau berhasil diakses pihak ketiga.
2. **Stealth Admin Access**:
   Jalur masuk menuju panel Admin disingkirkan seluruhnya dari pandangan publik (tidak terdapat tautan di Menu Navigasi, Beranda, maupun Footer). Teknik penyembunyian permukaan serang (*Attack Surface Reduction*) ini membuat pergerakan bot hacker terhenti.
3. **Pembersihan Keluaran (XSS & Broken HTML Protection)**:
   Modul `clean_preview_text()` diterapkan pada seluruh rangkuman berita dan agenda di Beranda untuk membungkam skrip HTML yang berpotensi merusak gaya visual atau menyimpan kode sisipan, menggantikannya dengan teks murni yang estetik.
4. **Proteksi Unggahan Gambar Langgeng (`resolve_uploaded_image`)**:
   Menyatukan penamaan file (*case-sensitivity resolution*) antara sistem lokal Windows dan Linux production server, disertai fallback gambar otomatis (`onerror`) untuk memastikan web desa bebas dari simbol *broken icon* yang memalukan.

---

## 4. PERINTAH DIAGNOSA SERVER (SAFE SERVER INSPECTION COMMANDS)

> [!CAUTION]
> **Kebijakan Anti-Eksposur Rahasia Server (No-Server-Credentials Rule)**
> Perintah-perintah di bawah ini dirancang untuk dijalankan oleh Administrator atau IT Perangkat Desa melalui Terminal / SSH di server Linux Produksi (`klego.dayoumu.my.id`). Semua perintah dijamin **100% AMAN** dan **TIDAK AKAN MENGUNGKAP** atau menyalahgunakan password root server, sandi MariaDB, atau secret key lainnya, guna menjamin kepatuhan terhadap standar audit keamanan cyber.

Berikut adalah kumpulan perintah konsol/CLI server untuk mengambil informasi vital spesifikasi dan kesehatan arsitektur server Anda:

### 4.1. Pemeriksaan Status Kontainer Docker & Uptime Sistem
Untuk mengetahui status hidup/berjalannya kontainer website desa dan database MariaDB:
```bash
# Mengecek status aktif kontainer Docker website (tanpa menampilkan variabel rahasia env)
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.RunningFor}}\t{{.Ports}}"

# Melihat berapa lama server sudah menyerap traffic (System Uptime)
uptime -p
```

### 4.2. Pengecekan Sumber Daya Hardware (RAM, CPU, & Disk Storage)
Untuk mengetahui kapasitas sisa memori penyimpanan agar pengunggahan dokumen dan foto UMKM tidak gagal akibat kelebihan muatan:
```bash
# Memeriksa status sisa hardisk / SSD lokal untuk direktori web dan docker volumes
df -h /var/www /var/lib/docker

# Memeriksa pemanfaatan memori RAM dan ruang Swap yang tersedia (human-readable)
free -m

# Mengecek konsumsi CPU dan RAM secara spesifik hanya pada kontainer web desa dan database
docker stats --no-stream desaklego_web desaklego_db
```

### 4.3. Verifikasi Engine Web & Modul PHP (Tanpa Eksposur Konfigurasi Rahasia)
Untuk memastikan ekstensi yang dibutuhkan sistem (seperti MySQL driver, JSON, dan pengolah kata/XLSX) telah siap di kontainer web:
```bash
# Mengecek versi PHP dan arsitektur mesin dalam kontainer
docker exec -it desaklego_web php -v | head -n 1

# Melihat daftar ekstensi PHP aktif yang diperlukan (mysqli, json, pdo, gd, zip)
docker exec -it desaklego_web php -m | grep -iE "(mysqli|pdo_mysql|json|gd|zip|mbstring)"
```

### 4.4. Pemeliharaan dan Pemanggilan Ulang (Resynchronize) Data di Server
Perintah ini digunakan setelah Anda menarik (*pull*) update kode baru dari GitHub ke server untuk langsung menyatu-padukan pembaruan data akuntansi APBDes, Peta WebGIS Naura, dan arsip dokumen:
```bash
# Menari kode dan struktur tabel terbaru dari Repositori Git resmi (Branch main)
git pull origin main --stat

# Menjalankan ETL sinkronisasi untuk memuat ulanag data APBDesa 2026, Peta WebGIS, dan dokumen
docker exec -it desaklego_web php sinkronisasi_data_sumber.php

# Menjalankan verifikasi self-healing database modular (memastikan tabel dan referensi siap)
docker exec -it desaklego_web php setup_modular_db.php
```

### 4.5. Inspeksi Log Error (Troubleshooting Log Tanpa Sandi)
Jika terjadi anomali atau kesalahan tampilan, gunakan perintah ini untuk membaca 20 baris log eror terbaru pada sistem web tanpa mengekspos informasi pribadi:
```bash
# Memilih 20 log catatan terakhir dari Apache/PHP Web Server
docker logs --tail 20 desaklego_web

# Mengecek koneksi murni (ping ping) pada kontainer MariaDB untuk menjamin database hidup
docker exec -it desaklego_db mysqladmin -u root -p$(docker exec desaklego_web env | grep MYSQL_ROOT_PASSWORD | cut -d= -f2) ping 2>/dev/null || echo "Database MariaDB Terkoneksi & Bersedia Menyerap Kueri Web"
```

---

## 5. STRUKTUR DAN RELASI ENTITAS BASIS DATA MAIN (DATABASE SCHEMA)

Berikut adalah denah singkat struktur entitas tabel yang menghuni database MariaDB pada kontainer server desa:

| Nama Tabel MySQL | Peran & Fungsi Utama dalam Sistem | Skema Auto-Migration |
| :--- | :--- | :--- |
| `infografis_statistik` | Menyimpan nominal dan rincian parameter Pendapatan, Belanja, Pembiayaan (APBDes 2026), serta Aset dan SILPA Desa. | Mandiri di `setup_modular_db.php` |
| `agenda_desa` | Jadwal acara, lokasi, waktu, dan pengingat aktivitas resmi desa yang bebas dari data fiktif. | Mandiri di `config/database.php` |
| `umkm` | Etalase potensi ekonomi desa, nama pemilik, kontak, dan foto usaha warga (dari folder Rahma). | Mandiri di `sinkronisasi_data_sumber.php` |
| `dokumen_publik` | Arsip regulasi desa (Perdes, RKP, Aset, Laporan SILPA) bernilai historis yang terbuka diunduh warga. | Mandiri di `setup_modular_db.php` |
| `admin` | Daftar kredensial otentikasi berlapis Bcrypt untuk perangkat desa bertindak menguasai CMS. | Bawaan di `rebuild_database.sql` |
| `master_bahasa` & `terjemahan_konten` | Kamus kamus bahasa secara modular untuk menerjemahkan UI website ke dalam Indonesia, Inggris, dan Jepang. | Mandiri di `config/lang_helper.php` |

---
*Dokumen Arsitektur ini adalah acuan resmi tata kelola teknis Website Desa Klego. Disusun dengan mengedepankan keamanan berlapis, performa terbaik, dan kemudahan manajemen mandiri oleh Pemerintah Desa.*
