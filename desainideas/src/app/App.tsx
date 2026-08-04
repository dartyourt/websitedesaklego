import { useState, useEffect, useRef } from "react";
import {
  BarChart, Bar, PieChart, Pie, Cell, AreaChart, Area,
  XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend,
} from "recharts";
import {
  Menu, X, ChevronDown, ArrowUp, Search, MapPin, Phone, Mail, Clock,
  Facebook, Instagram, Youtube, Download, Eye, Edit, Trash2, Plus,
  Bell, Settings, Users, FileText, Image, BarChart2, LogOut,
  ChevronRight, Leaf, Building, Star, Award, Shield, Wheat,
  BookOpen, Newspaper, Calendar, Megaphone, Home, User, Lock,
  Grid, Package, Globe, Check, TrendingUp, Heart, Map,
} from "lucide-react";

// ─── DATA ────────────────────────────────────────────────────────────────────

const demo = {
  penduduk: 4823,
  kk: 1456,
  lakiLaki: 2411,
  perempuan: 2412,
  luas: "487 Ha",
  rw: 6,
  rt: 18,
  dusun: 5,
};

const ageData = [
  { name: "0–14", value: 965, fill: "#2e9e5b" },
  { name: "15–29", value: 1205, fill: "#165f36" },
  { name: "30–44", value: 1014, fill: "#52b87a" },
  { name: "45–59", value: 879, fill: "#c4891f" },
  { name: "60+", value: 760, fill: "#8ecba5" },
];

const dusunData = [
  { name: "Klego", penduduk: 1243 },
  { name: "Ponggok", penduduk: 987 },
  { name: "Soka", penduduk: 876 },
  { name: "Rejosari", penduduk: 765 },
  { name: "Ngemplak", penduduk: 952 },
];

const genderPie = [
  { name: "Laki-laki", value: 2411, fill: "#165f36" },
  { name: "Perempuan", value: 2412, fill: "#c4891f" },
];

const populationTrend = [
  { year: "2019", penduduk: 4521 },
  { year: "2020", penduduk: 4598 },
  { year: "2021", penduduk: 4642 },
  { year: "2022", penduduk: 4711 },
  { year: "2023", penduduk: 4776 },
  { year: "2024", penduduk: 4823 },
];

const beritaList = [
  {
    id: 1,
    kategori: "Berita",
    judul: "Musyawarah Desa Klego Tetapkan APBDes 2025",
    tanggal: "12 Juli 2025",
    ringkasan: "Musyawarah desa telah menetapkan Anggaran Pendapatan dan Belanja Desa (APBDes) tahun 2025 dengan total anggaran sebesar Rp 1,2 miliar.",
    img: "photo-1600880292203-757bb62b4baf",
  },
  {
    id: 2,
    kategori: "Berita",
    judul: "Pelatihan UMKM Kerajinan Tangan Sukses Digelar",
    tanggal: "5 Juli 2025",
    ringkasan: "Sebanyak 45 warga Desa Klego mengikuti pelatihan kerajinan tangan berbahan bambu yang difasilitasi oleh Dinas Koperasi Kabupaten Boyolali.",
    img: "photo-1578662996442-48f60103fc96",
  },
  {
    id: 3,
    kategori: "Berita",
    judul: "Pembangunan Jalan Dusun Soka Resmi Dimulai",
    tanggal: "28 Juni 2025",
    ringkasan: "Proyek pembangunan dan pengerasan jalan sepanjang 1,2 km di Dusun Soka resmi dimulai dengan anggaran dari Dana Desa 2025.",
    img: "photo-1513828583688-c52646db42da",
  },
];

const agendaList = [
  { tanggal: "18 Jul", bulan: "2025", judul: "Posyandu Balita & Lansia", lokasi: "Balai Desa", jam: "08.00–11.00 WIB" },
  { tanggal: "22 Jul", bulan: "2025", judul: "Rapat Koordinasi RT/RW", lokasi: "Balai Desa", jam: "19.00 WIB" },
  { tanggal: "25 Jul", bulan: "2025", judul: "Gotong Royong Kebersihan", lokasi: "Seluruh Dusun", jam: "07.00–10.00 WIB" },
  { tanggal: "1 Agt", bulan: "2025", judul: "Peringatan HUT RI ke-80", lokasi: "Lapangan Desa", jam: "08.00 WIB" },
];

const pengumumanList = [
  { judul: "Pembayaran PBB 2025 Mulai 1 Agustus", tanggal: "10 Jul 2025", penting: true },
  { judul: "Jadwal Pelayanan KTP Elektronik", tanggal: "8 Jul 2025", penting: false },
  { judul: "Pembagian BLT Dana Desa Tahap 3", tanggal: "5 Jul 2025", penting: true },
  { judul: "Rekrutmen Kader PKK 2025", tanggal: "1 Jul 2025", penting: false },
];

const layananList = [
  { icon: FileText, judul: "Surat Keterangan Domisili", waktu: "1 Hari", syarat: "KTP + KK" },
  { icon: User, judul: "Surat Pengantar KTP/KK", waktu: "1 Hari", syarat: "Formulir F1.01 + KK lama" },
  { icon: Award, judul: "Surat Keterangan Tidak Mampu", waktu: "1 Hari", syarat: "KTP + KK + Surat RT" },
  { icon: BookOpen, judul: "Surat Keterangan Usaha", waktu: "2 Hari", syarat: "KTP + KK + Foto Usaha" },
  { icon: Shield, judul: "Surat Keterangan Kelakuan Baik", waktu: "1 Hari", syarat: "KTP + KK + Foto 3x4" },
  { icon: Heart, judul: "Surat Keterangan Nikah", waktu: "2 Hari", syarat: "KTP + KK + Akta Lahir" },
];

const potensiList = [
  {
    icon: Wheat,
    judul: "Pertanian",
    deskripsi: "Lahan pertanian produktif seluas 312 Ha dengan komoditas utama padi, jagung, dan kedelai. Hasil panen rata-rata 6 ton/Ha per musim.",
    color: "from-green-600 to-green-700",
    stat: "312 Ha",
    satuan: "Lahan Produktif",
  },
  {
    icon: Package,
    judul: "UMKM",
    deskripsi: "Terdapat 87 pelaku UMKM aktif bergerak di bidang kerajinan bambu, batik tulis, olahan pangan lokal, dan jasa.",
    color: "from-amber-600 to-amber-700",
    stat: "87",
    satuan: "Pelaku UMKM",
  },
  {
    icon: Globe,
    judul: "Kelembagaan",
    deskripsi: "Didukung kelembagaan aktif: BPD, LKMD, PKK, Karang Taruna, Gapoktan, dan Kelompok Tani yang solid.",
    color: "from-teal-600 to-teal-700",
    stat: "12",
    satuan: "Lembaga Aktif",
  },
  {
    icon: Building,
    judul: "Pembangunan",
    deskripsi: "Infrastruktur terus dikembangkan meliputi jalan, drainase, fasilitas kesehatan, dan pusat kegiatan masyarakat.",
    color: "from-blue-600 to-blue-700",
    stat: "Rp 1,2 M",
    satuan: "Dana Desa 2025",
  },
];

const galeriList = [
  { img: "photo-1586348943529-beaae6c28db9", alt: "Sawah Desa Klego", caption: "Hamparan sawah hijau" },
  { img: "photo-1529156069898-49953e39b3ac", alt: "Kegiatan Gotong Royong", caption: "Gotong royong warga" },
  { img: "photo-1574943320219-553eb213f72d", alt: "Panen Padi", caption: "Musim panen padi" },
  { img: "photo-1578662996442-48f60103fc96", alt: "UMKM Kerajinan", caption: "Kerajinan bambu UMKM" },
  { img: "photo-1600880292203-757bb62b4baf", alt: "Rapat Desa", caption: "Musyawarah Desa" },
  { img: "photo-1606836591695-4d58a73eba1e", alt: "PKK Desa", caption: "Kegiatan PKK" },
];

const peraturanList = [
  { no: "01/2025", judul: "Perdes Tentang APBDes TA 2025", tipe: "Peraturan Desa", tgl: "15 Jan 2025" },
  { no: "02/2025", judul: "Perdes Tentang Pengelolaan Aset Desa", tipe: "Peraturan Desa", tgl: "20 Feb 2025" },
  { no: "03/2025", judul: "Peraturan Kepala Desa tentang Pelayanan Administrasi", tipe: "Perkades", tgl: "10 Mar 2025" },
  { no: "001/2025", judul: "SK Pengangkatan Perangkat Desa 2025", tipe: "Keputusan Kades", tgl: "5 Jan 2025" },
  { no: "APBDes/2025", judul: "Dokumen APBDes Desa Klego 2025", tipe: "APBDes", tgl: "15 Jan 2025" },
];

const adminMenuItems = [
  { icon: BarChart2, label: "Dashboard", id: "dashboard" },
  { icon: Home, label: "Kelola Beranda", id: "beranda" },
  { icon: User, label: "Profil Desa", id: "profil-admin" },
  { icon: Users, label: "Data Kependudukan", id: "kependudukan" },
  { icon: Newspaper, label: "Berita & Agenda", id: "berita-admin" },
  { icon: FileText, label: "Produk Peraturan", id: "peraturan-admin" },
  { icon: Package, label: "Layanan & Potensi", id: "layanan-admin" },
  { icon: Image, label: "Galeri Media", id: "galeri-admin" },
  { icon: Settings, label: "Pengaturan", id: "pengaturan" },
  { icon: Users, label: "Manajemen Pengguna", id: "pengguna" },
];

// ─── HELPERS ─────────────────────────────────────────────────────────────────

const unsplash = (id: string, w = 800, h = 500) =>
  `https://images.unsplash.com/${id}?w=${w}&h=${h}&fit=crop&auto=format`;

// ─── COMPONENTS ──────────────────────────────────────────────────────────────

function SectionHeading({ label, title, subtitle }: { label: string; title: string; subtitle?: string }) {
  return (
    <div className="text-center mb-12">
      <span className="inline-block text-xs font-mono uppercase tracking-widest text-accent-foreground bg-accent/10 text-[#c4891f] px-3 py-1 rounded-full mb-3">
        {label}
      </span>
      <h2 className="text-3xl md:text-4xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>
        {title}
      </h2>
      {subtitle && <p className="mt-3 text-muted-foreground max-w-xl mx-auto text-sm leading-relaxed">{subtitle}</p>}
    </div>
  );
}

