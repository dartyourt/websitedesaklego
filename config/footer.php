    </main>
    <!-- MAIN CONTENT CONTAINER END -->

    <!-- FOOTER PREMIUM THEME -->
    <footer class="bg-[#0e3f23] text-slate-300 pt-16 pb-12 border-t-4 border-[#c4891f] shadow-2xl relative overflow-hidden">
        
        <!-- DECORATIVE BACKGROUND PATTERN -->
        <div class="absolute -right-24 -bottom-24 w-96 h-96 bg-emerald-800/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/4 -top-12 w-64 h-64 bg-amber-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-emerald-800/80">
                
                <!-- KOLOM 1: IDENTITAS & LOGO -->
                <div class="space-y-4 lg:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white rounded-full p-1 border-2 border-amber-400 flex items-center justify-center shadow-lg flex-shrink-0">
                            <img src="logoboyolali.png" alt="Logo Kabupaten Boyolali" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-white leading-snug">
                                Pemerintah <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>
                            </h3>
                            <p class="text-xs text-amber-400 font-semibold uppercase tracking-wider">
                                Kabupaten <?= htmlspecialchars($APP_PROFIL['kabupaten'] ?? 'Boyolali') ?>
                            </p>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-200/90 leading-relaxed pt-2">
                        Portal informasi resmi, keterbukaan pembendaharaan keuangan negara, dan pusat dokumentasi legislasi hukum Desa Klego sebagai wujud tata kelola pemerintahan yang akuntabel.
                    </p>
                </div>

                <!-- KOLOM 2: KONTAK & LOKASI -->
                <div class="space-y-4">
                    <h4 class="font-heading font-bold text-white text-sm uppercase tracking-wider border-l-4 border-amber-500 pl-3">
                        Alamat & Kontak
                    </h4>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-map-location-dot text-amber-400 mt-0.5 text-base"></i>
                            <span><?= htmlspecialchars($APP_PROFIL['alamat'] ?? 'Jl. Raya Klego-Andong, Balai Desa Klego, Kec. Klego, Kab. Boyolali, Jawa Tengah 57385') ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-amber-400 text-sm"></i>
                            <span><?= htmlspecialchars($APP_PROFIL['telepon'] ?? '(0276) 321-456') ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-amber-400 text-sm"></i>
                            <span><?= htmlspecialchars($APP_PROFIL['email'] ?? 'desaklego@boyolali.go.id') ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-clock text-amber-400 text-sm"></i>
                            <span>Senin - Jumat: 08.00 - 16.00 WIB</span>
                        </li>
                    </ul>
                </div>

                <!-- KOLOM 3: LAYANAN & PUSTAKA DOKUMEN -->
                <div class="space-y-4">
                    <h4 class="font-heading font-bold text-white text-sm uppercase tracking-wider border-l-4 border-amber-500 pl-3">
                        Pustaka & Regulasi
                    </h4>
                    <ul class="space-y-2.5 text-xs font-medium">
                        <li>
                            <a href="dokumen.php?kategori=Peraturan+%26+Produk+Legislasi+Desa" class="hover:text-amber-400 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-[10px] text-amber-500"></i> Peraturan & Produk Legislasi (JDIH)
                            </a>
                        </li>
                        <li>
                            <a href="dokumen.php?kategori=Inventarisasi+Aset+%26+Informasi" class="hover:text-amber-400 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-[10px] text-amber-500"></i> Data Aset & SILPA 2025
                            </a>
                        </li>
                        <li>
                            <a href="dokumen.php?kategori=Rencana+Pembangunan+Jangka+Menengah+%28RPJM%29" class="hover:text-amber-400 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-[10px] text-amber-500"></i> Dokumen RPJM Desa (6 Tahun)
                            </a>
                        </li>
                        <li>
                            <a href="infografis.php" class="hover:text-amber-400 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-[10px] text-amber-500"></i> Infografis Pembendaharaan Negara
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- KOLOM 4: MEDIA SOSIAL & TRANSAKSI PORTAL -->
                <div class="space-y-4">
                    <h4 class="font-heading font-bold text-white text-sm uppercase tracking-wider border-l-4 border-amber-500 pl-3">
                        Saluran Resmi
                    </h4>
                    <p class="text-xs text-emerald-200">
                        Ikuti media sosial resmi kami untuk mendapatkan pengumuman terkini, kegiatan warga, dan program pembantalan sosial.
                    </p>
                    <div class="flex items-center gap-3 pt-1">
                        <a href="#" class="w-9 h-9 rounded-lg bg-emerald-800/80 hover:bg-amber-600 text-white flex items-center justify-center transition-all duration-200 shadow" title="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-emerald-800/80 hover:bg-amber-600 text-white flex items-center justify-center transition-all duration-200 shadow" title="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-emerald-800/80 hover:bg-amber-600 text-white flex items-center justify-center transition-all duration-200 shadow" title="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-emerald-800/80 hover:bg-amber-600 text-white flex items-center justify-center transition-all duration-200 shadow" title="WhatsApp Center">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </a>
                    </div>
                    <div class="pt-2">
                        <a href="login.php" class="inline-flex items-center gap-2 text-xs font-bold text-amber-300 bg-black/20 hover:bg-black/40 border border-amber-500/40 px-4 py-2 rounded-lg transition-colors">
                            <i class="fa-solid fa-lock text-amber-400"></i> Masuk Sistem Admin (CMS)
                        </a>
                    </div>
                </div>

            </div>

            <!-- BOTTOM COPYRIGHT -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-emerald-300/70 gap-4">
                <p>
                    &copy; <?= date('Y') ?> Pemerintah <?= htmlspecialchars($APP_PROFIL['nama_desa'] ?? 'Desa Klego') ?>. Hak Cipta Dilindungi Undang-Undang.
                </p>
                <div class="flex items-center gap-6">
                    <span class="hover:underline cursor-pointer">Kebijakan Privasi</span>
                    <span>&bull;</span>
                    <span class="hover:underline cursor-pointer">Syarat Penggunaan</span>
                    <span>&bull;</span>
                    <span class="text-amber-400 font-semibold">Modular CMS Architecture</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
