<?php
$pageTitle = "Direktori & Potensi UMKM Desa Klego";
include 'config/header.php';

$umkmList = [];
if (isset($conn) && $conn && !mysqli_connect_error()) {
    $result = @mysqli_query($conn, "SELECT * FROM umkm ORDER BY nama_usaha ASC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $umkmList[] = $row;
        }
    }
}

// Fallback data langsung dari naskah verifikasi Sumber Data Rahma bila database kosong / proses setup
if (empty($umkmList)) {
    $umkmList = [
        [
            'nama_usaha' => "Novi’s Kitchen",
            'pemilik' => "Sunarti",
            'telepon' => "081331449874",
            'produk' => "Nasi Kuning, Nasi Gudangan, Nasi Box, Nasi Tumpeng, Cotot, Getuk, Lemet, Aneka Snack, Pempek",
            'jenis' => "Makanan",
            'alamat' => "Kedokan RT 16 RW 04",
            'deskripsi' => "Usaha kuliner berkualitas untuk kebutuhan harian, syukuran, maupun pesanan acara formal.",
            'foto' => "umkm_1_Novi_s_Kitchen.jpg"
        ],
        [
            'nama_usaha' => "Kedai Es Nak Co Ger",
            'pemilik' => "Liyo Stowati",
            'telepon' => "087788606821",
            'produk' => "Aneka Minuman Teh, Es Teler, Es Campur, Mojito, Kopi Segar",
            'jenis' => "Kedai Minuman",
            'alamat' => "Dukuh Klego, RT 04 RW 01",
            'deskripsi' => "Menyediakan beragam minuman segar bercinta rasa premium untuk menemani santai di berbagai suasana.",
            'foto' => "umkm_2_Kedai_Es_Nak_Co_Ger.png"
        ],
        [
            'nama_usaha' => "Aneka Snack",
            'pemilik' => "Uji Permulani",
            'telepon' => "082226733132",
            'produk' => "Macam-macam snack kemasan, keripik tradisional, rempah kering",
            'jenis' => "Makanan Ringan",
            'alamat' => "Ruko Karanganyar, Klego",
            'deskripsi' => "Pilihan camilan gurih dan renyah bermutu tinggi untuk sajian keluarga maupun buah tangan oleh-oleh khas.",
            'foto' => "umkm_3_Aneka_Snack.jpeg"
        ],
        [
            'nama_usaha' => "Martabak Bangka 94",
            'pemilik' => "Fendi",
            'telepon' => "082328857940",
            'produk' => "Martabak manis spesial, terang bulan aneka topping, dan martabak telur renyah",
            'jenis' => "Makanan",
            'alamat' => "Jl. Raya Karanggede-Gemolong No. 3, Ngembat, Klego",
            'deskripsi' => "Martabak hangat berselera tinggi dengan bahan premium, keju melimpah, dan racikan telur spesial.",
            'foto' => "umkm_4_Martabak_Bangka_94.jpeg"
        ],
        [
            'nama_usaha' => "Tahu Crispy & Ayam Crispy",
            'pemilik' => "Muhammad Khoirul Humam",
            'telepon' => "08812480734",
            'produk' => "Tahu Crispy berempah, Ayam Crispy renyah tahan lama, sambal spesial",
            'jenis' => "Makanan",
            'alamat' => "Ngembat, Klego",
            'deskripsi' => "Hidangan tahu dan ayam goreng renyah berbumbu rempah pilihan yang disukai anak-anak hingga dewasa.",
            'foto' => "umkm_5_Tahu_Crispy_Ayam_Crispy.jpeg"
        ],
        [
            'nama_usaha' => "Delia Bakery",
            'pemilik' => "Delia",
            'telepon' => "081575890910",
            'produk' => "Aneka roti manis, kue ulang tahun custom, snack box harian, paket hantaran pernikahan",
            'jenis' => "Snack & Bakery",
            'alamat' => "Klego RT 04 RW 01, Boyolali",
            'deskripsi' => "Bakery rumahan berkualitas bersertifikat halal dan higienis. Melayani pesanan kue pesta dan paket hantaran.",
            'foto' => "umkm_6_Delia_Bakery.jpg"
        ]
    ];
}
?>

