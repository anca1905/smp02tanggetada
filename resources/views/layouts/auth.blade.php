<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Login') - Sistem Informasi Manajemen Sekolah</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('assets/css/final.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-poppins min-h-screen flex flex-col justify-between">
    <nav class="bg-white text-primary shadow-lg relative z-10">
        <div class="container mx-auto px-6 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('home') }}"
                    class="text-brand-gray hover:text-white focus:outline-none p-1 mr-3 rounded-md hover:bg-[#2746b5] transition-colors group"
                    title="Lihat Halaman Depan">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <div class="relative mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                    </svg>
                </div>
                <h1 class="text-lg font-bold gradient-text hidden sm:block">Sistem Informasi Manajemen Sekolah</h1>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('presensi.index') }}"
                    class="text-xl font-bold text-primary hover:text-primary-dark transition-colors flex gap-2 items-center">
                    <p>Presensi</p>
                    <svg class="w-7 h-7 hover:text-primary-dark" viewBox="0 0 120 120" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="20" width="80" height="80" rx="16" stroke="#2746b5"
                            stroke-width="12" />
                        <path d="M42 60L54 72L78 48" stroke="#10B981" stroke-width="12" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-12 flex-1 flex flex-col items-center justify-center relative z-10">
        @yield('content')
    </div>

    <footer class="border-t border-gray-300 mt-auto relative z-10">
        <div class="text-center py-3 text-white text-sm">
            &copy; {{ date('Y') }} SIMS - Presensi Digital
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
