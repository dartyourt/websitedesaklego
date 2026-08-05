# BUKU PANDUAN PENGGUNA (USER MANUAL)
## SISTEM WEBSITE DAN PORTAL RESMI DESA KLEGO
**Kabupaten Boyolali, Jawa Tengah**

---

**Informasi Dokumen**
* **Judul Sistem**: Website Resmi dan Portal Informasi Desa Klego
* **Alamat Website**: `http://klego.dayoumu.my.id`
* **Sasaran Pembaca**: Perangkat Desa, Operator Balai Desa, dan Masyarakat Umum
* **Versi Dokumen**: 2.1 (Dilengkapi Langkah Detail & Keterangan Gambar)

---

## 1. PENDAHULUAN & KAMUS ISTILAH (GLOSARIUM)

Buku panduan ini dibuat untuk membantu Perangkat Desa Klego dalam mengelola, mengubah, dan menambah informasi di website desa dengan mudah, tepat, dan cepat, tanpa perlu memahami pemrograman atau kode teknis.

### Kamus Istilah Pengantar (Glosarium untuk Awam)
Agar gampang dipahami, berikut arti dari kata-kata khusus yang sering muncul dalam panduan ini:
* **Website / Situs Web**: Kumpulan halaman informasi di internet yang dapat dibuka melalui komputer atau HP (seperti Google Chrome atau Microsoft Edge).
* **Browser (Peramban)**: Aplikasi untuk membuka internet di HP atau laptop, misalnya Google Chrome, Mozilla Firefox, Safari, atau Microsoft Edge.
* **Link (Tautan / URL)**: Alamat teks yang diketik di bagian atas browser untuk membuka halaman tertentu (contoh: `klego.dayoumu.my.id`).
* **Login (Masuk Akun)**: Proses memasukkan nama pengguna (*username*) dan kata sandi (*password*) agar perangkat desa bisa masuk ke ruang kendali website.
* **CMS (Content Management System) / Portal Admin**: Ruang kerja khusus di website yang hanya bisa dibuka oleh perangkat desa untuk mengedit isi website (menulis berita, mengubah uang APBDes, dll).
* **Database (Basis Data)**: Tempat penyimpanan rahasia di dalam server tempat seluruh catatan teks, angka APBDes, daftar warga, dan agenda disimpan dengan aman.
* **Frontend (Tampilan Warga)**: Bagian depan website yang dilihat oleh seluruh masyarakat umum saat membuka situs desa.
* **Upload (Unggah)**: Proses memasukkan file foto atau dokumen dari laptop/HP Anda ke dalam website desa.
* **Refresh (Muat Ulang / F5)**: Memerintahkan browser untuk mengambil tampilan website terbaru setelah dilakukan perubahan.
* **Dropdown (Menu Pilihan Drop)**: Kotak menu pada formulir yang jika diklik akan memunculkan ke bawah daftar pilihan yang bisa dipilih.

---

## 2. CARA LOGIN KE PORTAL ADMINISTRATOR DESA

Sesuai aturan keamanan agar website desa tidak dibajak oleh pihak yang tidak bertanggung jawab, **tautan atau tombol untuk Login Admin sengaja TIDAK DITAMPILKAN di halaman depan website**. Hanya anda selaku Perangkat Desa yang boleh tahu cara membukanya.

### 2.1. Langkah Masuk (Login) ke Dalam Web
1. Buka aplikasi **Browser** (Google Chrome / Edge / Firefox) di laptop atau HP Anda.
2. Di bagian atas browser (kolom alamat), ketikkan alamat rahasia ini:
   * **`http://klego.dayoumu.my.id/login.php`**
   * (Atau jika sedang dicoba offline tanpa internet di laptop balai desa, ketik: `http://localhost/desa-desa/login.php`)
3. Tekan tombol **Enter** pada keyboard. Layar Anda akan memunculkan kotak form login admin.
4. Masukkan nama akun bawaan berikut ke kolom yang tersedia:
   * **Username**: `admin`
   * **Password**: `admin`
