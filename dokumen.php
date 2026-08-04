<?php
$pageTitle = "Pustaka Dokumen & Regulasi";
include 'config/header.php';

$selectedKategori = $_GET['kategori'] ?? '';
$searchQuery = $_GET['q'] ?? '';

// Daftar dokumen fallback (sumber asli Folder Sumber Data Satria) bila DB offline/belum di-setup
$defaultDocs = [
    // 1. Peraturan & Produk Legislasi Desa
    [
        'judul' => 'Perdes APBDesa Klego Tahun Anggaran 2026',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/KLEGO PERDES APBDesa TAHUN ANGGARAN 2026.pdf',
        'file_type' => 'pdf',
        'file_size' => '21.2 MB',
        'tanggal' => '2026-01-15',
        'keterangan' => 'Peraturan Desa Klego tentang Anggaran Pendapatan dan Belanja Desa (APBDes) Tahun Anggaran 2026.'
    ],
    [
        'judul' => 'Perkades Penjabaran APBDesa Klego Tahun Anggaran 2026',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/KLEGO PERKADES APBDesa TAHUN ANGGARAN 2026.pdf',
        'file_type' => 'pdf',
        'file_size' => '36.9 MB',
        'tanggal' => '2026-01-15',
        'keterangan' => 'Peraturan Kepala Desa Klego tentang Penjabaran APBDesa TA 2026 secara terperinci.'
    ],
    [
        'judul' => 'Perdes Pertanggungjawaban APB Desa Klego 2025',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/Perdes Pertanggungjawaban APB Desa Klego 2025.docx.pdf',
        'file_type' => 'pdf',
        'file_size' => '650 KB',
        'tanggal' => '2025-12-31',
        'keterangan' => 'Peraturan Desa menyatu mengenai laporan akhir pertanggungjawaban APBDes TA 2025.'
    ],
    [
        'judul' => 'Perdes Pengesahan RPJM Desa Klego',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/00. Perdes RPJMDES.docx.pdf',
        'file_type' => 'pdf',
        'file_size' => '190 KB',
        'tanggal' => '2025-12-01',
        'keterangan' => 'Perdes pengesahan dokumen Rencana Pembangunan Jangka Menengah Desa Klego.'
    ],
    [
        'judul' => 'Perbup Nomor 61 Tahun 2018 Tentang Kewenangan Desa',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/PERBUP NOMOR 61 TAHUN 2018 TTG KEWENANGAN DESA.doc.pdf',
        'file_type' => 'pdf',
        'file_size' => '168 KB',
        'tanggal' => '2018-12-10',
        'keterangan' => 'Peraturan Bupati Boyolali terkait pedoman kewenangan berdasarkan asal-usul dan skala desa.'
    ],
    [
        'judul' => 'Permendagri Nomor 44 Tahun 2016 Tentang Kewenangan Desa',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/PERMENDAGRI 44 TAHUN 2016 ttg Kewenangan Desa.doc.pdf',
        'file_type' => 'pdf',
        'file_size' => '194 KB',
        'tanggal' => '2016-08-15',
        'keterangan' => 'Regulasi nasional Kementerian Dalam Negeri mengenai tata kelola kewenangan desa.'
    ],
    [
        'judul' => 'Undang-Undang Nomor 6 Tahun 2014 Tentang Desa',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/UU Nomor 06 Tahun 2014.pdf',
        'file_type' => 'pdf',
        'file_size' => '551 KB',
        'tanggal' => '2014-01-15',
        'keterangan' => 'Undang-Undang pokok yang mengatur desa dan alokasi dana desa.'
    ],
    [
        'judul' => 'Undang-Undang Nomor 3 Tahun 2024 Tentang Perubahan atas UU Desa',
        'kategori' => 'Peraturan & Produk Legislasi Desa',
        'file_path' => 'data/Sumber Data Satria/Peraturan & Produk Legislasi Desa/UU Nomor 3 Tahun 2024.pdf',
        'file_type' => 'pdf',
        'file_size' => '2.4 MB',
        'tanggal' => '2024-04-25',
        'keterangan' => 'Perubahan terbaru atas Undang-Undang Desa mengenai masa jabatan dan tata kelola.'
    ],

    // 2. Inventarisasi Aset & Informasi
    [
        'judul' => 'Laporan SILPA 2025 Desa Klego',
        'kategori' => 'Inventarisasi Aset & Informasi',
        'file_path' => 'data/Sumber Data Satria/Inventarisasi Aset & Informasi/01. SILPA 2025 Desa Klego.xlsx',
        'file_type' => 'xlsx',
        'file_size' => '38.2 KB',
        'tanggal' => '2025-12-31',
        'keterangan' => 'Perhitungan Sisa Lebih Pembiayaan Anggaran (SILPA) akhir Tahun Anggaran 2025.'
    ],
    [
        'judul' => 'Buku Bantu Aset Desa Klego 2025',
        'kategori' => 'Inventarisasi Aset & Informasi',
        'file_path' => 'data/Sumber Data Satria/Inventarisasi Aset & Informasi/BUKU BANTU ASET KLEGO 2025.xlsx',
        'file_type' => 'xlsx',
        'file_size' => '138 KB',
        'tanggal' => '2025-12-31',
        'keterangan' => 'Buku bantu inventarisasi dan pencatatan nilai seluruh aset milik Pemerintah Desa Klego.'
    ],
    [
        'judul' => 'Berita Acara Stock Opnam Persediaan Desa Klego 2025',
        'kategori' => 'Inventarisasi Aset & Informasi',
        'file_path' => 'data/Sumber Data Satria/Inventarisasi Aset & Informasi/BA STOCK OPNAM PERSEDIAAN DESA KLEGO 2025.docx',
        'file_type' => 'docx',
        'file_size' => '43.7 KB',
        'tanggal' => '2025-12-30',
        'keterangan' => 'Berita acara pemeriksaan fisik persediaan barang akhir tahun 2025.'
    ],
    [
        'judul' => 'Buku Bantu Persediaan Akhir 2025',
        'kategori' => 'Inventarisasi Aset & Informasi',
        'file_path' => 'data/Sumber Data Satria/Inventarisasi Aset & Informasi/BUKU BANTU PERSEDIAAN AKHIR 2025.xlsx',
        'file_type' => 'xlsx',
        'file_size' => '84.5 KB',
        'tanggal' => '2025-12-31',
        'keterangan' => 'Rekapitulasi persediaan barang alat tulis kantor dan perlengkapan desa 2025.'
    ],
    [
        'judul' => 'Catatan Atas Laporan Keuangan (CaLK) Klego 2025',
        'kategori' => 'Inventarisasi Aset & Informasi',
        'file_path' => 'data/Sumber Data Satria/Inventarisasi Aset & Informasi/CaLK KLEGO 2025.docx',
        'file_type' => 'docx',
        'file_size' => '187 KB',
        'tanggal' => '2025-12-31',
        'keterangan' => 'Catatan lengkap atas laporan keuangan Pemerintah Desa Klego TA 2025.'
    ],
    [
        'judul' => 'Lampiran Inventaris Aset Jalan Desa Klego',
        'kategori' => 'Inventarisasi Aset & Informasi',
        'file_path' => 'data/Sumber Data Satria/Inventarisasi Aset & Informasi/Desa Klego Lampiran Inventaris Aset Jalan Desa.xlsx',
        'file_type' => 'xlsx',
        'file_size' => '21.1 KB',
        'tanggal' => '2025-12-31',
        'keterangan' => 'Rincian panjang, kondisi, dan spesifikasi seluruh ruas jalan desa di 5 dusun.'
    ],

    // 3. Rencana Pembangunan Jangka Menengah (RPJM)
    [
        'judul' => 'Naskah RPJM Desa Klego Perubahan (6 Tahun)',
        'kategori' => 'Rencana Pembangunan Jangka Menengah (RPJM)',
        'file_path' => 'data/Sumber Data Satria/Rencana Pembangunan Jangka Menengah (RPJM)/00. Naskah RPJM Desa 2 Tahun.docx.pdf',
        'file_type' => 'pdf',
        'file_size' => '656 KB',
        'tanggal' => '2025-12-01',
        'keterangan' => 'Naskah resmi dokumen perencanaan pembangunan desa sebagai pedoman arah program kerja pemdes.'
    ],

    // 4. Lain Lainnya
    [
        'judul' => 'Dokumen Visi dan Misi Kepala Desa Klego',
        'kategori' => 'Lain Lainnya',
        'file_path' => 'data/Sumber Data Satria/Lain Lainnya/6. Visi Misi Kepala Desa.doc.pdf',
        'file_type' => 'pdf',
        'file_size' => '170 KB',
        'tanggal' => '2025-01-01',
        'keterangan' => 'Penjabaran visi dan misi resmi Kepala Desa Klego dalam kepemimpinan periode berjalan.'
    ]
];