<!-- ================= HERO BANNER ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 relative overflow-hidden shadow-md">
    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fbbf24_1px,transparent_1px)] [background-size:20px_20px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <nav class="flex text-xs text-emerald-200/80 mb-4 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-2">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-white transition-colors"><?= tr('Beranda') ?></a>
                </li>
                <li>&bull;</li>
                <li><span class="text-amber-300 font-semibold"><?= tr('Potensi Ekonomi & Bisnis Warga') ?></span></li>
            </ol>
        </nav>
        <div class="max-w-3xl">
            <span class="text-xs font-mono uppercase tracking-widest bg-amber-400/20 text-amber-300 px-3 py-1 rounded-full border border-amber-400/40 inline-block mb-3">
                <?= tr('E-Katalog Resmi Pemerintah Desa') ?>
            </span>
            <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight leading-tight">
                <?= tr('Pusat Direktori & Produk Unggulan UMKM Klego') ?>
            </h1>
            <p class="text-emerald-100 text-sm sm:text-base mt-4 leading-relaxed">
                <?= tr('Wujud komitmen promosi ekonomi lokal dan kemandirian usaha warga. Temukan profil lengkap pelaku Usaha Mikro, Kecil, dan Menengah (UMKM), ragam produk olahan kuliner, dan pesan langsung tanpa perantara ke pengusaha lokal.') ?>
            </p>
        </div>
    </div>
</section>

<!-- ================= STATS & FILTER ================= -->
<section class="py-10 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-900 flex items-center justify-center text-xl font-bold shadow">
                <i class="fa-solid fa-shop"></i>
            </div>
            <div>
                <h2 class="font-heading font-bold text-slate-900 text-lg"><?= count($umkmList) ?> <?= tr('Usaha Lokal Unggulan Terverifikasi') ?></h2>
                <p class="text-xs text-slate-500"><?= tr('Dukung ekonomi desa dengan bertransaksi produk lokal berkualitas.') ?></p>
            </div>
        </div>
        
        <!-- PENCARIAN & FILTER CEPAT -->
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="filterUmkm('all', this)" class="filter-btn px-4 py-2 rounded-xl bg-emerald-800 text-white text-xs font-bold shadow transition-all">
                <?= tr('Semua Kategori') ?>
            </button>
            <button onclick="filterUmkm('makanan', this)" class="filter-btn px-4 py-2 rounded-xl bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold border border-slate-300 transition-all">
                <i class="fa-solid fa-bowl-food mr-1.5 text-amber-600"></i><?= tr('Makanan & Kuliner') ?>
            </button>
            <button onclick="filterUmkm('minuman', this)" class="filter-btn px-4 py-2 rounded-xl bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold border border-slate-300 transition-all">
                <i class="fa-solid fa-mug-hot mr-1.5 text-blue-600"></i><?= tr('Kedai & Minuman') ?>
            </button>
            <button onclick="filterUmkm('snack', this)" class="filter-btn px-4 py-2 rounded-xl bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold border border-slate-300 transition-all">
                <i class="fa-solid fa-cookie-bite mr-1.5 text-emerald-600"></i><?= tr('Snack & Bakery') ?>
            </button>
        </div>
    </div>
</section>

<!-- ================= GRID DIREKTORI UMKM ================= -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="umkmGrid">
        <?php foreach ($umkmList as $item): 
            $kategoriKunci = strtolower($item['jenis'] ?? 'makanan');
            if (str_contains($kategoriKunci, 'minum') || str_contains($kategoriKunci, 'kedai')) {
                $katClass = 'minuman';
            } elseif (str_contains($kategoriKunci, 'snack') || str_contains($kategoriKunci, 'ringan') || str_contains($kategoriKunci, 'roti') || str_contains($kategoriKunci, 'bakery')) {
                $katClass = 'snack';
            } else {
                $katClass = 'makanan';
            }

            // Normalisasi nomor telepon untuk format WhatsApp (+62...)
            $noTelp = preg_replace('/[^0-9]/', '', $item['telepon'] ?? '');
            if (str_starts_with($noTelp, '0')) {
                $waNumber = '62' . substr($noTelp, 1);
            } elseif (str_starts_with($noTelp, '8')) {
                $waNumber = '62' . $noTelp;
            } else {
                $waNumber = $noTelp;
            }

            $fotoPath = !empty($item['foto']) && file_exists('uploads/umkm/' . $item['foto']) 
                ? 'uploads/umkm/' . $item['foto'] 
                : (!empty($item['foto']) && file_exists('uploads/' . $item['foto']) ? 'uploads/' . $item['foto'] : null);
        ?>
        
        <article class="umkm-card <?= $katClass ?> bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm flex flex-col justify-between transition-all hover:-translate-y-1 hover:shadow-xl group">
            <div>
                <!-- FOTO UMKM -->
                <div class="h-52 bg-slate-100 relative overflow-hidden">
                    <?php if ($fotoPath): ?>
                        <img src="<?= htmlspecialchars($fotoPath) ?>" alt="<?= htmlspecialchars($item['nama_usaha']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-tr from-emerald-900 to-amber-600 flex items-center justify-center text-white text-5xl opacity-80">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-slate-900/90 backdrop-blur-xs text-white text-[11px] font-bold px-3 py-1 rounded-full border border-slate-700 shadow">
                        <?= htmlspecialchars($item['jenis'] ?? 'UMKM Warga') ?>
                    </div>
                </div>

                <!-- KONTEN -->
                <div class="p-6">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 mb-1">
                        <i class="fa-solid fa-user-check text-amber-500"></i>
                        <span><?= tr('Pemilik:') ?> <?= htmlspecialchars($item['pemilik'] ?? 'Warga Klego') ?></span>
                    </div>

                    <h3 class="font-heading font-extrabold text-xl text-slate-900 group-hover:text-emerald-700 transition-colors">
                        <?= htmlspecialchars($item['nama_usaha']) ?>
                    </h3>

                    <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                        <?= htmlspecialchars($item['deskripsi'] ?? '') ?>
                    </p>

                    <!-- DAFTAR PRODUK & JASA -->
                    <?php if (!empty($item['produk'])): ?>
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 block mb-1.5"><?= tr('Produk / Layanan Tersedia:') ?></span>
                        <div class="flex flex-wrap gap-1.5">
                            <?php 
                            $produks = explode(',', $item['produk']);
                            foreach (array_slice($produks, 0, 5) as $prod): 
                            ?>
                                <span class="bg-slate-100 text-slate-700 text-[11px] font-medium px-2 py-0.5 rounded-lg border border-slate-200"><?= htmlspecialchars(trim($prod)) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($produks) > 5): ?>
                                <span class="bg-amber-100 text-amber-900 text-[11px] font-bold px-1.5 py-0.5 rounded-lg">+<?= count($produks) - 5 ?> dll</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FOOTER AKSI -->
            <div class="p-6 pt-0 bg-white flex flex-col gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <i class="fa-solid fa-location-dot text-amber-600 flex-shrink-0"></i>
                    <span class="truncate" title="<?= htmlspecialchars($item['alamat'] ?? '') ?>"><?= htmlspecialchars($item['alamat'] ?? 'Klego, Boyolali') ?></span>
                </div>

                <?php if (!empty($waNumber)): ?>
                <a href="https://api.whatsapp.com/send?phone=<?= htmlspecialchars($waNumber) ?>&text=<?= urlencode('Halo kak ' . ($item['pemilik'] ?? 'Admin') . ', saya lihat profil ' . $item['nama_usaha'] . ' di Website Resmi Desa Klego, ingin bertanya mengenai pesanan produk/menu.') ?>" target="_blank" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs py-3 px-4 rounded-2xl flex items-center justify-center gap-2 shadow-md transition-all hover:scale-[1.02]">
                    <i class="fa-brands fa-whatsapp text-lg text-amber-300"></i>
                    <span><?= tr('Pesan Sekarang (WhatsApp)') ?></span>
                </a>
                <?php else: ?>
                <span class="w-full bg-slate-100 text-slate-400 font-bold text-xs py-3 px-4 rounded-2xl flex items-center justify-center gap-2">
                    <i class="fa-solid fa-phone"></i>
                    <span><?= tr('Kontak Belum Tersedia') ?></span>
                </span>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- ================= AJAKAN PENDAFTARAN UMKM ================= -->