5. Klik tombol **Masuk / Login**. Jika berhasil, Anda akan dibawa ke halaman utama (Dasbor) Portal Admin bertema Hijau Emerald dan Emas.

> 🖼️ **[Tempat Gambar / Screenshot 1: Form Login Admin Desa]**
> *Keterangan Gambar: Tampilan halaman login rahasia perangkat desa. Terdapat kolom input untuk mengisi username dan password, serta tombol hijau untuk masuk.*

---

## 3. PANDUAN MENGELOLA ISI WEBSITE (PORTAL ADMIN)

Di bagian atas atau tengah Dasbor Admin, Anda akan melihat kotak-kotak menu utama untuk mengatur berbagai jenis berita dan informasi di desa.

### 3.1. Manajemen Angka Keuangan & APBDesa (Infografis)
Menu ini dipakai untuk mengedit diagram batang, grafik lingkaran, dan angka transparansi keuangan (APBDes, SILPA, dan Aset) yang tayang di beranda warga.

#### A. Cara Menambah Data Anggaran Baru
1. Di dasbor Admin, klik kartu menu **"Keuangan & APBDesa"**. Anda akan melihat daftar tabel uang APBDes yang sudah ada.
2. Klik tombol hijau berbunyi **"+ Tambah Data APBDes / Infografis"** di bagian kiri atas tabel.
3. Anda akan masuk ke halaman formulir isian data. Isi kolom sebagai berikut:
   * **Kategori Keuangan**: Klik kotak dropdown lalu pilih kelompoknya. Apakah termasuk *Pendapatan APBDes 2026*, *Belanja APBDes 2026*, *Pembiayaan APBDes 2026*, atau *SILPA & Aset 2025*.
   * **Uraian / Label Parameter**: Ketik nama kegiatannya (Contoh: `Dana Desa` atau `Pembangunan Jalan Dukuh Klego`).
   * **Nilai Nominal / Angka**: Ketik uang rupiahnya **tanpa titik, tanpa koma, dan tanpa tulisan Rp**. Contoh: Jika angkanya Rp 150.000.000, maka Anda **cukup ketik 150000000**.
   * **Satuan**: Biarkan berisi `Rp` (atau ganti dengan `Hektar` khusus untuk data ukuran tanah sawah).
   * **Tahun Anggaran**: Biarkan terisi `2026` (atau sesuai tahun anggaran bersangkutan).
   * **Urutan Tampil**: Isi angka urutan, misalnya `1` atau `2` untuk urutan muncul di tabel layar.
   * **Warna Grafik**: Klik kotak warna kecil lalu pilih warna yang kontras agar grafik di depan kelihatan rapi dan indah dibaca warga.
4. Klik tombol **"Simpan Data APBDes"**. System akan memberi tahu bahwa data sukses tersimpan.

> 🖼️ **[Tempat Gambar / Screenshot 2: Formulir Tambah Data APBDesa]**
> *Keterangan Gambar: Tampilan formulir untuk mengisikan parameter baru ke dalam infografis anggaran desa. Perhatikan pengisian nominal rupiah yang ditulis utuh tanpa titik atau koma.*

#### B. Cara Mengubah (Edit) Angka APBDes
Jika ada revisi anggaran (misal setelah Rapat PAK / Perubahan APBDes):
1. Masuk ke menu **"Keuangan & APBDesa"**.
2. Cari nama anggaran yang ingin diganti nilainya pada tabel.
3. Di kolom paling kanan (kolom Aksi), klik tombol biru bertuliskan **"Edit"**.
4. Ganti nominal atau namanya sesuai kebutuhan baru.
5. Klik tombol **"Perbarui Data APBDes"**. Angka di grafik halaman depan akan otomatis berubah saat itu juga.

---

### 3.2. Manajemen Agenda Kegiatan Desa
Agenda berfungsi sebagai papan pengingat jadwal kegiatan resmi balai desa (contoh: *Jadwal Posyandu Balita, Rapat RW, atau Kerja Bakti Desa*).