// Ambil data dari database apabila tersedia
$documentList = [];
if (isset($conn) && $conn && !mysqli_connect_error()) {
    $sql = "SELECT * FROM dokumen_publik WHERE 1=1";
    if (!empty($selectedKategori)) {
        $escKat = mysqli_real_escape_string($conn, $selectedKategori);
        $sql .= " AND kategori = '$escKat'";
    }
    if (!empty($searchQuery)) {
        $escQ = mysqli_real_escape_string($conn, $searchQuery);
        $sql .= " AND (judul LIKE '%$escQ%' OR keterangan LIKE '%$escQ%')";
    }
    $sql .= " ORDER BY tanggal DESC, id ASC";
    
    $res = @mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            // format size
            $row['file_size_formatted'] = $row['file_size'] > 0 ? round($row['file_size']/1024, 1) . ' KB' : '-';
            if ($row['file_size'] > 1048576) {
                $row['file_size_formatted'] = round($row['file_size']/1048576, 1) . ' MB';
            }
            $documentList[] = $row;
        }
    }
}

// Jika hasil DB kosong (karena offline/filter di fallback), filter manual dari defaultDocs
if (empty($documentList)) {
    foreach ($defaultDocs as $doc) {
        $matchKat = empty($selectedKategori) || ($doc['kategori'] == $selectedKategori);
        $matchQ = empty($searchQuery) || (stripos($doc['judul'], $searchQuery) !== false || stripos($doc['keterangan'], $searchQuery) !== false);
        if ($matchKat && $matchQ) {
            $doc['file_size_formatted'] = $doc['file_size'];
            $documentList[] = $doc;
        }
    }
}

