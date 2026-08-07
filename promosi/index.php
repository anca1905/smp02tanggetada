<?php
$appName = 'SIMS Sekolah';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName ?> - Digitalisasi Sekolah Sesuai Standar Pendidikan Indonesia</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <div class="logo-icon"><i class='bx bxs-book-reader'></i></div>
                <span><?= $appName ?></span>
            </div>
            
            <button class="menu-toggle" aria-label="Toggle Menu">
                <i class='bx bx-menu'></i>
            </button>

            <ul class="nav-links">
                <li><a href="#fitur">Fitur Web</a></li>
                <li><a href="#mobile">Mobile App</a></li>
                <li><a href="#keunggulan">Sistem Sewa</a></li>
                <li><a href="#harga">Harga</a></li>
            </ul>
            <div class="nav-actions">
                <a href="../" class="btn btn-outline">Demo Web</a>
                <a href="#harga" class="btn btn-primary">Mulai Sekarang</a>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu">
        <button class="close-menu"><i class='bx bx-x'></i></button>
        <ul class="mobile-nav-links">
            <li><a href="#fitur">Fitur Web</a></li>
            <li><a href="#mobile">Mobile App</a></li>
            <li><a href="#keunggulan">Sistem Sewa</a></li>
            <li><a href="#harga">Harga</a></li>
            <li><a href="../" class="btn btn-outline">Coba Demo Web</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg-glow"></div>
        <div class="container hero-container">
            <div class="hero-content" data-aos="fade-up">
                <div class="badge-promo">
                    <span class="badge-icon">🚀</span> Platform SaaS Pendidikan
                </div>
                <h1>Digitalisasi Sekolah<br/><span class="text-gradient">Standar Indonesia</span></h1>
                <p>Tinggalkan sistem manual. Kelola absensi, nilai, dan penugasan dengan satu platform SaaS terpadu yang dirancang khusus untuk SD, SMP, dan SMA.</p>
                
                <div class="hero-buttons">
                    <a href="#fitur" class="btn btn-primary btn-lg">
                        Lihat Fitur <i class='bx bx-chevron-right'></i>
                    </a>
                    <a href="https://wa.me/628123456789" class="btn btn-secondary btn-lg" target="_blank">
                        Konsultasi Gratis
                    </a>
                </div>
                
                <div class="trust-badges">
                    <div class="avatars">
                        <img src="https://i.pravatar.cc/100?img=1" alt="Guru" class="avatar">
                        <img src="https://i.pravatar.cc/100?img=2" alt="Kepsek" class="avatar">
                        <img src="https://i.pravatar.cc/100?img=3" alt="Admin" class="avatar">
                        <div class="avatar-text">
                            <div class="stars"><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
                            <span>Dipercaya 50+ Sekolah</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual" data-aos="zoom-in" data-aos-delay="200">
                <div class="mockup-glass">
                    <img src="assets/dashboard_mockup.png" alt="Web Dashboard UI" class="mockup-img web-img">
                    
                    <div class="floating-card stat-card" data-aos="fade-left" data-aos-delay="400">
                        <div class="icon-box"><i class='bx bx-trending-up'></i></div>
                        <div>
                            <h4>Kinerja Naik</h4>
                            <p>+85% Efisiensi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan SaaS Section -->
    <section id="keunggulan" class="section-padding bg-alt">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>Kenapa Memilih Sistem Sewa (SaaS)?</h2>
                <p>Tidak perlu investasi jutaan rupiah di awal untuk beli server atau software.</p>
            </div>
            
            <div class="saas-grid">
                <div class="saas-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-wrapper"><i class='bx bx-server'></i></div>
                    <h3>Bebas Pusing Server</h3>
                    <p>Semua infrastruktur kami yang kelola. Sekolah tidak perlu menyewa IT Support atau membeli server fisik yang mahal.</p>
                </div>
                <div class="saas-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-wrapper"><i class='bx bx-refresh'></i></div>
                    <h3>Update Otomatis</h3>
                    <p>Fitur baru dan perbaikan keamanan selalu otomatis didapatkan tanpa perlu menginstal ulang aplikasi.</p>
                </div>
                <div class="saas-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-wrapper"><i class='bx bx-wallet'></i></div>
                    <h3>Cashflow Aman</h3>
                    <p>Dengan model berlangganan per bulan/tahun, pengeluaran sekolah lebih terkontrol dan terjangkau.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile App Section -->
    <section id="mobile" class="mobile-section section-padding">
        <div class="container mobile-container">
            <div class="mobile-visual" data-aos="fade-right">
                <div class="phone-mockup-glass">
                    <img src="assets/mobile_mockup.png" alt="Mobile App UI" class="mockup-img phone-img">
                    <div class="floating-badge" data-aos="fade-up" data-aos-delay="300">
                        <i class='bx bxs-check-circle'></i> Siap Rilis di PlayStore
                    </div>
                </div>
            </div>
            
            <div class="mobile-content" data-aos="fade-left">
                <div class="badge-promo bg-white-10">SIMS Mobile</div>
                <h2>Aplikasi Khusus Siswa & Orang Tua</h2>
                <p>Pantau perkembangan akademik anak langsung dari genggaman. Semua fitur penting telah dioptimalkan untuk pengalaman mobile yang cepat.</p>
                
                <ul class="feature-list">
                    <li>
                        <i class='bx bxs-book-content'></i>
                        <div>
                            <h4>Akses Materi & Tugas</h4>
                            <p>Download materi dan kumpulkan tugas PR (foto/dokumen) langsung dari HP.</p>
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-calendar-check'></i>
                        <div>
                            <h4>Jadwal & Absensi Realtime</h4>
                            <p>Orang tua bisa memantau kehadiran siswa setiap harinya.</p>
                        </div>
                    </li>
                    <li>
                        <i class='bx bxs-bell-ring'></i>
                        <div>
                            <h4>Notifikasi Instan</h4>
                            <p>Pengumuman sekolah langsung masuk ke notifikasi HP tanpa takut terlewat.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="section-padding">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>Fitur Web Dashboard Admin & Guru</h2>
                <p>Desain intuitif yang dirancang agar guru tidak gaptek saat menggunakannya.</p>
            </div>
            
            <div class="features-grid">
                <div class="feat-box" data-aos="zoom-in" data-aos-delay="100">
                    <i class='bx bx-fingerprint'></i>
                    <h3>Absensi Sekali Klik</h3>
                    <p>Guru tinggal tap dari web/HP di kelas. Data otomatis terekap ke tata usaha.</p>
                </div>
                <div class="feat-box" data-aos="zoom-in" data-aos-delay="200">
                    <i class='bx bx-task'></i>
                    <h3>Manajemen Tugas</h3>
                    <p>Buat tugas, tentukan tenggat waktu, dan beri nilai secara online.</p>
                </div>
                <div class="feat-box" data-aos="zoom-in" data-aos-delay="300">
                    <i class='bx bx-bar-chart-square'></i>
                    <h3>Rapor Otomatis</h3>
                    <p>Nilai tugas & ujian harian langsung diakumulasi menjadi nilai akhir rapor.</p>
                </div>
                <div class="feat-box" data-aos="zoom-in" data-aos-delay="400">
                    <i class='bx bx-user-pin'></i>
                    <h3>Data Master Terpadu</h3>
                    <p>Kelola data Guru, Siswa, dan Mata Pelajaran dalam satu dashboard terpusat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="pricing section-padding bg-alt">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>Paket Investasi Digital Sekolah</h2>
                <p>Harga transparan, tanpa biaya tersembunyi. Sesuai untuk semua ukuran sekolah.</p>
            </div>
            
            <div class="pricing-cards">
                <!-- Paket Dasar -->
                <div class="price-card" data-aos="fade-up" data-aos-delay="100">
                    <h3>Paket Perintis</h3>
                    <p>Cocok untuk sekolah kecil yang baru beralih digital.</p>
                    <div class="price-amount">
                        <span class="currency">Rp</span>
                        <span class="value">499</span>
                        <span class="period">rb/bln</span>
                    </div>
                    <ul class="price-features">
                        <li><i class='bx bx-check'></i> Maksimal 500 Siswa</li>
                        <li><i class='bx bx-check'></i> Web Dashboard (Admin & Guru)</li>
                        <li><i class='bx bx-check'></i> Fitur Absensi & Nilai</li>
                        <li class="disabled"><i class='bx bx-x'></i> Aplikasi Mobile Android</li>
                        <li><i class='bx bx-check'></i> Bantuan Email</li>
                    </ul>
                    <a href="#" class="btn btn-outline btn-full">Pilih Paket</a>
                </div>
                
                <!-- Paket Populer -->
                <div class="price-card popular" data-aos="fade-up" data-aos-delay="200">
                    <div class="ribbon">PALING DIMINATI</div>
                    <h3>Paket Unggulan</h3>
                    <p>Solusi lengkap dengan aplikasi HP untuk mobilitas KBM.</p>
                    <div class="price-amount">
                        <span class="currency">Rp</span>
                        <span class="value">999</span>
                        <span class="period">rb/bln</span>
                    </div>
                    <ul class="price-features">
                        <li><i class='bx bx-check'></i> Maksimal 1.500 Siswa</li>
                        <li><i class='bx bx-check'></i> Semua Fitur Web Admin</li>
                        <li><i class='bx bx-check'></i> <strong>Aplikasi Mobile Android</strong></li>
                        <li><i class='bx bx-check'></i> Notifikasi / Broadcast</li>
                        <li><i class='bx bx-check'></i> Prioritas Bantuan WhatsApp</li>
                    </ul>
                    <a href="#" class="btn btn-primary btn-full">Langganan Sekarang</a>
                </div>

                <!-- Paket Enterprise -->
                <div class="price-card" data-aos="fade-up" data-aos-delay="300">
                    <h3>Paket Yayasan</h3>
                    <p>Untuk sekolah besar / yayasan dengan banyak cabang.</p>
                    <div class="price-amount">
                        <span class="currency">Rp</span>
                        <span class="value">2.5</span>
                        <span class="period">jt/bln</span>
                    </div>
                    <ul class="price-features">
                        <li><i class='bx bx-check'></i> Siswa & Guru <strong>Unlimited</strong></li>
                        <li><i class='bx bx-check'></i> Domain Sekolah (sch.id)</li>
                        <li><i class='bx bx-check'></i> Re-branding Nama App</li>
                        <li><i class='bx bx-check'></i> Backup Data Ekstra</li>
                        <li><i class='bx bx-check'></i> Dedicated Account Manager</li>
                    </ul>
                    <a href="https://wa.me/628123456789" class="btn btn-outline btn-full">Hubungi Tim Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon"><i class='bx bxs-book-reader'></i></div>
                        <span><?= $appName ?></span>
                    </div>
                    <p>Mitra terpercaya digitalisasi sekolah di seluruh Indonesia. Mendidik lebih mudah dengan teknologi.</p>
                </div>
                <div class="footer-links">
                    <h4>Produk</h4>
                    <ul>
                        <li><a href="#fitur">Web Dashboard</a></li>
                        <li><a href="#mobile">Mobile App</a></li>
                        <li><a href="#harga">Harga Sewa</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Hubungi Kami</h4>
                    <p><i class='bx bx-envelope'></i> halo@simssekolah.id</p>
                    <p><i class='bx bxl-whatsapp'></i> +62 812-3456-7890</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $appName ?>. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi Animate On Scroll
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });

        // Navbar Scroll Effect
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Menu Toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const closeMenu = document.querySelector('.close-menu');
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-nav-links a');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.add('active');
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
        });

        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
            });
        });
    </script>
</body>
</html>