#### Cara Menerbitkan Agenda Baru:
1. Di halaman utama Admin, klik menu **"Agenda Kegiatan Desa"**.
2. Klik tombol **"+ Tambah Agenda"**.
3. Lengkapi 4 kolom penting berikut:
   * **Judul Agenda**: Tulis nama acara, misal `Penyuluhan Pertanian Bersama Tim Shafa & Balai Desa`.
   * **Tanggal Acara**: Pilih tanggal pelaksanaannya pada kalender yang muncul.
   * **Waktu / Jam**: Ketikan jam kegiatan, misal `08.00 WIB - Selesai`.
   * **Lokasi Acara**: Ketikan tempatnya, misal `Balai Desa Klego, Lantai 1`.
4. Tekan tombol **"Simpan"**. Agenda tersebut kini resmi tampil di daftar acara beranda website.

> 🖼️ **[Tempat Gambar / Screenshot 3: Pengelolaan Agenda Desa]**
> *Keterangan Gambar: Tampilan daftar agenda kegiatan desa yang akurat dan asli dari jadwal kegiatan resmi pemerintah desa.*

---

### 3.3. Manajemen Berita & Artikel Desa
Fitur ini berguna untuk meliput kegiatan desa, perkembangan UMKM, serta edukasi kesehatan agar website kelihatan aktif dan hidup.

#### Cara Menulis Berita:
1. Klik kartu menu **"Berita & Artikel"** di dasbor Anda.
2. Klik tombol **"Tambah Berita Baru"**.
3. Ketikkan **Judul Berita** yang menarik di atas.
4. Pada kotak **Isi Berita**, ketikkan atau salin teks berita Anda.
   * **Catatan Rapi & Otomatis**: Jangan khawatir jika teksnya panjang! Website kita sudah dibekali teknologi pembersih ringkasan (*clean preview*). Website secara otomatis memotong ringkasan dengan rapi pada kartu beranda tanpa memunculkan simbol aneh.
5. Pada bagian **Foto Utama / Thumbnail**, klik tombol **Pilih File / Browse** lalu pilih foto dokumentasi kegiatan dari laptop/HP Anda (format foto yang disarankan: `.jpg`, `.png`, atau `.webp`).
6. Klik tombol **"Publikasikan Berita"**.

> 🖼️ **[Tempat Gambar / Screenshot 4: Form Tulis Berita & Upload Gambar]**
> *Keterangan Gambar: Proses penulisan artikel berita baru beserta pengunggahan foto pendukung liputan.*

---

### 3.4. Manajemen Dokumen Regulasi (Peraturan & Keterbukaan Publik)
Menu ini memegang daftar dokumen penting (Perdes, RPJM Desa, Laporan SILPA, Data Pertanian Shafa, dan Data APBDes Naila) agar bisa didownload (diunduh) oleh masyarakat umum.

1. Buka menu **"Dokumen / Keterbukaan Publik"**.
2. Anda dapat melamar atau mendaftarkan file dokumen berformat `.pdf`, `.docx`, atau `.xlsx`.
3. Sistem secara pintar dan otomatis mengukur ukuran file (misal: `245 KB` atau `1.2 MB`) serta memberi tombol "Unduh / Download" berwarna hijau pada layar pembaca umum di beranda.

---

### 3.5. Pengarah Bahasa & Terjemahan Otomatis (Indonesia, Inggris, Jepang)
Website Desa Klego sudah mendukung 3 bahasa agar desa kita mudah dilestarikan dan dikenal baik oleh masyarakat lokal maupun calon investor asing.

1. Di menu utama Admin, jika perlu memperbarui terjemahan, Anda cukup membuka modul **"Sinkronisasi Bahasa"** atau memeriksa tabel kosakata.
2. Semua kata, mulai dari tombol navigasi, keterangan sebutan APBDes, hingga cerita sejarah, akan langsung berubah ke bahasa **Inggris** atau **Jepang** sesaat setelah pengunjung mengeklik lambang bendera di sudut atas website.