// Daftar kategori untuk Tab Filter
$kategoriList = [
    '' => t('kat_semua', 'Semua Berkas (JDIH & Aset)'),
    'Peraturan & Produk Legislasi Desa' => t('kat_legislasi', 'Peraturan & Produk Legislasi (11)'),
    'Inventarisasi Aset & Informasi' => t('kat_aset', 'Data Aset & SILPA 2025 (9)'),
    'Rencana Pembangunan Jangka Menengah (RPJM)' => t('kat_rpjm', 'RPJM Desa Perubahan (1)'),
    'Lain Lainnya' => 'Visi Misi & Lainnya (1)'
];
?>

<!-- ================= HEADER SECTION ================= -->
<section class="bg-gradient-to-r from-emerald-900 via-[#165f36] to-emerald-950 text-white py-16 border-b-4 border-amber-500 shadow-md relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 text-center max-w-4xl">
        <span class="inline-block text-xs font-mono font-bold uppercase tracking-widest text-amber-300 bg-black/20 px-4 py-1.5 rounded-full mb-4 border border-amber-400/40">
            <?= t('pustaka_hukum', 'JDIH & Keterbukaan Informasi Publik') ?>
        </span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight">
            <?= t('dok_judul', 'Pustaka Dokumen, Regulasi & Data Aset') ?>
        </h1>
        <p class="text-emerald-100/90 text-sm sm:text-base mt-4 max-w-2xl mx-auto leading-relaxed">
            <?= t('dok_sub', 'Akses bebas untuk seluruh masyarakat terhadap Peraturan Desa (Perdes APBDes 2026), Buku Bantu Aset, Laporan SILPA, serta dokumen RPJM Desa Klego.') ?>
        </p>
        
        <!-- SEARCH BAR FORM -->
        <form method="GET" action="dokumen.php" class="mt-8 max-w-xl mx-auto flex gap-2">
            <?php if (!empty($selectedKategori)): ?>
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($selectedKategori) ?>">
            <?php endif; ?>
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($searchQuery) ?>" 
                       placeholder="Cari nama peraturan, nomor, tahun, atau aset..." 
                       id="searchInput"
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-white text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:ring-4 focus:ring-amber-400 shadow-lg">
            </div>
            <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold px-6 py-3 rounded-xl shadow-lg transition-all text-sm flex items-center gap-2 flex-shrink-0">
                <span>Cari</span>
            </button>
            <?php if (!empty($searchQuery) || !empty($selectedKategori)): ?>
                <a href="dokumen.php" class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-xl font-medium text-sm flex items-center transition-all" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- ================= TAB FILTER KATEGORI ================= -->
