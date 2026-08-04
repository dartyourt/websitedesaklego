# Website Resmi Desa Klego (`websitedesaklego`) 🏛️🇮🇩
**Sistem Informasi Pemerintahan, Pelayanan Persuratan Dinamis, & Portal Berita Desa Klego, Kecamatan Klego, Kabupaten Boyolali, Jawa Tengah.**

---

## 🌟 Tentang Proyek ini
**websitedesaklego** adalah aplikasi web Sistem Informasi Desa bertaraf profesional yang dibangun dengan arsitektur PHP Native teroptimasi, desain dinamis TailwindCSS, serta dukungan penuh untuk containerization (Docker & Cloudflare Zero Trust).
Proyek ini dirancang agar cepat, aman, responsif, dan mudah dikelola melalui Sistem Manajemen Konten (CMS) yang terpadu.

### ✨ Fitur Unggulan
1. **🏛️ Profil Desa Dinamis & Identitas Boyolali:** Pengaturan identitas daerah, lambang resmi Boyolali, struktur organisasi, dan profil desa dapat disesuaikan 100% melalui panel Admin.
2. **📝 Administrasi Persuratan Otomatis:** Template surat keterangan (Domisili, SKTM, Usaha, Pengantar Umum) yang langsung menghasilkan file siap cetak berhias kop surat resmi dan penandatangan dinamis (Kepala Desa/Sekdes).
3. **📊 Infografis Keuangan & Aset Desa:** Transparansi APBDes dan laporan realisasi keuangan yang disajikan dengan grafik visual atraktif bagi masyarakat.
4. **🌐 CMS Ala WordPress:** Pengaturan Menu Navbar dinamis, manajemen halaman (Halaman kustom dengan editor), dan multi-bahasa terintegrasi.
5. **🛡️ Keamanan Tingkat Lanjut:** Filter anti SQL-Injection pada lapisan autentikasi, enkripsi password menggunakan standar **Bcrypt**, dan proteksi sesi ketat di setiap titik mutasi data.

---

## 🔒 Standar Keamanan & Pengaturan Credentials
Dalam repositori ini, seluruh kredensial database telah dipindahkan ke **Environment Variables (`getenv`)**, sehingga tidak memaparkan password di dalam kode sumber:
- **`DB_HOST`** (Default dev: `localhost` | Default docker: `db`)
- **`DB_USER`** (Default dev: `root`)
- **`DB_PASS`** (Default dev: *kosong* | Default docker: diatur melalui environment compose)
- **`DB_NAME`** (Default: `desa_klego`)

> **Catatan Keamanan Akun Admin:**
> Saat pertama kali instalasi atau eksekusi dari file dump `rebuild_database.sql`, akun default administrator adalah:
> - **Username:** `admin`
> - **Password:** `admin`
> *(Sistem otomatis melakukan upgrade proteksi hash password Anda saat pertama kali berhasil login! Sangat disarankan untuk segera mengubah password bawaan ini di menu admin setelah deployment).*

---

## 🚀 Panduan Deployment Server (Docker + Cloudflare Zero Trust)
Aplikasi ini sudah dipersiapkan secara native untuk deployment di Linux Server tertutup (tanpa Public IP) menggunakan **Docker Compose** dan **Cloudflare Tunnels**.

### 1. Menyiapkan Proyek di Server Linux (misal: Server Kampus)
Buka terminal SSH di server Anda, lalu lakukan clone repositori atau transfer file ke direktori tujuan:
```bash
sudo mkdir -p /var/www/websitedesaklego
cd /var/www/websitedesaklego
# (Copy file / clone repo ke direktori ini)
```

### 2. Konfigurasi Keamanan (Opsional)
Buka file `docker-compose.yml` dan ubah password default database `rahasia_klego_2026` menjadi password super aman pilihan Anda:
```yaml
    environment:
      - MYSQL_ROOT_PASSWORD=PasswordSangatKuatAnda123!
      - MYSQL_DATABASE=desa_klego
```

### 3. Jalankan Kontainer Docker
Ekseksusi perintah berikut di dalam direktori proyek:
```bash
docker compose up -d --build
```
*Kontainer web (Apache/PHP 8.2) akan hidup di port **`8081`** (`http://localhost:8081`), dan kontainer MariaDB secara otomatis memvisualkan skema tabel & data awal `rebuild_database.sql`.*

### 4. Menambahkan Rute Cloudflare Zero Trust Tunnel
Tanpa perlu membuka port firewall atau menggunakan IP Publik:
1. Masuk ke dasbor **Cloudflare Zero Trust** -> **Networks** -> **Tunnels**.
2. Pilih Tunnel server Anda (contoh: `server-kampus`) -> Klik **Configure**.
3. Pilih tab **Public Hostname** -> Klik **+ Add a public hostname**.
4. Masukkan konfigurasi rute:
   - **Subdomain:** `klego`
   - **Domain:** `dayoumu.my.id` *(sesuaikan dengan domain yang Anda hubungkan)*
   - **Service Type:** `HTTP`
   - **URL:** `localhost:8081` *(Sesuai port web pada `docker-compose.yml`)*
5. Klik **Save hostname**. Cloudflare otomatis membuat DNS berstatus *Proxied* (SSL HTTPS diaktifkan otomatis).
6. Website Resmi Desa Klego kini dapat diakses secara publik melalui **`https://klego.dayoumu.my.id`**!

---

## 💻 Panduan Menjalankan di Localhost (XAMPP / Laragon)
Bagi pengembang yang ingin menjalankan tes di komputer lokal:
1. Pindahkan folder repositori ke `C:\xampp\htdocs\desa-desa` (atau `C:\laragon\www\desa-desa`).
2. Nyalakan modul **Apache** dan **MySQL/MariaDB** di Control Panel XAMPP/Laragon.
3. Buka browser ke `http://localhost/phpmyadmin` dan buat database dengan nama **`desa_klego`**.
4. Import file **`rebuild_database.sql`** ke dalam database tersebut.
5. Akses website melalui browser di `http://localhost/desa-desa/`.

---
*Dikembangkat khusus dan terdedikasi untuk kemajuan digitalisasi layanan masyarakat Desa Klego, Boyolali.* 🌾