<section class="py-16 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-950 text-white relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">
        <div class="w-16 h-16 rounded-3xl bg-amber-500 text-slate-900 flex items-center justify-center text-3xl mx-auto mb-6 shadow-xl">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <h2 class="font-heading font-bold text-2xl sm:text-4xl text-white"><?= tr('Punya Usaha di Desa Klego Tapi Belum Terdaftar?') ?></h2>
        <p class="text-sm sm:text-base text-emerald-200/80 mt-3 leading-relaxed">
            <?= tr('Pemerintah Desa Klego membuka kesempatan gratis bagi seluruh pelaku UMKM, kedai kuliner, kerajinan, dan jasa di wilayah 5 Dusun untuk mendaftarkan usahanya agar ditampilkan di E-Katalog resmi desa.') ?>
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="page.php?slug=panduan-layanan" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold text-xs sm:text-sm py-3.5 px-8 rounded-2xl shadow-lg transition-transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check"></i>
                <span><?= tr('Syarat & Cara Pendaftaran') ?></span>
            </a>
            <a href="index.php" class="bg-slate-800 hover:bg-slate-700 text-white text-xs sm:text-sm font-bold py-3.5 px-6 rounded-2xl border border-slate-700 transition-colors">
                <?= tr('Kembali ke Beranda') ?>
            </a>
        </div>
    </div>
</section>

<!-- SCRIPT FILTER UMKM -->
<script>
function filterUmkm(category, btnElement) {
    // Ubah status tombol aktif
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.className = "filter-btn px-4 py-2 rounded-xl bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold border border-slate-300 transition-all";
    });
    btnElement.className = "filter-btn px-4 py-2 rounded-xl bg-emerald-800 text-white text-xs font-bold shadow transition-all";

    // Filter elemen card
    const cards = document.querySelectorAll('.umkm-card');
    cards.forEach(card => {
        if (category === 'all' || card.classList.contains(category)) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
}
</script>

<?php include 'config/footer.php'; ?>
