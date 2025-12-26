<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', $site_settings['app_name'] ?? 'SIMS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .nav-sticky {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            background-image: linear-gradient(rgba(30, 58, 138, 0.9), rgba(30, 58, 138, 0.8)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .hero-section {
            background-image: linear-gradient(rgba(30, 58, 138, 0.85), rgba(30, 58, 138, 0.7)),
                url("{{ isset($site_settings['hero_bg']) ? asset('storage/' . $site_settings['hero_bg']) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop' }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <div class="bg-blue-900 text-white py-2 text-sm hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex space-x-6">
                <span><i class="fas fa-phone-alt mr-2 text-yellow-400"></i>
                    {{ $site_settings['school_phone'] ?? '(0741) 123456' }}</span>
                <span><i class="fas fa-envelope mr-2 text-yellow-400"></i>
                    {{ $site_settings['school_email'] ?? 'admin@sekolah.sch.id' }}</span>
            </div>
            <div class="flex space-x-4">
                <span><i class="fas fa-calendar-alt mr-2"></i> {{ date('d F Y') }}</span>
            </div>
        </div>
    </div>

    <nav class="nav-sticky border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="https://via.placeholder.com/50x50/1e3a8a/ffffff?text=LOGO" alt="Logo"
                        class="rounded-full h-10 w-10">
                    <div class="flex flex-col">
                        <span class="text-blue-900 font-bold text-lg leading-tight tracking-wide">SIMS TERPADU</span>
                        <span class="text-xs text-gray-500 font-medium">Sistem Informasi Manajemen Sekolah</span>
                    </div>
                </a>

                <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-600">
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-blue-900 font-bold' : 'hover:text-blue-900' }} transition">Beranda</a>
                    <a href="{{ route('public.profil') }}"
                        class="{{ request()->routeIs('public.profil') ? 'text-blue-900 font-bold' : 'hover:text-blue-900' }} transition">Profil</a>
                    <a href="{{ route('public.berita') }}"
                        class="{{ request()->routeIs('public.berita') ? 'text-blue-900 font-bold' : 'hover:text-blue-900' }} transition">Berita</a>
                    <a href="{{ route('public.kontak') }}"
                        class="{{ request()->routeIs('public.kontak') ? 'text-blue-900 font-bold' : 'hover:text-blue-900' }} transition">Kontak</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-blue-900 text-white hover:bg-blue-800 font-medium px-4 py-2 rounded shadow transition flex items-center text-xs md:text-sm">
                            <i class="fas fa-tachometer-alt mr-2"></i> <span class="hidden md:inline">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-blue-900 text-white hover:bg-blue-800 font-medium px-4 py-2 rounded shadow transition flex items-center text-xs md:text-sm">
                            <i class="fas fa-sign-in-alt mr-2"></i> <span class="hidden md:inline">Login Portal</span> <span
                                class="md:hidden">Login</span>
                        </a>
                    @endauth

                    <button id="mobile-menu-button" type="button"
                        class="md:hidden text-gray-600 hover:text-blue-900 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-blue-900 bg-blue-50' : 'text-gray-700 hover:text-blue-900 hover:bg-gray-50' }}">Beranda</a>
                <a href="{{ route('public.profil') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.profil') ? 'text-blue-900 bg-blue-50' : 'text-gray-700 hover:text-blue-900 hover:bg-gray-50' }}">Profil</a>
                <a href="{{ route('public.berita') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.berita') ? 'text-blue-900 bg-blue-50' : 'text-gray-700 hover:text-blue-900 hover:bg-gray-50' }}">Berita</a>
                <a href="{{ route('public.kontak') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.kontak') ? 'text-blue-900 bg-blue-50' : 'text-gray-700 hover:text-blue-900 hover:bg-gray-50' }}">Kontak</a>

                <div class="border-t border-gray-100 my-2 pt-2">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tautan Cepat</p>
                    <a href="{{ route('public.jadwal') }}"
                        class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-900">Jadwal Pelajaran</a>
                    <a href="{{ route('public.kalender') }}"
                        class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-900">Kalender Akademik</a>
                </div>
            </div>
        </div>
    </nav>

    @hasSection('header')
        <div class="page-header text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">@yield('header')</h1>
                <p class="text-blue-200 text-sm md:text-base">@yield('subheader')</p>
            </div>
        </div>
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-white font-bold text-xl">SIMS TERPADU</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-4 text-gray-400 max-w-md">
                        Sistem Informasi Manajemen Sekolah Terpadu. Mendukung transparansi dan efisiensi administrasi
                        pendidikan.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('public.jadwal') }}" class="hover:text-white transition">Jadwal
                                Pelajaran</a></li>
                        <li><a href="{{ route('public.kalender') }}" class="hover:text-white transition">Kalender
                                Akademik</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Portal Siswa</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Portal Guru</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Kontak Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-500"></i>
                            <span>{!! nl2br(e($site_settings['school_address'] ?? 'Jl. Jendral Sudirman No. 123<br>Jambi, Indonesia 36123')) !!}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mt-1 mr-3 text-blue-500"></i>
                            <span>(0741) 123-4567</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Sistem Informasi Manajemen Sekolah. Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');

            btn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        });
    </script>

</body>

</html>