> 🖼️ **[Tempat Gambar / Screenshot 5: Pengalih Tiga Bahasa di Website Desa]**
> *Keterangan Gambar: Ikon tombol bendera Indonesia, Inggris, dan Jepang yang terletak di atas web untuk mengubah bahasa secara otomatis dan akurat.*

---

## 4. PANDUAN MENGGUNAKAN FITUR PETA WEBGIS (KHUSUS WARGA)

Selain membaca berita, masyarakat umum juga bisa berinteraksi dengan **Peta Desa Klego Digital** yang berada di halaman depan (Beranda) tepat setelah blok Statistik Demografi.

### Langkah Membaca dan Mengoperasikan Peta:
1. Geser layar halaman depan website ke bawah sampai menemukan kotak **Peta Interaktif Wilayah & Fasilitas Desa Klego**.
2. Anda akan melihat garis lingkar warna teal/biru kehitaman; itu adalah **Polygon Batas Administrasi Resmi Desa Klego**.
3. Pada gambar peta, terdapat titik-titik ikon marker fasiltas umum:
   * **Ikon Masjid / Mushola**: Lokasi sarana ibadah.
   * **Ikon Sekolah / Toga**: Lokasi SD, SMP, dan madrasah.
   * **Ikon Palang Merah / Puskesmas**: Lokasi pusat layanan kesehatan & posyandu.
   * **Ikon Gedung / Kantor**: Lokasi Kantor Kepala Desa & Balai Desa Klego.
   * **Ikon Pasar & Taman**: Lokasi pasar klego dan ruang terbuka hijau.
4. **Cara Minta Petunjuk Arah (GPS)**:
   * Klik salah satu ikon marker tersebut di layar Anda.
   * Akan muncul kotak pesan (*popup*) bertuliskan nama fasilitas dan tombol **"Buka Google Maps"**.
   * Klik tombol itu, maka HP Anda akan langsung membuka aplikasi navigasi Google Maps membimbing Anda ke lokasi tersebut.
5. **Kontrol Tampilan Peta (Anti-Menumpuk)**:
   * Di sudut **Kiri Bawah**, terdapat tabel Legenda Simbol (bisa distimulasikan agar menyusup kecil).
   * Di sudut **Kanan Atas**, terdapat tombol lapis gambar (bisa diklik untuk mengubah latar peta menjadi Foto Satelit Asli dari luar angkasa atau Peta Jalan Klasik).

> 🖼️ **[Tempat Gambar / Screenshot 6: Peta Digital WebGIS Desa Klego]**
> *Keterangan Gambar: Tampilan peta interaktif Desa Klego di halaman utama dengan marka batas wilayah dan ikon fasillitas yang siap diklik.*

---

## 5. SOLUSI MENGATASImasalah (TANYA JAWAB CEPAT)

**T: Saya mau mengelola website, tapi bingung cari tombol login admin di beranda web kok tidak ada?**
* **J:** Memang tidak dipasang demi keamanan dari pencurian. Solusinya: Klik bagian atas browser, lalu ketik manual ujung alamatnya bertajuk `/login.php` di belakang alamat website (contoh: `klego.dayoumu.my.id/login.php`), lalu klik Enter.

**T: Saya sudah merubah angka uang APBDes di Admin, kenapa pas buka halaman depan angkanya belum ganti?**
* **J:** Itu terjadi karena browser HP/Laptop Anda masih menyimpan memori tampilan lama (disebut cache). Solusinya: Tekan tombol **Refresh (F5)** pada keyboard atau tekan lambang panah putar melingkar di atas browser Anda agar website memanggil data yang baru.

**T: Apakah saya boleh asal menghapus item belanja desa di menu Infografis?**
* **J:** Sebaiknya HANYA menghapus item jika memang terjadi salah ketik dobel. Bila memang angkanya berganti karena rapat evaluasi baru, gunakan tombol **Edit (Perbarui)** saja agar urutannya tidak terganggu.

---
*Demikian buku panduan resmi operasional Website Desa Klego ini disusun. Mari kelola informasi desa dengan transparansi, semangat pelayanan gotong-royong, dan kebanggaan bersama!*
