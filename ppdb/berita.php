<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Artikel - SMK Negeri 1 Skillance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'school-blue': '#003366', 'school-yellow': '#FFCC00' },
                    fontFamily: { sans: ['Open Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- PHP Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="bg-school-blue text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-2">Kabar Sekolah</h1>
            <p class="text-sm text-gray-300">Informasi terbaru, prestasi siswa, dan kegiatan sekolah.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            <div class="lg:w-2/3 space-y-8" id="news-list">
                <!-- Loaded by JS -->
                 <p class="text-center">Memuat berita...</p>
            </div>

            <div class="lg:w-1/3 space-y-8">

                <div class="bg-white p-6 rounded shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Cari Berita</h4>
                    <div class="flex">
                        <input type="text"
                            class="w-full border px-3 py-2 rounded-l focus:outline-none focus:border-school-blue"
                            placeholder="Kata kunci...">
                        <button class="bg-school-blue text-white px-4 py-2 rounded-r hover:bg-blue-800"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Kategori</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="flex justify-between hover:text-school-blue"><span>Kegiatan
                                    Sekolah</span> <span
                                    class="bg-gray-100 px-2 rounded text-xs text-gray-500">12</span></a></li>
                        <li><a href="#" class="flex justify-between hover:text-school-blue"><span>Prestasi Siswa</span>
                                <span class="bg-gray-100 px-2 rounded text-xs text-gray-500">5</span></a></li>
                        <li><a href="#" class="flex justify-between hover:text-school-blue"><span>Pengumuman</span>
                                <span class="bg-gray-100 px-2 rounded text-xs text-gray-500">8</span></a></li>
                        <li><a href="#" class="flex justify-between hover:text-school-blue"><span>Artikel Guru</span>
                                <span class="bg-gray-100 px-2 rounded text-xs text-gray-500">3</span></a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
