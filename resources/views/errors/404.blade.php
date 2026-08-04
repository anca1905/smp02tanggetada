<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SIMS</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Background pattern halus agar tidak sepi */
            background-image: radial-gradient(rgba(30, 58, 138, 0.05) 2px, transparent 2px);
            background-size: 30px 30px;
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-6">

    <div class="max-w-xl w-full text-center relative">

        <h1
            class="text-[150px] md:text-[200px] font-black text-blue-900/5 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 select-none z-0">
            404
        </h1>

        <div
            class="relative z-10 bg-white/80 backdrop-blur-sm p-8 md:p-12 rounded-3xl shadow-xl border border-white/50">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6 shadow-inner">
                <i class="fas fa-map-signs text-4xl text-red-500"></i>
            </div>

            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                Oops! Halaman Nyasar.
            </h2>

            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                Maaf, halaman yang Anda cari tidak ditemukan. Mungkin tautannya rusak atau halaman telah dipindahkan.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button onclick="history.back()"
                    class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </button>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg flex items-center justify-center">
                        <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('home') }}"
                        class="px-6 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i> Ke Beranda
                    </a>
                @endauth
            </div>
        </div>

        <div class="mt-8 text-sm text-gray-500">
            &copy; {{ date('Y') }} Sistem Informasi Manajemen Sekolah
        </div>
    </div>

</body>

</html>