<section class="bg-white border-b border-slate-200 shadow-sm sticky top-[68px] z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center space-x-2 overflow-x-auto py-3 no-scrollbar">
            <?php foreach ($kategoriList as $val => $label): ?>
                <?php 
                    $isActive = ($selectedKategori == $val);
                    $url = "dokumen.php" . (!empty($val) ? "?kategori=" . urlencode($val) : "");
                    if (!empty($searchQuery)) {
                        $url .= (!empty($val) ? "&" : "?") . "q=" . urlencode($searchQuery);
                    }
                ?>
                <a href="<?= $url ?>" 
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 flex-shrink-0 <?= $isActive ? 'bg-emerald-800 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= DAFTAR DOKUMEN (TABLE & CARDS) ================= -->
<section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 min-h-[500px]">
    <div class="flex justify-between items-center mb-6 text-xs text-slate-500 font-medium">
        <span>Menampilkan <strong class="text-slate-900"><?= count($documentList) ?></strong> dokumen resmi yang terdaftar</span>
        <span class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Diperbarui Berkala
        </span>
    </div>

    <?php if (empty($documentList)): ?>
        <!-- EMPTY STATE -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-xl mx-auto my-12">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="font-heading font-bold text-lg text-slate-800">Dokumen Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1 mb-6">
                Tidak ada dokumen yang cocok dengan kata kunci atau filter kategori Anda.
            </p>
            <a href="dokumen.php" class="bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl inline-block hover:bg-emerald-800 transition-all">
                Tampilkan Semua Dokumen
            </a>
        </div>
    <?php else: ?>
        
        <!-- DOCUMENTS GRID -->
        <div class="space-y-4" id="docListContainer">
            <?php foreach ($documentList as $doc): ?>
                <?php
                // Tentukan Ikon berdasarkan Tipe Berkas
                $type = strtolower($doc['file_type'] ?? 'pdf');
                $iconClass = "fa-solid fa-file-pdf text-rose-600 bg-rose-50 border-rose-200";
                $typeBadge = "PDF";
                if ($type === 'xlsx' || $type === 'xls') {
                    $iconClass = "fa-solid fa-file-excel text-emerald-600 bg-emerald-50 border-emerald-200";
                    $typeBadge = "EXCEL";
                } elseif ($type === 'docx' || $type === 'doc') {
                    $iconClass = "fa-solid fa-file-word text-blue-600 bg-blue-50 border-blue-200";
                    $typeBadge = "WORD";
                }
                
                // Pastikan file path beralasan
                $fileUrl = htmlspecialchars($doc['file_path']);
                ?>
                
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 doc-row group">
                    
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-14 h-14 rounded-2xl border flex items-center justify-center text-2xl flex-shrink-0 <?= $iconClass ?> shadow-inner group-hover:scale-105 transition-transform">
                            <i class="<?= explode(' ', $iconClass)[1] ?>"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2 text-[11px] font-medium text-slate-500">
                                <span class="text-emerald-800 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100">
                                    <?= htmlspecialchars($doc['kategori']) ?>
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-calendar-days text-amber-600"></i>
                                    <?= isset($doc['tanggal']) ? date('d M Y', strtotime($doc['tanggal'])) : '2026-01-15' ?>
                                </span>
                                <span>&bull;</span>
                                <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                    <?= $doc['file_size_formatted'] ?> (<?= $typeBadge ?>)
                                </span>
                            </div>

                            <h3 class="font-heading font-bold text-lg text-slate-900 group-hover:text-emerald-800 transition-colors pt-1 doc-title">
                                <?= htmlspecialchars($doc['judul']) ?>
                            </h3>

                            <?php if (!empty($doc['keterangan'])): ?>
                                <p class="text-xs text-slate-600 leading-relaxed max-w-3xl pt-1">
                                    <?= htmlspecialchars($doc['keterangan']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-100">
                        <?php if ($type === 'pdf'): ?>
                            <button type="button" 
                                    onclick="openPdfModal('<?= $fileUrl ?>', '<?= addslashes($doc['judul']) ?>')"
                                    class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center gap-1.5 transition-colors focus:outline-none">
                                <i class="fa-solid fa-eye text-emerald-700"></i>
                                <span>Pratinjau</span>
                            </button>
                        <?php endif; ?>
                        
                        <a href="<?= $fileUrl ?>" 
                           download 
                           target="_blank"
                           class="bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-900 hover:to-emerald-800 text-white font-bold px-5 py-2.5 rounded-xl text-xs shadow hover:shadow-lg transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-download text-amber-300"></i>
                            <span>Unduh Resmi</span>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- ================= MODAL PRATINJAU PDF ONLINE ================= -->
<div id="pdfModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden border border-slate-700">
        <!-- MODAL HEADER -->
        <div class="bg-emerald-900 text-white px-6 py-4 flex items-center justify-between border-b border-emerald-800">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-pdf text-amber-400 text-2xl"></i>
                <div>
                    <h3 class="font-heading font-bold text-base text-white" id="modalDocTitle">Pratinjau Dokumen</h3>
                    <p class="text-[11px] text-emerald-200">Pustaka Digital Desa Klego</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a id="modalDownloadBtn" href="#" download target="_blank" class="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold px-4 py-2 rounded-lg transition-colors flex items-center gap-1.5 shadow">
                    <i class="fa-solid fa-download"></i> Unduh Berkas
                </a>
                <button type="button" onclick="closePdfModal()" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- MODAL IFRAME BODY -->
        <div class="flex-grow bg-slate-200 relative">
            <div class="absolute inset-0 flex items-center justify-center text-slate-500 z-0">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-spinner fa-spin text-emerald-700 text-xl"></i> Memuat dokumen PDF...
                </span>
            </div>
            <iframe id="pdfIframe" src="" class="w-full h-full border-0 relative z-10"></iframe>
        </div>
    </div>
</div>

<!-- JS FOR PDF MODAL & LIVE SEARCH -->
<script>
function openPdfModal(fileUrl, title) {
    const modal = document.getElementById('pdfModal');
    const iframe = document.getElementById('pdfIframe');
    const titleEl = document.getElementById('modalDocTitle');
    const dlBtn = document.getElementById('modalDownloadBtn');
    
    titleEl.textContent = title;
    iframe.src = fileUrl + '#toolbar=0';
    dlBtn.href = fileUrl;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closePdfModal() {
    const modal = document.getElementById('pdfModal');
    const iframe = document.getElementById('pdfIframe');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        iframe.src = '';
    }, 300);
    document.body.style.overflow = 'auto';
}

// Live text search filter di browser
const searchInput = document.getElementById('searchInput');
searchInput?.addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('.doc-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php include 'config/footer.php'; ?>
