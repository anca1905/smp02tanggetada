<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan - SMK Negeri 1 Skillance</title>
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

    <div class="bg-school-blue text-white py-8 text-center">
        <h1 class="text-3xl font-bold mb-2">Galeri Kegiatan</h1>
        <p class="text-sm text-gray-300">Dokumentasi aktivitas siswa dan prestasi sekolah</p>
    </div>

    <div class="container mx-auto px-4 py-12">
        <!-- Filter (Static for UI) -->
        <div class="flex flex-wrap justify-center gap-4 mb-10">
            <button class="px-4 py-2 bg-school-blue text-white rounded-full text-sm font-bold shadow">Semua</button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition shadow-sm">Kegiatan Sekolah</button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition shadow-sm">Praktik Siswa</button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition shadow-sm">Prestasi</button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition shadow-sm">Ekstrakurikuler</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Item 1 -->
            <div class="group relative overflow-hidden rounded-lg shadow-md cursor-pointer">
                <img src="https://images.unsplash.com/photo-1577896335477-2858506f97cc?q=80&w=1000&auto=format&fit=crop" alt="Kegiatan 1" class="w-full h-64 object-cover transform transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition duration-300 flex items-center justify-center">
                    <div class="text-white text-center opacity-0 group-hover:opacity-100 transition duration-300 px-4">
                        <h4 class="font-bold text-lg">Upacara Bendera</h4>
                        <p class="text-xs mt-1">12 Jan 2026</p>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="group relative overflow-hidden rounded-lg shadow-md cursor-pointer">
                <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?q=80&w=1000&auto=format&fit=crop" alt="Kegiatan 2" class="w-full h-64 object-cover transform transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition duration-300 flex items-center justify-center">
                    <div class="text-white text-center opacity-0 group-hover:opacity-100 transition duration-300 px-4">
                        <h4 class="font-bold text-lg">Kunjungan Industri TKJ</h4>
                        <p class="text-xs mt-1">10 Jan 2026</p>
                    </div>
                </div>
            </div>

             <!-- Item 3 -->
             <div class="group relative overflow-hidden rounded-lg shadow-md cursor-pointer">
                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1000&auto=format&fit=crop" alt="Kegiatan 3" class="w-full h-64 object-cover transform transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition duration-300 flex items-center justify-center">
                    <div class="text-white text-center opacity-0 group-hover:opacity-100 transition duration-300 px-4">
                        <h4 class="font-bold text-lg">Praktik Lab RPL</h4>
                        <p class="text-xs mt-1">08 Jan 2026</p>
                    </div>
                </div>
            </div>

             <!-- Item 4 -->
             <div class="group relative overflow-hidden rounded-lg shadow-md cursor-pointer">
                <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=1000&auto=format&fit=crop" alt="Kegiatan 4" class="w-full h-64 object-cover transform transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition duration-300 flex items-center justify-center">
                    <div class="text-white text-center opacity-0 group-hover:opacity-100 transition duration-300 px-4">
                        <h4 class="font-bold text-lg">Juara 1 LKS Provinsi</h4>
                        <p class="text-xs mt-1">05 Jan 2026</p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex justify-center gap-2">
            <a href="#" class="w-10 h-10 flex items-center justify-center rounded border hover:bg-gray-100 text-gray-600"><i class="fas fa-chevron-left"></i></a>
            <a href="#" class="w-10 h-10 flex items-center justify-center rounded bg-school-blue text-white font-bold">1</a>
            <a href="#" class="w-10 h-10 flex items-center justify-center rounded border hover:bg-gray-100 text-gray-600 font-bold">2</a>
            <a href="#" class="w-10 h-10 flex items-center justify-center rounded border hover:bg-gray-100 text-gray-600 font-bold">3</a>
            <a href="#" class="w-10 h-10 flex items-center justify-center rounded border hover:bg-gray-100 text-gray-600"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