function StatCard({ icon: Icon, value, label, color }: { icon: any; value: string | number; label: string; color: string }) {
  return (
    <div className="bg-white rounded-2xl p-5 shadow-sm border border-border flex items-center gap-4 hover:shadow-md transition-shadow">
      <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${color}`}>
        <Icon size={22} className="text-white" />
      </div>
      <div>
        <p className="text-xl font-bold text-foreground" style={{ fontFamily: "var(--font-sans)" }}>{value.toLocaleString("id-ID")}</p>
        <p className="text-xs text-muted-foreground">{label}</p>
      </div>
    </div>
  );
}

// ─── NAVBAR ──────────────────────────────────────────────────────────────────

const navLinks = [
  {
    label: "Home",
    id: "home",
    sub: ["Tentang Desa", "SOTK", "Peta Desa"],
  },
  {
    label: "Profil Desa",
    id: "profil",
    sub: ["Sejarah Desa", "Visi & Misi", "Struktur Pemdes", "Struktur BPD"],
  },
  { label: "Infografis", id: "infografis", sub: [] },
  {
    label: "Produk Peraturan",
    id: "peraturan",
    sub: ["Perdes", "Perkades", "APBDes", "SK Kades", "Informasi Publik"],
  },
  {
    label: "Pelayanan & Potensi",
    id: "pelayanan",
    sub: ["Layanan Administrasi", "Fasilitas Desa", "Potensi Desa", "UMKM", "Galeri"],
  },
];

function Navbar({ activeSection, setActiveSection, onAdmin }: any) {
  const [mobileOpen, setMobileOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [openDrop, setOpenDrop] = useState<string | null>(null);

  useEffect(() => {
    const handler = () => setScrolled(window.scrollY > 60);
    window.addEventListener("scroll", handler);
    return () => window.removeEventListener("scroll", handler);
  }, []);

  return (
    <header
      className={`fixed top-0 inset-x-0 z-50 transition-all duration-300 ${
        scrolled
          ? "bg-white/95 backdrop-blur-md shadow-sm border-b border-border"
          : "bg-transparent"
      }`}
    >
      {/* Top bar */}
      <div className="hidden md:block bg-primary text-primary-foreground text-xs">
        <div className="max-w-7xl mx-auto px-6 py-1.5 flex items-center justify-between">
          <div className="flex items-center gap-6">
            <span className="flex items-center gap-1.5"><Clock size={11} /> Senin–Jumat 08.00–16.00 WIB</span>
            <span className="flex items-center gap-1.5"><Phone size={11} /> (0276) 321-456</span>
            <span className="flex items-center gap-1.5"><Mail size={11} /> desklego@gmail.com</span>
          </div>
          <div className="flex items-center gap-3">
            <a href="#" className="hover:text-yellow-300 transition-colors"><Facebook size={12} /></a>
            <a href="#" className="hover:text-yellow-300 transition-colors"><Instagram size={12} /></a>
            <a href="#" className="hover:text-yellow-300 transition-colors"><Youtube size={12} /></a>
            <button onClick={onAdmin} className="ml-2 text-xs bg-accent text-white px-3 py-0.5 rounded-full hover:bg-amber-500 transition-colors">
              Admin
            </button>
          </div>
        </div>
      </div>

      {/* Main nav */}
      <div className="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        {/* Logo */}
        <button
          onClick={() => setActiveSection("beranda")}
          className="flex items-center gap-3 group"
        >
          <div className="w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
            <Leaf size={20} className="text-white" />
          </div>
          <div className="text-left leading-tight">
            <p className={`font-bold text-sm transition-colors ${scrolled ? "text-foreground" : "text-white"}`} style={{ fontFamily: "var(--font-sans)" }}>
              Desa Klego
            </p>
            <p className={`text-xs transition-colors ${scrolled ? "text-muted-foreground" : "text-green-200"}`}>
              Kec. Klego, Kab. Boyolali
            </p>
          </div>
        </button>

        {/* Desktop nav */}
        <nav className="hidden lg:flex items-center gap-1">
          {navLinks.map((link) => (
            <div key={link.id} className="relative group">
              <button
                onClick={() => setActiveSection(link.id)}
                onMouseEnter={() => link.sub.length > 0 && setOpenDrop(link.id)}
                onMouseLeave={() => setOpenDrop(null)}
                className={`flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                  activeSection === link.id
                    ? "bg-primary/10 text-primary"
                    : scrolled
                    ? "text-foreground hover:text-primary hover:bg-primary/5"
                    : "text-white/90 hover:text-white hover:bg-white/10"
                }`}
              >
                {link.label}
                {link.sub.length > 0 && <ChevronDown size={13} className="opacity-60" />}
              </button>

              {link.sub.length > 0 && openDrop === link.id && (
                <div
                  onMouseEnter={() => setOpenDrop(link.id)}
                  onMouseLeave={() => setOpenDrop(null)}
                  className="absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-border py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150"
                >
                  {link.sub.map((s) => (
                    <button
                      key={s}
                      onClick={() => { setActiveSection(link.id); setOpenDrop(null); }}
                      className="w-full text-left px-4 py-2 text-sm text-foreground hover:bg-secondary hover:text-primary transition-colors flex items-center gap-2"
                    >
                      <ChevronRight size={12} className="text-primary" />
                      {s}
                    </button>
                  ))}
                </div>
              )}
            </div>
          ))}
        </nav>

        {/* Search + mobile toggle */}
        <div className="flex items-center gap-2">
          <button className={`p-2 rounded-lg transition-colors ${scrolled ? "text-foreground hover:bg-secondary" : "text-white hover:bg-white/10"}`}>
            <Search size={18} />
          </button>
          <button
            className={`lg:hidden p-2 rounded-lg transition-colors ${scrolled ? "text-foreground hover:bg-secondary" : "text-white hover:bg-white/10"}`}
            onClick={() => setMobileOpen(!mobileOpen)}
          >
            {mobileOpen ? <X size={20} /> : <Menu size={20} />}
          </button>
        </div>
      </div>

      {/* Mobile menu */}
      {mobileOpen && (
        <div className="lg:hidden bg-white border-t border-border shadow-lg">
          {navLinks.map((link) => (
            <div key={link.id}>
              <button
                onClick={() => { setActiveSection(link.id); setMobileOpen(false); }}
                className="w-full text-left px-6 py-3 text-sm font-medium text-foreground hover:bg-secondary hover:text-primary transition-colors"
              >
                {link.label}
              </button>
              {link.sub.map((s) => (
                <button
                  key={s}
                  onClick={() => { setActiveSection(link.id); setMobileOpen(false); }}
                  className="w-full text-left px-10 py-2 text-xs text-muted-foreground hover:text-primary hover:bg-secondary/50 transition-colors"
                >
                  {s}
                </button>
              ))}
            </div>
          ))}
          <div className="px-6 py-4 border-t border-border">
            <button onClick={onAdmin} className="w-full bg-primary text-white py-2 rounded-lg text-sm font-medium">
              Masuk Admin Panel
            </button>
          </div>
        </div>
      )}
    </header>
  );
}

// ─── HERO ────────────────────────────────────────────────────────────────────

function Hero({ setActiveSection }: any) {
  return (
    <section className="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
      {/* Background */}
      <div className="absolute inset-0">
        <img
          src={unsplash("photo-1586348943529-beaae6c28db9", 1920, 1080)}
          alt="Panorama Desa Klego"
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-green-950/70 via-green-900/60 to-green-950/80" />
      </div>

      {/* Decorative overlay */}
      <div className="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-[#f3f8f5] to-transparent" />

      {/* Content */}
      <div className="relative z-10 text-center text-white px-6 max-w-4xl mx-auto">
        <div className="mb-4 flex items-center justify-center gap-2">
          <div className="h-px w-12 bg-yellow-400/60" />
          <span className="text-yellow-300 text-xs font-mono uppercase tracking-widest">
            Pemerintah Desa Klego
          </span>
          <div className="h-px w-12 bg-yellow-400/60" />
        </div>

        <h1
          className="text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-4"
          style={{ fontFamily: "var(--font-serif)" }}
        >
          Desa Klego
        </h1>

        <p className="text-lg md:text-xl text-green-100 mb-2 font-light">
          Kecamatan Klego, Kabupaten Boyolali, Jawa Tengah
        </p>

        <p className="text-base md:text-lg text-yellow-200 font-medium italic mb-8" style={{ fontFamily: "var(--font-serif)" }}>
          "Maju Bersama, Mandiri Sejahtera, Hijau Lestari"
        </p>

        {/* Stats row */}
        <div className="flex flex-wrap items-center justify-center gap-6 mb-10">
          {[
            { v: "4.823", l: "Jiwa" },
            { v: "1.456", l: "KK" },
            { v: "487 Ha", l: "Luas Wilayah" },
            { v: "5", l: "Dusun" },
          ].map(({ v, l }) => (
            <div key={l} className="text-center">
              <p className="text-2xl font-bold text-yellow-300">{v}</p>
              <p className="text-xs text-green-200 uppercase tracking-wide">{l}</p>
            </div>
          ))}
        </div>

        {/* CTA */}
        <div className="flex flex-wrap items-center justify-center gap-3">
          <button
            onClick={() => setActiveSection("pelayanan")}
            className="bg-primary hover:bg-green-700 text-white px-7 py-3 rounded-xl font-semibold transition-all hover:shadow-lg hover:-translate-y-0.5 text-sm"
          >
            Layanan Publik
          </button>
          <button
            onClick={() => setActiveSection("profil")}
            className="bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/30 text-white px-7 py-3 rounded-xl font-semibold transition-all text-sm"
          >
            Profil Desa
          </button>
          <button
            onClick={() => setActiveSection("potensi")}
            className="bg-amber-500/80 hover:bg-amber-500 backdrop-blur-sm text-white px-7 py-3 rounded-xl font-semibold transition-all text-sm"
          >
            Potensi Desa
          </button>
        </div>
      </div>

      {/* Scroll indicator */}
      <div className="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce z-10">
        <div className="w-6 h-9 border-2 border-white/40 rounded-full flex items-start justify-center p-1">
          <div className="w-1 h-2 bg-white/70 rounded-full animate-[bounce_1.5s_ease-in-out_infinite]" />
        </div>
      </div>
    </section>
  );
}

// ─── HOME SECTION ────────────────────────────────────────────────────────────

function HomeSection({ setActiveSection }: any) {
  const [activeTab, setActiveTab] = useState<"berita" | "agenda" | "pengumuman">("berita");

  return (
    <div>
      <Hero setActiveSection={setActiveSection} />

      {/* Quick stats */}
      <div className="bg-primary py-8">
        <div className="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-4">
          {[
            { icon: Users, v: "4.823", l: "Total Penduduk" },
            { icon: Home, v: "1.456", l: "Kepala Keluarga" },
            { icon: Map, v: "487 Ha", l: "Luas Wilayah" },
            { icon: Building, v: "5", l: "Jumlah Dusun" },
          ].map(({ icon: Icon, v, l }) => (
            <div key={l} className="flex items-center gap-3 text-white">
              <div className="w-10 h-10 rounded-lg bg-white/15 flex items-center justify-center">
                <Icon size={18} />
              </div>
              <div>
                <p className="font-bold text-lg leading-none">{v}</p>
                <p className="text-xs text-green-200">{l}</p>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Profil singkat + Sambutan */}
      <section className="max-w-7xl mx-auto px-6 py-20">
        <div className="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <span className="inline-block text-xs font-mono uppercase tracking-widest text-[#c4891f] bg-amber-50 px-3 py-1 rounded-full mb-4">
              Tentang Kami
            </span>
            <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4" style={{ fontFamily: "var(--font-serif)" }}>
              Selamat Datang di<br />Desa Klego
            </h2>
            <p className="text-muted-foreground text-sm leading-relaxed mb-4">
              Desa Klego adalah desa yang terletak di Kecamatan Klego, Kabupaten Boyolali, Provinsi Jawa Tengah. Desa ini memiliki kekayaan alam berupa lahan pertanian yang subur, sumber daya manusia yang potensial, serta budaya masyarakat yang kuat dan harmonis.
            </p>
            <p className="text-muted-foreground text-sm leading-relaxed mb-6">
              Dengan luas wilayah 487 hektar yang terbagi dalam 5 dusun, 6 RW, dan 18 RT, Desa Klego terus berkomitmen untuk mewujudkan pemerintahan yang transparan, akuntabel, dan berorientasi pada kesejahteraan masyarakat.
            </p>
            <div className="grid grid-cols-2 gap-3">
              {[
                { l: "Dusun", v: "5" }, { l: "RW", v: "6" },
                { l: "RT", v: "18" }, { l: "Lembaga Aktif", v: "12" },
              ].map(({ l, v }) => (
                <div key={l} className="bg-secondary rounded-xl p-3 border border-border">
                  <p className="text-2xl font-bold text-primary">{v}</p>
                  <p className="text-xs text-muted-foreground">{l}</p>
                </div>
              ))}
            </div>
          </div>

          {/* Sambutan Kades */}
          <div className="relative">
            <div className="bg-white rounded-3xl shadow-lg border border-border overflow-hidden">
              <div className="bg-primary h-24 relative">
                <div className="absolute inset-0 opacity-20" style={{ backgroundImage: "radial-gradient(circle at 70% 50%, #52b87a 0%, transparent 60%)" }} />
                <p className="absolute bottom-4 left-6 text-white/70 text-xs uppercase tracking-widest font-mono">Sambutan Kepala Desa</p>
              </div>
              <div className="relative -mt-8 flex justify-center">
                <div className="w-20 h-20 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-muted">
                  <img
                    src={unsplash("photo-1506794778202-cad84cf45f1d", 160, 160)}
                    alt="Kepala Desa Klego"
                    className="w-full h-full object-cover"
                  />
                </div>
              </div>
              <div className="px-6 pb-6 pt-3 text-center">
                <p className="font-bold text-foreground">Bapak Suwito, S.Pd.</p>
                <p className="text-xs text-muted-foreground mb-4">Kepala Desa Klego, Periode 2021–2027</p>
                <p className="text-sm text-muted-foreground leading-relaxed italic" style={{ fontFamily: "var(--font-serif)" }}>
                  "Bersama seluruh perangkat dan masyarakat Desa Klego, kami berkomitmen untuk mewujudkan pemerintahan yang bersih, transparan, dan terus berinovasi demi kesejahteraan bersama. Selamat datang di portal resmi Desa Klego."
                </p>
                <div className="mt-4 flex items-center justify-center gap-1">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} size={12} className="fill-amber-400 text-amber-400" />
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Berita / Agenda / Pengumuman */}
      <section className="bg-secondary/40 py-20">
        <div className="max-w-7xl mx-auto px-6">
          <SectionHeading label="Informasi Terkini" title="Berita, Agenda & Pengumuman" subtitle="Tetap update dengan informasi terbaru seputar Desa Klego" />

          {/* Tabs */}
          <div className="flex items-center justify-center gap-2 mb-8">
            {(["berita", "agenda", "pengumuman"] as const).map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium transition-all ${
                  activeTab === tab
                    ? "bg-primary text-white shadow-md"
                    : "bg-white text-muted-foreground hover:text-primary border border-border"
                }`}
              >
                {tab === "berita" && <Newspaper size={14} />}
                {tab === "agenda" && <Calendar size={14} />}
                {tab === "pengumuman" && <Megaphone size={14} />}
                {tab.charAt(0).toUpperCase() + tab.slice(1)}
              </button>
            ))}
          </div>

          {activeTab === "berita" && (
            <div className="grid md:grid-cols-3 gap-6">
              {beritaList.map((b) => (
                <article key={b.id} className="bg-white rounded-2xl overflow-hidden shadow-sm border border-border hover:shadow-md transition-all hover:-translate-y-1 group">
                  <div className="relative h-44 overflow-hidden bg-muted">
                    <img
                      src={unsplash(b.img, 600, 350)}
                      alt={b.judul}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                    <span className="absolute top-3 left-3 bg-primary text-white text-xs px-2.5 py-1 rounded-full font-medium">
                      {b.kategori}
                    </span>
                  </div>
                  <div className="p-5">
                    <p className="text-xs text-muted-foreground mb-2 flex items-center gap-1.5">
                      <Calendar size={11} /> {b.tanggal}
                    </p>
                    <h3 className="font-bold text-foreground text-sm leading-snug mb-2 group-hover:text-primary transition-colors">
                      {b.judul}
                    </h3>
                    <p className="text-xs text-muted-foreground leading-relaxed line-clamp-2">{b.ringkasan}</p>
                    <button className="mt-3 text-xs text-primary font-semibold flex items-center gap-1 hover:gap-2 transition-all">
                      Baca Selengkapnya <ChevronRight size={12} />
                    </button>
                  </div>
                </article>
              ))}
            </div>
          )}

          {activeTab === "agenda" && (
            <div className="max-w-2xl mx-auto space-y-3">
              {agendaList.map((a, i) => (
                <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm flex items-center gap-5 hover:border-primary/30 transition-colors">
                  <div className="w-14 h-14 rounded-xl bg-primary flex flex-col items-center justify-center text-white flex-shrink-0">
                    <span className="text-base font-bold leading-none">{a.tanggal.split(" ")[0]}</span>
                    <span className="text-xs opacity-80">{a.tanggal.split(" ").slice(1).join(" ")}</span>
                  </div>
                  <div className="flex-1">
                    <p className="font-semibold text-foreground text-sm">{a.judul}</p>
                    <div className="flex items-center gap-4 mt-1">
                      <span className="text-xs text-muted-foreground flex items-center gap-1"><MapPin size={10} /> {a.lokasi}</span>
                      <span className="text-xs text-muted-foreground flex items-center gap-1"><Clock size={10} /> {a.jam}</span>
                    </div>
                  </div>
                  <span className="w-2 h-2 rounded-full bg-primary flex-shrink-0" />
                </div>
              ))}
            </div>
          )}

          {activeTab === "pengumuman" && (
            <div className="max-w-2xl mx-auto space-y-3">
              {pengumumanList.map((p, i) => (
                <div key={i} className={`bg-white rounded-2xl p-5 border shadow-sm flex items-center gap-4 hover:shadow-md transition-all ${p.penting ? "border-amber-200" : "border-border"}`}>
                  {p.penting && <span className="w-2 h-2 rounded-full bg-amber-400 flex-shrink-0" />}
                  {!p.penting && <span className="w-2 h-2 rounded-full bg-green-400 flex-shrink-0" />}
                  <div className="flex-1">
                    <p className="font-semibold text-foreground text-sm">{p.judul}</p>
                    <p className="text-xs text-muted-foreground mt-0.5">{p.tanggal}</p>
                  </div>
                  {p.penting && (
                    <span className="text-xs bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full font-medium">Penting</span>
                  )}
                  <ChevronRight size={14} className="text-muted-foreground" />
                </div>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Layanan Publik */}
      <section className="max-w-7xl mx-auto px-6 py-20">
        <SectionHeading
          label="Layanan Administrasi"
          title="Pelayanan Publik Desa"
          subtitle="Kami hadir untuk memudahkan urusan administrasi kependudukan Anda"
        />
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {layananList.map((l, i) => (
            <div key={i} className="bg-white rounded-2xl p-6 border border-border shadow-sm hover:border-primary/40 hover:shadow-md transition-all group cursor-pointer">
              <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-all">
                <l.icon size={22} className="text-primary group-hover:text-white transition-colors" />
              </div>
              <h3 className="font-semibold text-foreground text-sm mb-2">{l.judul}</h3>
              <div className="flex items-center gap-3 mt-3">
                <span className="text-xs bg-secondary text-primary px-2.5 py-1 rounded-full flex items-center gap-1">
                  <Clock size={10} /> {l.waktu}
                </span>
                <span className="text-xs text-muted-foreground">{l.syarat}</span>
              </div>
            </div>
          ))}
        </div>
        <div className="text-center mt-8">
          <button
            onClick={() => setActiveSection("pelayanan")}
            className="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors text-sm shadow-md hover:shadow-lg"
          >
            Lihat Semua Layanan
          </button>
        </div>
      </section>

      {/* Potensi Desa */}
      <section className="bg-primary py-20 relative overflow-hidden">
        <div className="absolute inset-0 opacity-5" style={{ backgroundImage: "radial-gradient(circle at 20% 80%, #52b87a 0%, transparent 50%), radial-gradient(circle at 80% 20%, #c4891f 0%, transparent 50%)" }} />
        <div className="max-w-7xl mx-auto px-6 relative">
          <div className="text-center mb-12">
            <span className="text-xs font-mono uppercase tracking-widest text-yellow-300">Unggulan Desa</span>
            <h2 className="text-3xl md:text-4xl font-bold text-white mt-2" style={{ fontFamily: "var(--font-serif)" }}>
              Potensi Desa Klego
            </h2>
            <p className="mt-3 text-green-200 text-sm max-w-xl mx-auto">Kekayaan sumber daya alam dan manusia yang menjadi fondasi kemakmuran desa</p>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {potensiList.map((p, i) => (
              <div key={i} className="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 hover:bg-white/15 transition-all group">
                <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${p.color} flex items-center justify-center mb-4 shadow-md`}>
                  <p.icon size={22} className="text-white" />
                </div>
                <p className="text-2xl font-bold text-yellow-300 mb-0.5">{p.stat}</p>
                <p className="text-xs text-green-300 mb-3">{p.satuan}</p>
                <h3 className="font-semibold text-white mb-2">{p.judul}</h3>
                <p className="text-xs text-green-200 leading-relaxed">{p.deskripsi}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Gallery */}
      <section className="max-w-7xl mx-auto px-6 py-20">
        <SectionHeading label="Galeri Desa" title="Kegiatan & Dokumentasi" subtitle="Rekam jejak kegiatan dan kehidupan masyarakat Desa Klego" />
        <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
          {galeriList.map((g, i) => (
            <div
              key={i}
              className={`relative overflow-hidden rounded-2xl bg-muted group cursor-pointer ${i === 0 ? "md:col-span-2 md:row-span-2" : ""}`}
            >
              <img
                src={unsplash(g.img, i === 0 ? 900 : 500, i === 0 ? 600 : 350)}
                alt={g.alt}
                className={`w-full object-cover group-hover:scale-105 transition-transform duration-500 ${i === 0 ? "h-64 md:h-full" : "h-44"}`}
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                <p className="text-white text-sm font-medium">{g.caption}</p>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Peta */}
      <section className="max-w-7xl mx-auto px-6 pb-20">
        <SectionHeading label="Lokasi" title="Peta Desa Klego" subtitle="Jl. Raya Klego, Kecamatan Klego, Kabupaten Boyolali, Jawa Tengah 57385" />
        <div className="rounded-3xl overflow-hidden shadow-lg border border-border h-80 bg-muted">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31678.02!2d110.7!3d-7.45!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a7d6c8f1a7e21%3A0x1!2sKlego%2C+Boyolali!5e0!3m2!1sid!2sid!4v1689500000000!5m2!1sid!2sid"
            width="100%"
            height="100%"
            style={{ border: 0 }}
            allowFullScreen
            loading="lazy"
            referrerPolicy="no-referrer-when-downgrade"
            title="Peta Desa Klego"
          />
        </div>
      </section>
    </div>
  );
}

// ─── PROFIL DESA ─────────────────────────────────────────────────────────────

function ProfilSection() {
  const [tab, setTab] = useState("sejarah");
  const tabs = [
    { id: "sejarah", label: "Sejarah Desa" },
    { id: "visi", label: "Visi & Misi" },
    { id: "pemdes", label: "Struktur Pemdes" },
    { id: "bpd", label: "Struktur BPD" },
  ];

  const pemdesMembers = [
    { nama: "Suwito, S.Pd.", jabatan: "Kepala Desa", foto: "photo-1506794778202-cad84cf45f1d" },
    { nama: "Wagimin, A.Md.", jabatan: "Sekretaris Desa", foto: "photo-1519085360753-af0119f7cbe7" },
    { nama: "Sri Wahyuni", jabatan: "Kaur Keuangan", foto: "photo-1573497019940-1c28c88b4f3e" },
    { nama: "Budi Santoso", jabatan: "Kaur Perencanaan", foto: "photo-1500648767791-00dcc994a43e" },
    { nama: "Siti Aminah", jabatan: "Kasi Pemerintahan", foto: "photo-1488426862026-3ee34a7d66df" },
    { nama: "Joko Purnomo", jabatan: "Kasi Kesejahteraan", foto: "photo-1507003211169-0a1dd7228f2d" },
    { nama: "Rina Sulistyowati", jabatan: "Kasi Pelayanan", foto: "photo-1438761681033-6461ffad8d80" },
    { nama: "Mulyadi", jabatan: "Kepala Dusun Klego", foto: "photo-1472099645785-5658abf4ff4e" },
  ];

  return (
    <div className="min-h-screen">
      {/* Header */}
      <div className="bg-primary py-20 text-white text-center relative overflow-hidden">
        <div className="absolute inset-0 opacity-10" style={{ backgroundImage: "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")" }} />
        <div className="relative">
          <p className="text-green-200 text-xs font-mono uppercase tracking-widest mb-2">Mengenal Lebih Dekat</p>
          <h1 className="text-4xl font-bold" style={{ fontFamily: "var(--font-serif)" }}>Profil Desa Klego</h1>
          <p className="text-green-200 mt-2 text-sm">Kecamatan Klego, Kabupaten Boyolali, Jawa Tengah</p>
        </div>
      </div>

      {/* Tab navigation */}
      <div className="sticky top-16 bg-white border-b border-border z-30 shadow-sm">
        <div className="max-w-5xl mx-auto px-6 flex items-center gap-1 overflow-x-auto">
          {tabs.map((t) => (
            <button
              key={t.id}
              onClick={() => setTab(t.id)}
              className={`px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors ${
                tab === t.id
                  ? "border-primary text-primary"
                  : "border-transparent text-muted-foreground hover:text-foreground"
              }`}
            >
              {t.label}
            </button>
          ))}
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-6 py-12">
        {tab === "sejarah" && (
          <div className="grid md:grid-cols-3 gap-8">
            <div className="md:col-span-2 space-y-4">
              <h2 className="text-2xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>Sejarah Desa Klego</h2>
              <p className="text-muted-foreground text-sm leading-relaxed">
                Desa Klego memiliki sejarah panjang yang berakar pada tradisi masyarakat agraris Jawa Tengah. Konon, nama "Klego" berasal dari nama pohon <em>klego</em> (sejenis tanaman obat tradisional) yang dahulu banyak tumbuh di wilayah ini dan digunakan oleh masyarakat setempat sebagai obat-obatan.
              </p>
              <p className="text-muted-foreground text-sm leading-relaxed">
                Pada masa kolonial Belanda, Desa Klego sudah terbentuk sebagai satu kesatuan administratif yang memiliki sistem pemerintahan desa tradisional. Kepala desa pertama yang tercatat dalam sejarah adalah Mbah Wiryo Dikromo yang memimpin pada awal abad ke-20.
              </p>
              <p className="text-muted-foreground text-sm leading-relaxed">
                Pasca kemerdekaan Indonesia, Desa Klego terus berkembang dengan dukungan program pembangunan desa dari pemerintah. Berbagai infrastruktur seperti sekolah, puskesmas pembantu, jalan desa, dan fasilitas umum lainnya mulai dibangun secara bertahap.
              </p>
              <p className="text-muted-foreground text-sm leading-relaxed">
                Pada era reformasi dan desentralisasi, Desa Klego semakin mandiri dalam mengelola pemerintahan dan pembangunan desa. Dengan dukungan Dana Desa sejak 2015, berbagai program pemberdayaan masyarakat dan pembangunan infrastruktur berhasil diwujudkan.
              </p>
            </div>
            <div className="space-y-4">
              <div className="bg-white rounded-2xl p-5 border border-border shadow-sm">
                <h3 className="font-semibold text-foreground text-sm mb-3">Data Wilayah</h3>
                {[
                  ["Luas Wilayah", "487 Ha"],
                  ["Luas Sawah", "312 Ha"],
                  ["Luas Tegalan", "98 Ha"],
                  ["Luas Pemukiman", "77 Ha"],
                  ["Jumlah Dusun", "5 Dusun"],
                  ["Jumlah RW", "6 RW"],
                  ["Jumlah RT", "18 RT"],
                  ["Ketinggian", "±350 mdpl"],
                ].map(([k, v]) => (
                  <div key={k} className="flex justify-between py-2 border-b border-border last:border-0">
                    <span className="text-xs text-muted-foreground">{k}</span>
                    <span className="text-xs font-semibold text-foreground">{v}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}

        {tab === "visi" && (
          <div className="space-y-8">
            <div className="bg-gradient-to-br from-primary to-green-700 rounded-3xl p-8 text-white text-center relative overflow-hidden">
              <div className="absolute inset-0 opacity-10" style={{ backgroundImage: "radial-gradient(circle at 30% 70%, #52b87a, transparent 60%)" }} />
              <p className="text-green-200 text-xs font-mono uppercase tracking-widest mb-4">Visi Desa Klego</p>
              <h2 className="text-2xl md:text-3xl font-bold leading-relaxed" style={{ fontFamily: "var(--font-serif)" }}>
                "Terwujudnya Desa Klego yang Maju, Mandiri, Sejahtera, dan Berbudaya Berlandaskan Nilai-nilai Gotong Royong"
              </h2>
            </div>
            <div>
              <h3 className="text-xl font-bold text-foreground mb-6" style={{ fontFamily: "var(--font-serif)" }}>Misi Desa Klego</h3>
              <div className="space-y-4">
                {[
                  "Meningkatkan kualitas penyelenggaraan pemerintahan desa yang transparan, akuntabel, dan partisipatif.",
                  "Meningkatkan kualitas sumber daya manusia melalui pendidikan, kesehatan, dan pemberdayaan masyarakat.",
                  "Meningkatkan pembangunan infrastruktur desa yang merata dan berkelanjutan untuk mendukung aktivitas ekonomi masyarakat.",
                  "Mengembangkan potensi ekonomi desa berbasis pertanian, UMKM, dan pariwisata untuk meningkatkan pendapatan masyarakat.",
                  "Melestarikan nilai-nilai budaya, kearifan lokal, dan lingkungan hidup demi keberlanjutan generasi mendatang.",
                  "Meningkatkan ketertiban, keamanan, dan kerukunan antarwarga dalam kehidupan bermasyarakat.",
                ].map((misi, i) => (
                  <div key={i} className="flex items-start gap-4 bg-white rounded-xl p-4 border border-border shadow-sm">
                    <div className="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center flex-shrink-0 font-bold text-sm">
                      {i + 1}
                    </div>
                    <p className="text-sm text-muted-foreground leading-relaxed pt-1">{misi}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}

        {tab === "pemdes" && (
          <div>
            <h2 className="text-2xl font-bold text-foreground mb-8 text-center" style={{ fontFamily: "var(--font-serif)" }}>
              Struktur Pemerintah Desa Klego
            </h2>
            <div className="grid sm:grid-cols-2 md:grid-cols-4 gap-4">
              {pemdesMembers.map((m, i) => (
                <div key={i} className={`bg-white rounded-2xl p-5 border border-border shadow-sm text-center hover:shadow-md transition-shadow ${i === 0 ? "md:col-span-4 max-w-sm mx-auto" : ""}`}>
                  <div className="w-16 h-16 rounded-2xl overflow-hidden bg-muted mx-auto mb-3">
                    <img src={unsplash(m.foto, 128, 128)} alt={m.nama} className="w-full h-full object-cover" />
                  </div>
                  <p className="font-semibold text-foreground text-sm">{m.nama}</p>
                  <p className="text-xs text-primary mt-1 font-medium">{m.jabatan}</p>
                </div>
              ))}
            </div>
          </div>
        )}

        {tab === "bpd" && (
          <div>
            <h2 className="text-2xl font-bold text-foreground mb-4 text-center" style={{ fontFamily: "var(--font-serif)" }}>
              Struktur Badan Permusyawaratan Desa
            </h2>
            <p className="text-center text-muted-foreground text-sm mb-8">Periode 2021–2027</p>
            <div className="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
              {[
                { nama: "H. Ahmad Fauzi, S.H.", jabatan: "Ketua BPD", dusun: "Dusun Klego" },
                { nama: "Suparyanto", jabatan: "Wakil Ketua BPD", dusun: "Dusun Ponggok" },
                { nama: "Endang Setiyowati", jabatan: "Sekretaris BPD", dusun: "Dusun Soka" },
                { nama: "Purwanto", jabatan: "Anggota BPD", dusun: "Dusun Rejosari" },
                { nama: "Sunarti", jabatan: "Anggota BPD", dusun: "Dusun Ngemplak" },
                { nama: "Karsono", jabatan: "Anggota BPD", dusun: "Dusun Klego" },
              ].map((m, i) => (
                <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm hover:border-primary/30 transition-colors">
                  <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
                    <User size={22} className="text-primary" />
                  </div>
                  <p className="font-semibold text-foreground text-sm">{m.nama}</p>
                  <p className="text-xs text-primary font-medium mt-1">{m.jabatan}</p>
                  <p className="text-xs text-muted-foreground mt-1 flex items-center gap-1"><MapPin size={10} /> {m.dusun}</p>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

// ─── INFOGRAFIS ──────────────────────────────────────────────────────────────

function InfografiSection() {
  const COLORS = ["#165f36", "#2e9e5b", "#c4891f", "#52b87a", "#8ecba5"];

  return (
    <div className="min-h-screen">
      <div className="bg-primary py-16 text-white text-center">
        <p className="text-green-200 text-xs font-mono uppercase tracking-widest mb-2">Data Desa</p>
        <h1 className="text-4xl font-bold" style={{ fontFamily: "var(--font-serif)" }}>Infografis Catatan Sipil</h1>
        <p className="text-green-200 mt-2 text-sm">Data kependudukan Desa Klego tahun 2025</p>
      </div>

      <div className="max-w-7xl mx-auto px-6 py-12 space-y-12">
        {/* Summary stats */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          <StatCard icon={Users} value={demo.penduduk} label="Total Penduduk" color="bg-primary" />
          <StatCard icon={Home} value={demo.kk} label="Kepala Keluarga" color="bg-green-600" />
          <StatCard icon={User} value={demo.lakiLaki} label="Laki-laki" color="bg-blue-600" />
          <StatCard icon={User} value={demo.perempuan} label="Perempuan" color="bg-rose-500" />
        </div>

        {/* Charts row 1 */}
        <div className="grid md:grid-cols-2 gap-6">
          {/* Gender pie */}
          <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
            <h3 className="font-semibold text-foreground mb-4 text-sm">Persebaran Jenis Kelamin</h3>
            <div className="h-60">
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie data={genderPie} cx="50%" cy="50%" outerRadius={90} dataKey="value" label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`} labelLine={false}>
                    {genderPie.map((entry, i) => (
                      <Cell key={i} fill={entry.fill} />
                    ))}
                  </Pie>
                  <Tooltip formatter={(v: any) => `${v.toLocaleString("id-ID")} jiwa`} />
                  <Legend />
                </PieChart>
              </ResponsiveContainer>
            </div>
          </div>

          {/* Age bar */}
          <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
            <h3 className="font-semibold text-foreground mb-4 text-sm">Kelompok Umur Penduduk</h3>
            <div className="h-60">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={ageData} margin={{ top: 0, right: 0, left: -20, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e4f2ea" />
                  <XAxis dataKey="name" tick={{ fontSize: 11 }} />
                  <YAxis tick={{ fontSize: 11 }} />
                  <Tooltip formatter={(v: any) => `${v.toLocaleString("id-ID")} jiwa`} />
                  <Bar dataKey="value" radius={[4, 4, 0, 0]}>
                    {ageData.map((entry, i) => (
                      <Cell key={i} fill={COLORS[i % COLORS.length]} />
                    ))}
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>
        </div>

        {/* Charts row 2 */}
        <div className="grid md:grid-cols-2 gap-6">
          {/* Dusun bar */}
          <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
            <h3 className="font-semibold text-foreground mb-4 text-sm">Persebaran Penduduk per Dusun</h3>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={dusunData} layout="vertical" margin={{ top: 0, right: 20, left: 20, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e4f2ea" horizontal={false} />
                  <XAxis type="number" tick={{ fontSize: 11 }} />
                  <YAxis dataKey="name" type="category" tick={{ fontSize: 11 }} width={65} />
                  <Tooltip formatter={(v: any) => `${v.toLocaleString("id-ID")} jiwa`} />
                  <Bar dataKey="penduduk" fill="#165f36" radius={[0, 4, 4, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>

          {/* Population trend */}
          <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
            <h3 className="font-semibold text-foreground mb-4 text-sm">Tren Pertumbuhan Penduduk</h3>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={populationTrend} margin={{ top: 0, right: 0, left: -20, bottom: 0 }}>
                  <defs>
                    <linearGradient id="popGradient" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#165f36" stopOpacity={0.2} />
                      <stop offset="95%" stopColor="#165f36" stopOpacity={0} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e4f2ea" />
                  <XAxis dataKey="year" tick={{ fontSize: 11 }} />
                  <YAxis tick={{ fontSize: 11 }} domain={[4400, 4900]} />
                  <Tooltip formatter={(v: any) => `${v.toLocaleString("id-ID")} jiwa`} />
                  <Area type="monotone" dataKey="penduduk" stroke="#165f36" strokeWidth={2.5} fill="url(#popGradient)" dot={{ fill: "#165f36", strokeWidth: 2, r: 4 }} />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </div>
        </div>

        {/* Dusun detail */}
        <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
          <h3 className="font-semibold text-foreground mb-4 text-sm">Detail Penduduk per Dusun</h3>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border">
                  <th className="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Dusun</th>
                  <th className="text-right py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Laki-laki</th>
                  <th className="text-right py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Perempuan</th>
                  <th className="text-right py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Total</th>
                  <th className="text-right py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">KK</th>
                  <th className="py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Proporsi</th>
                </tr>
              </thead>
              <tbody>
                {[
                  { dusun: "Klego", l: 621, p: 622, kk: 378 },
                  { dusun: "Ponggok", l: 493, p: 494, kk: 301 },
                  { dusun: "Soka", l: 438, p: 438, kk: 267 },
                  { dusun: "Rejosari", l: 382, p: 383, kk: 233 },
                  { dusun: "Ngemplak", l: 477, p: 475, kk: 277 },
                ].map((row, i) => {
                  const total = row.l + row.p;
                  const pct = Math.round((total / demo.penduduk) * 100);
                  return (
                    <tr key={i} className="border-b border-border/50 hover:bg-secondary/30 transition-colors">
                      <td className="py-3 px-4 font-medium text-foreground">{row.dusun}</td>
                      <td className="py-3 px-4 text-right text-muted-foreground">{row.l.toLocaleString("id-ID")}</td>
                      <td className="py-3 px-4 text-right text-muted-foreground">{row.p.toLocaleString("id-ID")}</td>
                      <td className="py-3 px-4 text-right font-semibold text-foreground">{total.toLocaleString("id-ID")}</td>
                      <td className="py-3 px-4 text-right text-muted-foreground">{row.kk}</td>
                      <td className="py-3 px-4">
                        <div className="flex items-center gap-2">
                          <div className="flex-1 h-1.5 bg-secondary rounded-full overflow-hidden">
                            <div className="h-full bg-primary rounded-full" style={{ width: `${pct}%` }} />
                          </div>
                          <span className="text-xs text-muted-foreground font-mono w-8 text-right">{pct}%</span>
                        </div>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
              <tfoot>
                <tr className="bg-secondary/50">
                  <td className="py-3 px-4 font-bold text-foreground">Total</td>
                  <td className="py-3 px-4 text-right font-bold text-foreground">{demo.lakiLaki.toLocaleString("id-ID")}</td>
                  <td className="py-3 px-4 text-right font-bold text-foreground">{demo.perempuan.toLocaleString("id-ID")}</td>
                  <td className="py-3 px-4 text-right font-bold text-primary">{demo.penduduk.toLocaleString("id-ID")}</td>
                  <td className="py-3 px-4 text-right font-bold text-foreground">{demo.kk.toLocaleString("id-ID")}</td>
                  <td className="py-3 px-4 text-xs text-muted-foreground font-mono">100%</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}

// ─── PRODUK PERATURAN ────────────────────────────────────────────────────────

function PeraturanSection() {
  const [filter, setFilter] = useState("Semua");
  const types = ["Semua", "Peraturan Desa", "Perkades", "APBDes", "Keputusan Kades"];
  const filtered = filter === "Semua" ? peraturanList : peraturanList.filter((p) => p.tipe === filter);

  return (
    <div className="min-h-screen">
      <div className="bg-primary py-16 text-white text-center">
        <p className="text-green-200 text-xs font-mono uppercase tracking-widest mb-2">Regulasi Desa</p>
        <h1 className="text-4xl font-bold" style={{ fontFamily: "var(--font-serif)" }}>Produk Peraturan Desa</h1>
        <p className="text-green-200 mt-2 text-sm">Katalog dokumen resmi dan produk legislasi Desa Klego</p>
      </div>

      <div className="max-w-5xl mx-auto px-6 py-12">
        {/* Filter */}
        <div className="flex flex-wrap gap-2 mb-8">
          {types.map((t) => (
            <button
              key={t}
              onClick={() => setFilter(t)}
              className={`px-4 py-2 rounded-xl text-sm font-medium transition-all ${
                filter === t ? "bg-primary text-white shadow-md" : "bg-white text-muted-foreground border border-border hover:text-primary"
              }`}
            >
              {t}
            </button>
          ))}
        </div>

        <div className="space-y-3">
          {filtered.map((p, i) => (
            <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm flex items-center gap-5 hover:border-primary/30 hover:shadow-md transition-all">
              <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                <FileText size={22} className="text-primary" />
              </div>
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 mb-1">
                  <span className="text-xs bg-secondary text-primary px-2.5 py-0.5 rounded-full font-medium">{p.tipe}</span>
                  <span className="text-xs text-muted-foreground font-mono">No. {p.no}</span>
                </div>
                <p className="font-semibold text-foreground text-sm truncate">{p.judul}</p>
                <p className="text-xs text-muted-foreground mt-1 flex items-center gap-1"><Calendar size={10} /> {p.tgl}</p>
              </div>
              <button className="flex items-center gap-1.5 text-xs text-primary hover:bg-primary hover:text-white border border-primary/30 px-3 py-2 rounded-xl transition-all flex-shrink-0">
                <Download size={13} /> PDF
              </button>
            </div>
          ))}
        </div>

        {/* APBDes highlight */}
        <div className="mt-8 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl p-6 border border-amber-200">
          <div className="flex items-start gap-4">
            <div className="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0">
              <TrendingUp size={22} className="text-white" />
            </div>
            <div>
              <h3 className="font-bold text-foreground mb-1">Anggaran Pendapatan & Belanja Desa (APBDes) 2025</h3>
              <p className="text-sm text-muted-foreground mb-3">Total Anggaran: <span className="font-bold text-foreground">Rp 1.247.500.000</span></p>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                {[
                  { l: "Pendapatan Asli Desa", v: "Rp 45 Jt" },
                  { l: "Dana Desa", v: "Rp 854 Jt" },
                  { l: "ADD", v: "Rp 298 Jt" },
                  { l: "Bagi Hasil Pajak", v: "Rp 50 Jt" },
                ].map(({ l, v }) => (
                  <div key={l} className="bg-white rounded-xl p-3">
                    <p className="text-xs text-muted-foreground mb-1">{l}</p>
                    <p className="text-sm font-bold text-foreground">{v}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

// ─── PELAYANAN & POTENSI ──────────────────────────────────────────────────────

function PelayananSection() {
  const [tab, setTab] = useState("layanan");

  return (
    <div className="min-h-screen">
      <div className="bg-primary py-16 text-white text-center">
        <p className="text-green-200 text-xs font-mono uppercase tracking-widest mb-2">Untuk Masyarakat</p>
        <h1 className="text-4xl font-bold" style={{ fontFamily: "var(--font-serif)" }}>Pelayanan & Potensi Desa</h1>
        <p className="text-green-200 mt-2 text-sm">Layanan administrasi dan kekayaan potensi Desa Klego</p>
      </div>

      <div className="sticky top-16 bg-white border-b border-border z-30">
        <div className="max-w-5xl mx-auto px-6 flex items-center gap-1">
          {[
            { id: "layanan", label: "Layanan Administrasi" },
            { id: "fasilitas", label: "Fasilitas Desa" },
            { id: "potensi", label: "Potensi Unggulan" },
            { id: "umkm", label: "UMKM" },
            { id: "galeri", label: "Galeri Kegiatan" },
          ].map((t) => (
            <button
              key={t.id}
              onClick={() => setTab(t.id)}
              className={`px-4 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors ${
                tab === t.id ? "border-primary text-primary" : "border-transparent text-muted-foreground hover:text-foreground"
              }`}
            >
              {t.label}
            </button>
          ))}
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-6 py-10">
        {tab === "layanan" && (
          <div>
            <div className="bg-primary/5 rounded-2xl p-5 mb-8 flex items-start gap-3 border border-primary/15">
              <Clock size={18} className="text-primary flex-shrink-0 mt-0.5" />
              <div>
                <p className="font-semibold text-foreground text-sm mb-1">Jam Pelayanan</p>
                <p className="text-sm text-muted-foreground">Senin – Kamis: 08.00 – 15.00 WIB &nbsp;|&nbsp; Jumat: 08.00 – 11.30 WIB &nbsp;|&nbsp; Sabtu – Minggu: Libur</p>
              </div>
            </div>
            <div className="grid sm:grid-cols-2 gap-4">
              {[...layananList,
                { icon: Globe, judul: "Surat Keterangan Pindah", waktu: "3 Hari", syarat: "KTP + KK + F1.03" },
                { icon: Star, judul: "Surat Keterangan Lahir", waktu: "1 Hari", syarat: "Surat RS + KK Orang Tua" },
                { icon: Check, judul: "Legalisasi Surat", waktu: "1 Hari", syarat: "Surat asli + KTP" },
                { icon: MapPin, judul: "Surat Keterangan Tanah", waktu: "3 Hari", syarat: "KTP + Bukti Kepemilikan" },
              ].map((l, i) => (
                <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm">
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                      <l.icon size={18} className="text-primary" />
                    </div>
                    <div>
                      <p className="font-semibold text-foreground text-sm">{l.judul}</p>
                      <p className="text-xs text-muted-foreground mt-1">Syarat: {l.syarat}</p>
                      <span className="inline-block mt-2 text-xs bg-secondary text-primary px-2.5 py-0.5 rounded-full">
                        Estimasi: {l.waktu}
                      </span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {tab === "fasilitas" && (
          <div className="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
            {[
              { nama: "Balai Desa", ket: "Pusat kegiatan pemerintahan dan musyawarah", icon: Building },
              { nama: "SD Negeri Klego 1 & 2", ket: "Sekolah dasar negeri di wilayah desa", icon: BookOpen },
              { nama: "Puskesmas Pembantu", ket: "Layanan kesehatan dasar untuk warga", icon: Heart },
              { nama: "Masjid & Mushola", ket: "8 masjid dan 14 mushola aktif", icon: Star },
              { nama: "Lapangan Olahraga", ket: "Lapangan sepak bola dan voli multi-guna", icon: Award },
              { nama: "TPQ & Ponpes", ket: "Lembaga pendidikan keagamaan", icon: BookOpen },
              { nama: "Pasar Desa", ket: "Pasar tradisional buka setiap pagi", icon: Package },
              { nama: "BUMDES", ket: "Badan Usaha Milik Desa aktif beroperasi", icon: TrendingUp },
              { nama: "Embung Desa", ket: "Penampungan air untuk irigasi pertanian", icon: Leaf },
            ].map((f, i) => (
              <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm hover:border-primary/30 transition-colors">
                <div className="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
                  <f.icon size={18} className="text-primary" />
                </div>
                <p className="font-semibold text-foreground text-sm mb-1">{f.nama}</p>
                <p className="text-xs text-muted-foreground leading-relaxed">{f.ket}</p>
              </div>
            ))}
          </div>
        )}

        {tab === "potensi" && (
          <div className="space-y-6">
            {potensiList.map((p, i) => (
              <div key={i} className="bg-white rounded-2xl overflow-hidden border border-border shadow-sm flex flex-col md:flex-row">
                <div className={`bg-gradient-to-br ${p.color} p-8 flex flex-col items-center justify-center text-white min-w-[160px]`}>
                  <p.icon size={36} className="mb-2" />
                  <p className="text-3xl font-bold">{p.stat}</p>
                  <p className="text-xs opacity-80">{p.satuan}</p>
                </div>
                <div className="p-6">
                  <h3 className="font-bold text-foreground text-lg mb-2" style={{ fontFamily: "var(--font-serif)" }}>{p.judul}</h3>
                  <p className="text-sm text-muted-foreground leading-relaxed">{p.deskripsi}</p>
                </div>
              </div>
            ))}
          </div>
        )}

        {tab === "umkm" && (
          <div>
            <div className="grid grid-cols-3 gap-4 mb-8">
              <StatCard icon={Package} value={87} label="Pelaku UMKM" color="bg-amber-500" />
              <StatCard icon={Users} value={234} label="Tenaga Kerja" color="bg-primary" />
              <StatCard icon={TrendingUp} value="Rp 2,3 M" label="Omzet/Tahun" color="bg-teal-600" />
            </div>
            <div className="grid sm:grid-cols-2 gap-4">
              {[
                { nama: "Kelompok Kerajinan Bambu Karya Mandiri", kategori: "Kerajinan", anggota: 15, omzet: "Rp 120 Jt/tahun", kontak: "Ibu Sumarni" },
                { nama: "UMKM Batik Tulis Klego Heritage", kategori: "Fashion", anggota: 8, omzet: "Rp 85 Jt/tahun", kontak: "Ibu Wahyuni" },
                { nama: "Kelompok Olahan Singkong Lestari", kategori: "Pangan", anggota: 12, omzet: "Rp 67 Jt/tahun", kontak: "Bapak Haryono" },
                { nama: "Konveksi Maju Bersama", kategori: "Fashion", anggota: 20, omzet: "Rp 210 Jt/tahun", kontak: "Bapak Santosa" },
                { nama: "Kelompok Budidaya Lele Sejahtera", kategori: "Perikanan", anggota: 10, omzet: "Rp 95 Jt/tahun", kontak: "Bapak Widodo" },
                { nama: "Warung Kuliner Masakan Jawa", kategori: "Kuliner", anggota: 5, omzet: "Rp 48 Jt/tahun", kontak: "Ibu Hartini" },
              ].map((u, i) => (
                <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm hover:shadow-md transition-shadow">
                  <div className="flex items-start justify-between mb-3">
                    <h3 className="font-semibold text-foreground text-sm leading-snug flex-1 pr-3">{u.nama}</h3>
                    <span className="text-xs bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full flex-shrink-0">{u.kategori}</span>
                  </div>
                  <div className="space-y-1.5">
                    <p className="text-xs text-muted-foreground flex items-center gap-2"><Users size={11} /> {u.anggota} anggota</p>
                    <p className="text-xs text-muted-foreground flex items-center gap-2"><TrendingUp size={11} /> {u.omzet}</p>
                    <p className="text-xs text-muted-foreground flex items-center gap-2"><User size={11} /> {u.kontak}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {tab === "galeri" && (
          <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
            {galeriList.map((g, i) => (
              <div key={i} className="relative rounded-2xl overflow-hidden bg-muted aspect-video group cursor-pointer">
                <img
                  src={unsplash(g.img, 500, 350)}
                  alt={g.alt}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                  <p className="text-white text-xs font-medium">{g.caption}</p>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

// ─── FOOTER ──────────────────────────────────────────────────────────────────

function Footer({ setActiveSection }: any) {
  return (
    <footer className="bg-[#0f3d22] text-green-100">
      <div className="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        {/* Brand */}
        <div>
          <div className="flex items-center gap-3 mb-4">
            <div className="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
              <Leaf size={18} className="text-white" />
            </div>
            <div>
              <p className="font-bold text-white text-sm">Desa Klego</p>
              <p className="text-xs text-green-300">Kabupaten Boyolali</p>
            </div>
          </div>
          <p className="text-xs text-green-300 leading-relaxed mb-4">
            Portal resmi Pemerintah Desa Klego, Kecamatan Klego, Kabupaten Boyolali, Provinsi Jawa Tengah.
          </p>
          <div className="flex items-center gap-3">
            <a href="#" className="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors"><Facebook size={14} /></a>
            <a href="#" className="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors"><Instagram size={14} /></a>
            <a href="#" className="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors"><Youtube size={14} /></a>
          </div>
        </div>

        {/* Quick links */}
        <div>
          <p className="text-white font-semibold text-sm mb-4">Tautan Cepat</p>
          <ul className="space-y-2">
            {[
              { label: "Beranda", id: "home" },
              { label: "Profil Desa", id: "profil" },
              { label: "Infografis", id: "infografis" },
              { label: "Produk Peraturan", id: "peraturan" },
              { label: "Layanan Publik", id: "pelayanan" },
            ].map((l) => (
              <li key={l.id}>
                <button
                  onClick={() => setActiveSection(l.id)}
                  className="text-xs text-green-300 hover:text-white transition-colors flex items-center gap-1.5"
                >
                  <ChevronRight size={10} /> {l.label}
                </button>
              </li>
            ))}
          </ul>
        </div>

        {/* Layanan */}
        <div>
          <p className="text-white font-semibold text-sm mb-4">Layanan</p>
          <ul className="space-y-2">
            {["Surat Keterangan Domisili", "Surat Pengantar KTP/KK", "Surat Keterangan Usaha", "Surat Keterangan Nikah", "Surat Pindah"].map((l) => (
              <li key={l}>
                <button className="text-xs text-green-300 hover:text-white transition-colors flex items-center gap-1.5">
                  <ChevronRight size={10} /> {l}
                </button>
              </li>
            ))}
          </ul>
        </div>

        {/* Contact */}
        <div>
          <p className="text-white font-semibold text-sm mb-4">Kontak</p>
          <div className="space-y-3">
            <div className="flex items-start gap-2.5">
              <MapPin size={13} className="text-green-400 flex-shrink-0 mt-0.5" />
              <p className="text-xs text-green-300 leading-relaxed">Jl. Raya Klego No. 01, Desa Klego, Kec. Klego, Kab. Boyolali 57385</p>
            </div>
            <div className="flex items-center gap-2.5">
              <Phone size={13} className="text-green-400" />
              <p className="text-xs text-green-300">(0276) 321-456</p>
            </div>
            <div className="flex items-center gap-2.5">
              <Mail size={13} className="text-green-400" />
              <p className="text-xs text-green-300">desklego@gmail.com</p>
            </div>
            <div className="flex items-start gap-2.5">
              <Clock size={13} className="text-green-400 flex-shrink-0 mt-0.5" />
              <p className="text-xs text-green-300 leading-relaxed">Senin–Kamis: 08.00–15.00<br />Jumat: 08.00–11.30</p>
            </div>
          </div>
        </div>
      </div>

      <div className="border-t border-white/10 py-4">
        <div className="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-green-400">
          <p>© 2025 Pemerintah Desa Klego. Hak Cipta Dilindungi.</p>
          <p>Dibuat dengan ❤ untuk pelayanan masyarakat Desa Klego</p>
        </div>
      </div>
    </footer>
  );
}

// ─── BACK TO TOP ─────────────────────────────────────────────────────────────

function BackToTop() {
  const [visible, setVisible] = useState(false);
  useEffect(() => {
    const h = () => setVisible(window.scrollY > 300);
    window.addEventListener("scroll", h);
    return () => window.removeEventListener("scroll", h);
  }, []);
  return visible ? (
    <button
      onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}
      className="fixed bottom-6 right-6 z-50 w-11 h-11 bg-primary text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-green-700 transition-all hover:-translate-y-0.5"
    >
      <ArrowUp size={18} />
    </button>
  ) : null;
}

// ─── ADMIN LOGIN ──────────────────────────────────────────────────────────────

function AdminLogin({ onLogin }: { onLogin: () => void }) {
  const [pw, setPw] = useState("");
  const [user, setUser] = useState("");
  const [err, setErr] = useState(false);

  const handle = (e: React.FormEvent) => {
    e.preventDefault();
    if (user === "admin" && pw === "admin123") {
      onLogin();
    } else {
      setErr(true);
    }
  };

  return (
    <div className="min-h-screen bg-primary flex items-center justify-center px-4 relative overflow-hidden">
      <div className="absolute inset-0 opacity-10" style={{ backgroundImage: "radial-gradient(circle at 20% 80%, #52b87a 0%, transparent 50%), radial-gradient(circle at 80% 20%, #c4891f 0%, transparent 40%)" }} />
      <div className="relative w-full max-w-sm">
        <div className="text-center mb-8">
          <div className="w-16 h-16 rounded-2xl bg-white flex items-center justify-center mx-auto mb-4 shadow-xl">
            <Leaf size={28} className="text-primary" />
          </div>
          <h1 className="text-2xl font-bold text-white" style={{ fontFamily: "var(--font-serif)" }}>Admin Panel</h1>
          <p className="text-green-200 text-sm mt-1">Desa Klego – CMS</p>
        </div>

        <form onSubmit={handle} className="bg-white rounded-3xl p-8 shadow-2xl">
          <div className="space-y-4">
            <div>
              <label className="text-xs font-semibold text-muted-foreground uppercase tracking-wide block mb-1.5">Username</label>
              <input
                type="text"
                value={user}
                onChange={(e) => { setUser(e.target.value); setErr(false); }}
                placeholder="admin"
                className="w-full bg-input-background rounded-xl px-4 py-3 text-sm text-foreground outline-none focus:ring-2 focus:ring-primary/30 transition-all"
              />
            </div>
            <div>
              <label className="text-xs font-semibold text-muted-foreground uppercase tracking-wide block mb-1.5">Password</label>
              <input
                type="password"
                value={pw}
                onChange={(e) => { setPw(e.target.value); setErr(false); }}
                placeholder="••••••••"
                className="w-full bg-input-background rounded-xl px-4 py-3 text-sm text-foreground outline-none focus:ring-2 focus:ring-primary/30 transition-all"
              />
            </div>
            {err && <p className="text-xs text-destructive bg-destructive/10 rounded-lg px-3 py-2">Username atau password salah. Coba: admin / admin123</p>}
            <button
              type="submit"
              className="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors shadow-md"
            >
              Masuk
            </button>
          </div>
          <p className="text-center text-xs text-muted-foreground mt-4">Demo: admin / admin123</p>
        </form>
      </div>
    </div>
  );
}

// ─── ADMIN DASHBOARD ─────────────────────────────────────────────────────────

function AdminDashboard({ onLogout }: { onLogout: () => void }) {
  const [activeMenu, setActiveMenu] = useState("dashboard");
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const dashStats = [
    { icon: Users, label: "Total Penduduk", value: "4.823", change: "+47", color: "bg-primary" },
    { icon: Eye, label: "Pengunjung Hari Ini", value: "312", change: "+28", color: "bg-blue-600" },
    { icon: Newspaper, label: "Total Berita", value: "47", change: "+3", color: "bg-amber-500" },
    { icon: FileText, label: "Dokumen", value: "23", change: "+1", color: "bg-teal-600" },
  ];

  const recentActivity = [
    { aksi: "Berita baru diterbitkan", isi: "Musyawarah Desa Klego Tetapkan APBDes 2025", waktu: "5 menit lalu", type: "berita" },
    { aksi: "Agenda diperbarui", isi: "Posyandu Balita & Lansia – 18 Juli 2025", waktu: "1 jam lalu", type: "agenda" },
    { aksi: "Pengumuman baru", isi: "Pembayaran PBB 2025 Mulai 1 Agustus", waktu: "3 jam lalu", type: "pengumuman" },
    { aksi: "Data penduduk diperbarui", isi: "Input data KK baru – Dusun Klego", waktu: "Kemarin", type: "data" },
    { aksi: "Dokumen diupload", isi: "APBDes Desa Klego 2025.pdf", waktu: "Kemarin", type: "dokumen" },
  ];

  const renderContent = () => {
    switch (activeMenu) {
      case "dashboard":
        return (
          <div className="space-y-6">
            <div>
              <h2 className="text-xl font-bold text-foreground mb-1" style={{ fontFamily: "var(--font-serif)" }}>Dashboard Utama</h2>
              <p className="text-sm text-muted-foreground">Selamat datang, Administrator. Berikut ringkasan data Desa Klego.</p>
            </div>

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
              {dashStats.map((s, i) => (
                <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm">
                  <div className="flex items-center justify-between mb-3">
                    <div className={`w-10 h-10 rounded-xl ${s.color} flex items-center justify-center`}>
                      <s.icon size={18} className="text-white" />
                    </div>
                    <span className="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-mono">{s.change}</span>
                  </div>
                  <p className="text-2xl font-bold text-foreground">{s.value}</p>
                  <p className="text-xs text-muted-foreground mt-0.5">{s.label}</p>
                </div>
              ))}
            </div>

            <div className="grid lg:grid-cols-2 gap-6">
              {/* Visitor trend */}
              <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
                <h3 className="font-semibold text-foreground text-sm mb-4">Pengunjung 7 Hari Terakhir</h3>
                <div className="h-48">
                  <ResponsiveContainer width="100%" height="100%">
                    <AreaChart data={[
                      { day: "Sen", v: 245 }, { day: "Sel", v: 312 }, { day: "Rab", v: 289 },
                      { day: "Kam", v: 367 }, { day: "Jum", v: 298 }, { day: "Sab", v: 178 }, { day: "Min", v: 156 },
                    ]} margin={{ top: 0, right: 0, left: -25, bottom: 0 }}>
                      <defs>
                        <linearGradient id="vGrad" x1="0" y1="0" x2="0" y2="1">
                          <stop offset="5%" stopColor="#165f36" stopOpacity={0.2} />
                          <stop offset="95%" stopColor="#165f36" stopOpacity={0} />
                        </linearGradient>
                      </defs>
                      <CartesianGrid strokeDasharray="3 3" stroke="#e4f2ea" />
                      <XAxis dataKey="day" tick={{ fontSize: 11 }} />
                      <YAxis tick={{ fontSize: 11 }} />
                      <Tooltip />
                      <Area type="monotone" dataKey="v" stroke="#165f36" strokeWidth={2} fill="url(#vGrad)" />
                    </AreaChart>
                  </ResponsiveContainer>
                </div>
              </div>

              {/* Recent activity */}
              <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
                <h3 className="font-semibold text-foreground text-sm mb-4">Aktivitas Terbaru</h3>
                <div className="space-y-3">
                  {recentActivity.map((a, i) => (
                    <div key={i} className="flex items-start gap-3 pb-3 border-b border-border last:border-0 last:pb-0">
                      <div className="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                        {a.type === "berita" && <Newspaper size={12} className="text-primary" />}
                        {a.type === "agenda" && <Calendar size={12} className="text-primary" />}
                        {a.type === "pengumuman" && <Megaphone size={12} className="text-primary" />}
                        {a.type === "data" && <Users size={12} className="text-primary" />}
                        {a.type === "dokumen" && <FileText size={12} className="text-primary" />}
                      </div>
                      <div className="flex-1 min-w-0">
                        <p className="text-xs font-semibold text-foreground">{a.aksi}</p>
                        <p className="text-xs text-muted-foreground truncate">{a.isi}</p>
                      </div>
                      <span className="text-xs text-muted-foreground flex-shrink-0">{a.waktu}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Content summary */}
            <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
              <div className="flex items-center justify-between mb-4">
                <h3 className="font-semibold text-foreground text-sm">Kelola Konten</h3>
                <button className="text-xs text-primary hover:underline">Lihat Semua</button>
              </div>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                {[
                  { icon: Newspaper, label: "Berita", count: 47, action: "berita-admin" },
                  { icon: Calendar, label: "Agenda", count: 12, action: "berita-admin" },
                  { icon: FileText, label: "Peraturan", count: 23, action: "peraturan-admin" },
                  { icon: Image, label: "Galeri", count: 86, action: "galeri-admin" },
                ].map((c, i) => (
                  <button
                    key={i}
                    onClick={() => setActiveMenu(c.action)}
                    className="p-4 rounded-xl border border-border bg-secondary/30 hover:bg-secondary hover:border-primary/30 transition-all text-left group"
                  >
                    <c.icon size={18} className="text-primary mb-2" />
                    <p className="text-lg font-bold text-foreground">{c.count}</p>
                    <p className="text-xs text-muted-foreground">{c.label}</p>
                  </button>
                ))}
              </div>
            </div>
          </div>
        );

      case "berita-admin":
        return (
          <div className="space-y-6">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>Kelola Berita & Agenda</h2>
                <p className="text-sm text-muted-foreground">Tambah, edit, dan hapus konten berita & agenda desa</p>
              </div>
              <button className="flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors shadow-md">
                <Plus size={16} /> Tambah Berita
              </button>
            </div>
            <div className="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
              <table className="w-full text-sm">
                <thead className="border-b border-border bg-secondary/30">
                  <tr>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Judul</th>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Kategori</th>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Tanggal</th>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Status</th>
                    <th className="py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {beritaList.map((b) => (
                    <tr key={b.id} className="border-b border-border last:border-0 hover:bg-secondary/20 transition-colors">
                      <td className="py-3 px-5">
                        <p className="font-medium text-foreground text-xs">{b.judul}</p>
                      </td>
                      <td className="py-3 px-5">
                        <span className="text-xs bg-secondary text-primary px-2.5 py-0.5 rounded-full">{b.kategori}</span>
                      </td>
                      <td className="py-3 px-5 text-xs text-muted-foreground">{b.tanggal}</td>
                      <td className="py-3 px-5">
                        <span className="text-xs bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full border border-green-200">Terbit</span>
                      </td>
                      <td className="py-3 px-5">
                        <div className="flex items-center gap-2 justify-center">
                          <button className="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors"><Edit size={13} /></button>
                          <button className="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors"><Trash2 size={13} /></button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        );

      case "peraturan-admin":
        return (
          <div className="space-y-6">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>Kelola Produk Peraturan</h2>
                <p className="text-sm text-muted-foreground">Upload dan kelola dokumen peraturan desa</p>
              </div>
              <button className="flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors shadow-md">
                <Plus size={16} /> Upload Dokumen
              </button>
            </div>
            <div className="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
              <table className="w-full text-sm">
                <thead className="border-b border-border bg-secondary/30">
                  <tr>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Nomor</th>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Judul Dokumen</th>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Tipe</th>
                    <th className="text-left py-3 px-5 text-xs font-semibold text-muted-foreground uppercase tracking-wide">Tanggal</th>
                    <th className="py-3 px-5">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {peraturanList.map((p, i) => (
                    <tr key={i} className="border-b border-border last:border-0 hover:bg-secondary/20 transition-colors">
                      <td className="py-3 px-5 text-xs font-mono text-muted-foreground">{p.no}</td>
                      <td className="py-3 px-5 text-xs font-medium text-foreground">{p.judul}</td>
                      <td className="py-3 px-5"><span className="text-xs bg-secondary text-primary px-2.5 py-0.5 rounded-full">{p.tipe}</span></td>
                      <td className="py-3 px-5 text-xs text-muted-foreground">{p.tgl}</td>
                      <td className="py-3 px-5">
                        <div className="flex items-center gap-2 justify-center">
                          <button className="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors"><Edit size={13} /></button>
                          <button className="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors"><Trash2 size={13} /></button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        );

      case "galeri-admin":
        return (
          <div className="space-y-6">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>Kelola Galeri Media</h2>
                <p className="text-sm text-muted-foreground">Upload dan kelola foto dan video kegiatan desa</p>
              </div>
              <button className="flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors shadow-md">
                <Plus size={16} /> Upload Foto
              </button>
            </div>
            <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
              {galeriList.map((g, i) => (
                <div key={i} className="relative rounded-2xl overflow-hidden bg-muted group">
                  <img src={unsplash(g.img, 400, 280)} alt={g.alt} className="w-full h-40 object-cover" />
                  <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <button className="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-foreground hover:bg-secondary transition-colors"><Edit size={14} /></button>
                    <button className="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white hover:bg-red-600 transition-colors"><Trash2 size={14} /></button>
                  </div>
                  <div className="p-3 bg-white border-t border-border">
                    <p className="text-xs font-medium text-foreground truncate">{g.caption}</p>
                  </div>
                </div>
              ))}
              {/* Upload placeholder */}
              <div className="rounded-2xl border-2 border-dashed border-primary/30 h-52 flex flex-col items-center justify-center gap-2 text-primary/50 hover:border-primary/50 hover:text-primary/70 cursor-pointer transition-colors bg-primary/5">
                <Plus size={24} />
                <p className="text-xs font-medium">Upload Foto Baru</p>
              </div>
            </div>
          </div>
        );

      case "kependudukan":
        return (
          <div className="space-y-6">
            <div>
              <h2 className="text-xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>Data Kependudukan</h2>
              <p className="text-sm text-muted-foreground">Kelola dan perbarui data statistik kependudukan desa</p>
            </div>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              {[
                { l: "Total Penduduk", v: "4.823", icon: Users },
                { l: "Kepala Keluarga", v: "1.456", icon: Home },
                { l: "Laki-laki", v: "2.411", icon: User },
                { l: "Perempuan", v: "2.412", icon: User },
              ].map(({ l, v, icon: Icon }, i) => (
                <div key={i} className="bg-white rounded-2xl p-5 border border-border shadow-sm">
                  <Icon size={18} className="text-primary mb-2" />
                  <p className="text-2xl font-bold text-foreground">{v}</p>
                  <p className="text-xs text-muted-foreground">{l}</p>
                  <button className="mt-2 text-xs text-primary hover:underline flex items-center gap-1"><Edit size={10} /> Edit</button>
                </div>
              ))}
            </div>
            <div className="bg-white rounded-2xl p-6 border border-border shadow-sm">
              <div className="flex items-center justify-between mb-4">
                <h3 className="font-semibold text-foreground text-sm">Data per Dusun</h3>
                <button className="text-xs bg-primary text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5"><Plus size={12} /> Tambah</button>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-xs">
                  <thead className="border-b border-border">
                    <tr>
                      {["Dusun", "Laki-laki", "Perempuan", "Total", "KK", "Aksi"].map(h => (
                        <th key={h} className="text-left py-2 px-3 font-semibold text-muted-foreground uppercase tracking-wide text-[10px]">{h}</th>
                      ))}
                    </tr>
                  </thead>
                  <tbody>
                    {[
                      { d: "Klego", l: 621, p: 622, kk: 378 },
                      { d: "Ponggok", l: 493, p: 494, kk: 301 },
                      { d: "Soka", l: 438, p: 438, kk: 267 },
                      { d: "Rejosari", l: 382, p: 383, kk: 233 },
                      { d: "Ngemplak", l: 477, p: 475, kk: 277 },
                    ].map((r, i) => (
                      <tr key={i} className="border-b border-border/50 hover:bg-secondary/20">
                        <td className="py-2 px-3 font-medium text-foreground">{r.d}</td>
                        <td className="py-2 px-3 text-muted-foreground">{r.l.toLocaleString("id-ID")}</td>
                        <td className="py-2 px-3 text-muted-foreground">{r.p.toLocaleString("id-ID")}</td>
                        <td className="py-2 px-3 font-semibold text-foreground">{(r.l + r.p).toLocaleString("id-ID")}</td>
                        <td className="py-2 px-3 text-muted-foreground">{r.kk}</td>
                        <td className="py-2 px-3">
                          <div className="flex gap-1">
                            <button className="w-6 h-6 bg-blue-50 text-blue-600 rounded flex items-center justify-center"><Edit size={11} /></button>
                            <button className="w-6 h-6 bg-red-50 text-red-500 rounded flex items-center justify-center"><Trash2 size={11} /></button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        );

      case "pengaturan":
        return (
          <div className="space-y-6 max-w-2xl">
            <div>
              <h2 className="text-xl font-bold text-foreground" style={{ fontFamily: "var(--font-serif)" }}>Pengaturan Website</h2>
              <p className="text-sm text-muted-foreground">Konfigurasi umum portal Desa Klego</p>
            </div>
            {[
              { label: "Nama Desa", val: "Desa Klego" },
              { label: "Kecamatan", val: "Klego" },
              { label: "Kabupaten", val: "Boyolali" },
              { label: "Provinsi", val: "Jawa Tengah" },
              { label: "Kode Pos", val: "57385" },
              { label: "No. Telepon", val: "(0276) 321-456" },
              { label: "Email Resmi", val: "desklego@gmail.com" },
              { label: "Slogan Desa", val: "Maju Bersama, Mandiri Sejahtera, Hijau Lestari" },
            ].map(({ label, val }) => (
              <div key={label} className="bg-white rounded-2xl p-5 border border-border shadow-sm flex items-center justify-between gap-4">
                <div>
                  <p className="text-xs text-muted-foreground uppercase tracking-wide font-semibold">{label}</p>
                  <p className="text-sm text-foreground font-medium mt-0.5">{val}</p>
                </div>
                <button className="w-8 h-8 bg-secondary rounded-xl flex items-center justify-center text-muted-foreground hover:text-primary transition-colors flex-shrink-0">
                  <Edit size={14} />
                </button>
              </div>
            ))}
            <button className="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors shadow-md text-sm">
              Simpan Perubahan
            </button>
          </div>
        );

      default:
        return (
          <div className="flex flex-col items-center justify-center h-64 text-center">
            <div className="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-4">
              <Settings size={28} className="text-primary" />
            </div>
            <h3 className="font-bold text-foreground text-lg mb-2" style={{ fontFamily: "var(--font-serif)" }}>
              {adminMenuItems.find((m) => m.id === activeMenu)?.label}
            </h3>
            <p className="text-sm text-muted-foreground max-w-xs">
              Fitur pengelolaan konten ini sedang dalam pengembangan. Segera hadir untuk memudahkan administrasi Desa Klego.
            </p>
          </div>
        );
    }
  };

  return (
    <div className="min-h-screen bg-background flex">
      {/* Sidebar */}
      <aside className={`${sidebarOpen ? "w-60" : "w-16"} flex-shrink-0 bg-sidebar transition-all duration-300 flex flex-col`} style={{ minHeight: "100vh" }}>
        {/* Sidebar header */}
        <div className={`flex items-center ${sidebarOpen ? "gap-3 px-5" : "justify-center px-0"} py-5 border-b border-sidebar-border`}>
          <div className="w-9 h-9 rounded-xl bg-sidebar-primary flex items-center justify-center flex-shrink-0">
            <Leaf size={18} className="text-white" />
          </div>
          {sidebarOpen && (
            <div className="min-w-0">
              <p className="text-xs font-bold text-sidebar-foreground truncate">Desa Klego</p>
              <p className="text-[10px] text-sidebar-accent-foreground opacity-60 truncate">Admin Panel</p>
            </div>
          )}
        </div>

        {/* Menu */}
        <nav className="flex-1 py-4 space-y-0.5 px-2 overflow-y-auto">
          {adminMenuItems.map((item) => (
            <button
              key={item.id}
              onClick={() => setActiveMenu(item.id)}
              className={`w-full flex items-center ${sidebarOpen ? "gap-3 px-3" : "justify-center px-0"} py-2.5 rounded-xl text-sm transition-all ${
                activeMenu === item.id
                  ? "bg-sidebar-primary text-white shadow-md"
                  : "text-sidebar-accent-foreground hover:bg-sidebar-accent"
              }`}
              title={!sidebarOpen ? item.label : undefined}
            >
              <item.icon size={17} className="flex-shrink-0" />
              {sidebarOpen && <span className="text-xs font-medium truncate">{item.label}</span>}
            </button>
          ))}
        </nav>

        {/* Logout */}
        <div className="p-3 border-t border-sidebar-border">
          <button
            onClick={onLogout}
            className={`w-full flex items-center ${sidebarOpen ? "gap-3 px-3" : "justify-center"} py-2.5 rounded-xl text-xs text-sidebar-accent-foreground hover:bg-sidebar-accent transition-colors`}
          >
            <LogOut size={16} className="flex-shrink-0" />
            {sidebarOpen && "Keluar"}
          </button>
        </div>
      </aside>

      {/* Main content */}
      <div className="flex-1 flex flex-col min-w-0">
        {/* Topbar */}
        <header className="h-14 bg-white border-b border-border flex items-center justify-between px-6 flex-shrink-0 shadow-sm">
          <div className="flex items-center gap-3">
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="p-2 rounded-lg hover:bg-secondary transition-colors text-muted-foreground"
            >
              <Menu size={18} />
            </button>
            <nav className="flex items-center gap-1 text-xs text-muted-foreground">
              <span>Admin</span>
              <ChevronRight size={12} />
              <span className="text-foreground font-medium">
                {adminMenuItems.find((m) => m.id === activeMenu)?.label}
              </span>
            </nav>
          </div>
          <div className="flex items-center gap-3">
            <button className="relative p-2 rounded-lg hover:bg-secondary transition-colors text-muted-foreground">
              <Bell size={17} />
              <span className="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full" />
            </button>
            <div className="flex items-center gap-2">
              <div className="w-7 h-7 rounded-lg bg-primary flex items-center justify-center">
                <User size={14} className="text-white" />
              </div>
              <span className="text-xs font-medium text-foreground hidden sm:block">Administrator</span>
            </div>
          </div>
        </header>

        {/* Content */}
        <main className="flex-1 p-6 overflow-y-auto">
          {renderContent()}
        </main>
      </div>
    </div>
  );
}

// ─── APP ─────────────────────────────────────────────────────────────────────

export default function App() {
  const [view, setView] = useState<"public" | "admin-login" | "admin">("public");
  const [activeSection, setActiveSection] = useState("home");

  const scrollTop = () => window.scrollTo({ top: 0, behavior: "smooth" });

  const handleSetSection = (id: string) => {
    setActiveSection(id);
    scrollTop();
  };

  if (view === "admin-login") {
    return <AdminLogin onLogin={() => setView("admin")} />;
  }

  if (view === "admin") {
    return <AdminDashboard onLogout={() => setView("public")} />;
  }

  return (
    <div className="min-h-screen bg-background" style={{ fontFamily: "var(--font-sans)" }}>
      <Navbar
        activeSection={activeSection}
        setActiveSection={handleSetSection}
        onAdmin={() => setView("admin-login")}
      />

      <main>
        {activeSection === "home" && <HomeSection setActiveSection={handleSetSection} />}
        {activeSection === "profil" && (
          <div className="pt-16">
            <ProfilSection />
          </div>
        )}
        {activeSection === "infografis" && (
          <div className="pt-16">
            <InfografiSection />
          </div>
        )}
        {activeSection === "peraturan" && (
          <div className="pt-16">
            <PeraturanSection />
          </div>
        )}
        {activeSection === "pelayanan" && (
          <div className="pt-16">
            <PelayananSection />
          </div>
        )}
      </main>

      <Footer setActiveSection={handleSetSection} />
      <BackToTop />
    </div>
  );
}
