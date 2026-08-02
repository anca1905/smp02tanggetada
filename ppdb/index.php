<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK Negeri 1 Skillance | Berprestasi & Kompeten</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'school-blue': '#003366',
                        'school-yellow': '#FFCC00',
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }

        nav a.active {
            color: white;
            background: #007bff;
            padding: 8px 12px;
            border-radius: 5px;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <div class="bg-school-blue text-white text-xs py-2 hidden md:block border-b border-gray-600">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex gap-4">
                <span><i class="fas fa-phone-alt mr-1"></i> (021) 789-1234</span>
                <span><i class="fas fa-envelope mr-1"></i> admin@smkskillance.sch.id</span>
                <span><i class="fas fa-clock mr-1"></i> Senin - Jumat: 07:00 - 16:00</span>
            </div>
            <div class="flex gap-3">
                <a href="#" class="hover:text-school-yellow"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-school-yellow"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-school-yellow"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- PHP Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="bg-school-yellow text-school-blue py-2 text-sm font-bold border-b border-gray-300">
        <div class="container mx-auto px-4 flex">
            <span class="bg-red-600 text-white px-2 mr-2 rounded text-xs flex items-center shrink-0">PENGUMUMAN:</span>
            <div class="marquee-container w-full overflow-hidden">
                <div class="marquee-content">
                    Selamat Datang di Website Resmi SMK Skillance | Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran
                    2025/2026 Telah Dibuka | Segera lengkapi berkas sebelum tanggal 30 Juni.
                </div>
            </div>
        </div>
    </div>

    <section class="relative min-h-[500px] flex items-center py-12 md:py-0">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('assets/img/hero.png');">
            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center md:text-left">
            <span id="hero-slogan" class="text-school-yellow font-bold tracking-wider mb-2 block text-sm">Cerdas, Terampil, Berkarakter</span>
            <h2 id="hero-title" class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
                PPDB Tahun 2026 <br> Telah Dibuka
            </h2>
            <p id="hero-desc" class="text-gray-200 mb-8 max-w-xl mx-auto md:mx-0 text-sm md:text-base">
                Mari bergabung bersama kami menjadi tenaga profesional yang siap kerja dan berwirausaha.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                <a href="ppdb.php"
                    class="bg-school-yellow text-school-blue px-6 py-3 rounded font-bold hover:bg-yellow-400 transition text-center">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
                <a href="#" id="btn-download-brosur"
                    class="bg-transparent border-2 border-white text-white px-6 py-3 rounded font-bold hover:bg-white hover:text-school-blue transition text-center">
                    <i class="fas fa-download mr-2"></i> Download Brosur
                </a>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-4 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 shadow-lg">
            <div class="bg-school-blue text-white p-8 border-b md:border-b-0 md:border-r border-blue-800">
                <i class="fas fa-graduation-cap text-3xl mb-3 text-school-yellow"></i>
                <h3 class="font-bold text-lg mb-2">Alumni Terserap Kerja</h3>
                <p class="text-sm text-gray-300">Kerjasama dengan 50+ DUDI (Dunia Usaha Dunia Industri).</p>
            </div>
            <div class="bg-school-blue text-white p-8 border-b md:border-b-0 md:border-r border-blue-800">
                <i class="fas fa-chalkboard-teacher text-3xl mb-3 text-school-yellow"></i>
                <h3 class="font-bold text-lg mb-2">Guru Bersertifikasi</h3>
                <p class="text-sm text-gray-300">Pengajar profesional dengan sertifikasi kompetensi di bidangnya.</p>
            </div>
            <div class="bg-school-blue text-white p-8">
                <i class="fas fa-mosque text-3xl mb-3 text-school-yellow"></i>
                <h3 class="font-bold text-lg mb-2">Unggul Imtaq & Iptek</h3>
                <p class="text-sm text-gray-300">Mengedepankan adab, akhlak mulia, dan teknologi terkini.</p>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="w-full md:w-1/3">
                    <div class="border-4 border-gray-200 p-1 bg-white shadow-lg max-w-sm mx-auto md:max-w-full">
                        <img src="https://ui-avatars.com/api/?name=Drs.+H.+Supriyadi&size=400&background=random"
                            alt="Kepala Sekolah" class="w-full">
                        <div class="bg-school-blue text-white text-center py-2">
                            <h4 class="font-bold text-sm">Drs. H. Supriyadi, M.Pd</h4>
                            <span class="text-xs">Kepala Sekolah</span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-2/3">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-school-yellow pl-4">Sambutan
                        Kepala Sekolah</h3>
                    <div class="prose text-gray-600 text-sm leading-relaxed text-justify">
                        <p class="mb-3">Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
                        <p class="mb-3">
                            Puji syukur kita panjatkan ke hadirat Allah SWT. Website sekolah ini hadir sebagai media
                            informasi dan komunikasi antara sekolah, orang tua, siswa, dan masyarakat luas. Di era
                            digitalisasi 4.0 ini, SMK Skillance terus berkomitmen meningkatkan mutu pelayanan
                            pendidikan.
                        </p>
                        <p class="mb-3">
                            Kami siap mencetak lulusan yang tidak hanya cerdas secara intelektual, tetapi juga memiliki
                            karakter profil pelajar Pancasila.
                        </p>
                        <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                    </div>
                    <a href="sambutan-kepala-sekolah.php" class="mt-4 inline-block text-school-blue font-bold text-sm hover:underline">Baca
                        Selengkapnya &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-school-blue uppercase">Kompetensi Keahlian</h2>
                <div class="h-1 w-20 bg-school-yellow mx-auto mt-2"></div>
            </div>

            <div id="majors-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Content will be loaded dynamically -->
                <p class="text-center col-span-full">Memuat Data Jurusan...</p>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-school-blue uppercase">Berita Sekolah</h2>
                    <div class="h-1 w-20 bg-school-yellow mt-2"></div>
                </div>
                <a href="berita.php" class="text-sm text-gray-600 hover:text-school-blue">Lihat Semua Berita &rarr;</a>
            </div>

            <div id="news-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Content will be loaded dynamically -->
                <p class="text-center col-span-full">Memuat Berita...</p>
            </div>
        </div>
    </section>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>
