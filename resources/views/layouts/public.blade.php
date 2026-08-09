<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', $site_settings['app_name'] ?? 'SIMS')</title>
    @vite('resources/css/app.css')
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

        /* Dropdown desktop */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 200px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            border: 1px solid #e5e7eb;
            padding: 6px 0;
            z-index: 100;
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            display: block;
            animation: fadeInDown 0.15s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            font-size: 0.85rem;
            color: #374151;
            transition: background 0.15s, color 0.15s;
        }

        .nav-dropdown-menu a:hover {
            background: #eff6ff;
            color: #1e3a8a;
        }

        /* Mobile accordion */
        .mobile-accordion-content {
            display: none;
        }

        .mobile-accordion-content.open {
            display: block;
        }

        .mobile-accordion-btn .chevron {
            transition: transform 0.2s;
        }

        .mobile-accordion-btn.open .chevron {
            transform: rotate(180deg);
        }

        /* Mobile menu overlay */
        #mobile-drawer {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 200;
            display: none;
        }

        #mobile-drawer.open {
            display: flex;
        }

        #drawer-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
        }

        #drawer-panel {
            position: relative;
            width: 280px;
            background: white;
            height: 100%;
            overflow-y: auto;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
            transform: translateX(-100%);
            transition: transform 0.28s ease;
        }

        #mobile-drawer.open #drawer-panel {
            transform: translateX(0);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    {{-- Top bar --}}
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

    {{-- Desktop Navbar --}}
    <nav class="nav-sticky border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ isset($site_settings['school_logo']) ? asset('storage/' . $site_settings['school_logo']) : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg' }}"
                        alt="Logo Sekolah" class="h-10 w-auto">
                    <div class="flex flex-col">
                        <span
                            class="text-blue-900 font-bold text-lg leading-tight tracking-wide">{{ $site_settings['app_name'] }}</span>
                        <span class="text-xs text-gray-500 font-medium">Sistem Informasi Manajemen Sekolah</span>
                    </div>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-1 text-sm font-medium text-gray-600">

                    {{-- HOME --}}
                    <a href="{{ route('home') }}"
                        class="px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                        HOME
                    </a>

                    {{-- PROFIL dropdown --}}
                    <div class="nav-dropdown">
                        <button class="flex items-center gap-1 px-3 py-2 rounded-lg {{ request()->is('profil*') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                            PROFIL <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('public.profil.sejarah') }}"><i class="fas fa-history w-4 text-blue-500"></i> Sejarah</a>
                            <a href="{{ route('public.profil.visimisi') }}"><i class="fas fa-bullseye w-4 text-blue-500"></i> Visi &amp; Misi</a>
                            <a href="{{ route('public.profil.struktur') }}"><i class="fas fa-sitemap w-4 text-blue-500"></i> Struktur Organisasi</a>
                            <a href="{{ route('public.profil.gtk') }}"><i class="fas fa-chalkboard-teacher w-4 text-blue-500"></i> GTK</a>
                            <a href="{{ route('public.profil.sarana') }}"><i class="fas fa-school w-4 text-blue-500"></i> Sarana &amp; Prasarana</a>
                        </div>
                    </div>

                    {{-- BERITA dropdown --}}
                    <div class="nav-dropdown">
                        <button class="flex items-center gap-1 px-3 py-2 rounded-lg {{ request()->is('berita*') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                            BERITA <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('public.berita.kegiatan') }}"><i class="fas fa-calendar-check w-4 text-green-500"></i> Kegiatan Sekolah</a>
                            <a href="{{ route('public.galeri') }}"><i class="fas fa-images w-4 text-green-500"></i> Galeri</a>
                            <a href="{{ route('public.info') }}"><i class="fas fa-bell w-4 text-green-500"></i> Info Penting</a>
                        </div>
                    </div>

                    {{-- E-LEARNING dropdown --}}
                    <div class="nav-dropdown">
                        <button class="flex items-center gap-1 px-3 py-2 rounded-lg {{ request()->is('elearning*') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                            E-LEARNING <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('public.webguru') }}"><i class="fas fa-chalkboard w-4 text-purple-500"></i> Web Guru</a>
                            <a href="{{ route('public.edokumen') }}"><i class="fas fa-folder-open w-4 text-purple-500"></i> E-Dokumen</a>
                        </div>
                    </div>

                    {{-- PERPUSTAKAAN --}}
                    <a href="{{ route('public.perpustakaan') }}"
                        class="px-3 py-2 rounded-lg {{ request()->routeIs('public.perpustakaan') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                        PERPUSTAKAAN
                    </a>

                    {{-- SPMB 2026 --}}
                    <a href="{{ route('public.spmb') }}"
                        class="px-3 py-2 rounded-lg {{ request()->routeIs('public.spmb') || request()->routeIs('public.ppdb*') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                        SPMB 2026
                    </a>

                    {{-- KONTAK --}}
                    <a href="{{ route('public.kontak') }}"
                        class="{{ request()->routeIs('public.kontak') ? 'text-blue-900 font-bold' : 'hover:text-blue-900' }} transition">Kontak</a>
                </div>

                {{-- Right: Login + Hamburger --}}
                <div class="flex items-center space-x-3">
                    @auth
                    <a href="{{ route('dashboard') }}"
                        class="bg-blue-900 text-white hover:bg-blue-800 font-medium px-4 py-2 rounded shadow transition flex items-center text-xs md:text-sm">
                        <i class="fas fa-tachometer-alt mr-2"></i> <span class="hidden md:inline">Dashboard</span>
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="bg-blue-900 text-white hover:bg-blue-800 font-medium px-4 py-2 rounded shadow transition flex items-center text-xs md:text-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i> <span class="hidden md:inline">Login Portal</span><span class="md:hidden">Login</span>
                    </a>
                    @endauth

                    {{-- Hamburger (mobile) --}}
                    <button id="hamburger-btn" type="button"
                        class="md:hidden text-gray-600 hover:text-blue-900 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Mobile Drawer --}}
    <div id="mobile-drawer">
        <div id="drawer-overlay"></div>
        <div id="drawer-panel">
            {{-- Drawer header --}}
            <div class="flex items-center justify-between p-4 bg-blue-700 text-white">
                <div class="flex items-center gap-3">
                    <img src="{{ isset($site_settings['school_logo']) ? asset('storage/' . $site_settings['school_logo']) : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg' }}"
                        alt="Logo" class="h-9 w-auto">
                    <span class="font-bold text-sm leading-tight">{{ $site_settings['app_name'] ?? 'SIMS' }}</span>
                </div>
                <button id="drawer-close" class="text-white/80 hover:text-white text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Drawer Menu Items --}}
            <nav class="p-3 space-y-1 text-sm font-medium text-gray-700">

                {{-- HOME --}}
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-100' }} transition">
                    <i class="fas fa-home w-4 text-blue-600"></i> HOME
                </a>

                {{-- PROFIL accordion --}}
                <div>
                    <button class="mobile-accordion-btn w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 transition {{ request()->is('profil*') ? 'bg-blue-50 text-blue-900 font-bold' : '' }}"
                        data-target="acc-profil">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-building w-4 text-blue-600"></i> PROFIL
                        </span>
                        <i class="fas fa-chevron-down chevron text-xs text-gray-400"></i>
                    </button>
                    <div id="acc-profil" class="mobile-accordion-content pl-4 mt-1 space-y-1">
                        <a href="{{ route('public.profil.sejarah') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-history text-xs text-blue-400"></i> Sejarah
                        </a>
                        <a href="{{ route('public.profil.visimisi') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-bullseye text-xs text-blue-400"></i> Visi &amp; Misi
                        </a>
                        <a href="{{ route('public.profil.struktur') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-sitemap text-xs text-blue-400"></i> Struktur Organisasi
                        </a>
                        <a href="{{ route('public.profil.gtk') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-chalkboard-teacher text-xs text-blue-400"></i> GTK
                        </a>
                        <a href="{{ route('public.profil.sarana') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-school text-xs text-blue-400"></i> Sarana &amp; Prasarana
                        </a>
                    </div>
                </div>

                {{-- BERITA accordion --}}
                <div>
                    <button class="mobile-accordion-btn w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 transition {{ request()->is('berita*') ? 'bg-blue-50 text-blue-900 font-bold' : '' }}"
                        data-target="acc-berita">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-newspaper w-4 text-green-600"></i> BERITA
                        </span>
                        <i class="fas fa-chevron-down chevron text-xs text-gray-400"></i>
                    </button>
                    <div id="acc-berita" class="mobile-accordion-content pl-4 mt-1 space-y-1">
                        <a href="{{ route('public.berita.kegiatan') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-calendar-check text-xs text-green-400"></i> Kegiatan Sekolah
                        </a>
                        <a href="{{ route('public.galeri') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-images text-xs text-green-400"></i> Galeri
                        </a>
                        <a href="{{ route('public.info') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-bell text-xs text-green-400"></i> Info Penting
                        </a>
                    </div>
                </div>

                {{-- E-LEARNING accordion --}}
                <div>
                    <button class="mobile-accordion-btn w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 transition {{ request()->is('elearning*') ? 'bg-blue-50 text-blue-900 font-bold' : '' }}"
                        data-target="acc-elearning">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-laptop w-4 text-purple-600"></i> E-LEARNING
                        </span>
                        <i class="fas fa-chevron-down chevron text-xs text-gray-400"></i>
                    </button>
                    <div id="acc-elearning" class="mobile-accordion-content pl-4 mt-1 space-y-1">
                        <a href="{{ route('public.webguru') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-chalkboard text-xs text-purple-400"></i> Web Guru
                        </a>
                        <a href="{{ route('public.edokumen') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-folder-open text-xs text-purple-400"></i> E-Dokumen
                        </a>
                    </div>
                </div>

                {{-- PERPUSTAKAAN --}}
                <a href="{{ route('public.perpustakaan') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('public.perpustakaan') ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-100' }} transition">
                    <i class="fas fa-book-open w-4 text-amber-600"></i> PERPUSTAKAAN
                </a>

                {{-- SPMB 2026 --}}
                <a href="{{ route('public.spmb') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('public.spmb') || request()->routeIs('public.ppdb*') ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-100' }} transition">
                    <i class="fas fa-graduation-cap w-4 text-red-600"></i> SPMB 2026
                </a>

                {{-- KONTAK --}}
                <a href="{{ route('public.kontak') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('public.kontak') ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-100' }} transition">
                    <i class="fas fa-envelope w-4 text-blue-600"></i> KONTAK
                </a>

                {{-- Divider & Login --}}
                <div class="border-t border-gray-200 pt-3 mt-3">
                    @auth
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-900 text-white font-semibold hover:bg-blue-800 transition">
                        <i class="fas fa-tachometer-alt w-4"></i> Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-900 text-white font-semibold hover:bg-blue-800 transition">
                        <i class="fas fa-sign-in-alt w-4"></i> Login Portal
                    </a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>

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
                        <li><a href="{{ route('public.perpustakaan') }}" class="hover:text-white transition">Perpustakaan</a></li>
                        <li><a href="{{ route('public.spmb') }}" class="hover:text-white transition">SPMB 2026</a></li>
                        <li><a href="{{ route('public.edokumen') }}" class="hover:text-white transition">E-Dokumen</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Portal Guru/Siswa</a></li>
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
                            <span>{{ $site_settings['school_phone'] ?? '(0741) 123-4567' }}</span>
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

            // ── Mobile Drawer ─────────────────────────────────────────────────
            const hamburger = document.getElementById('hamburger-btn');
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('drawer-overlay');
            const closeBtn = document.getElementById('drawer-close');

            function openDrawer() {
                drawer.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                drawer.classList.remove('open');
                document.body.style.overflow = '';
            }

            hamburger.addEventListener('click', openDrawer);
            overlay.addEventListener('click', closeDrawer);
            closeBtn.addEventListener('click', closeDrawer);

            // ── Mobile Accordion ──────────────────────────────────────────────
            document.querySelectorAll('.mobile-accordion-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const targetId = btn.getAttribute('data-target');
                    const content = document.getElementById(targetId);

                    // Toggle current
                    const isOpen = content.classList.contains('open');
                    // Close all
                    document.querySelectorAll('.mobile-accordion-content').forEach(function(c) {
                        c.classList.remove('open');
                    });
                    document.querySelectorAll('.mobile-accordion-btn').forEach(function(b) {
                        b.classList.remove('open');
                    });
                    // Open if was closed
                    if (!isOpen) {
                        content.classList.add('open');
                        btn.classList.add('open');
                    }
                });
            });

            // Auto-open accordion if current page matches
            @if(request()->is('profil*'))
            var el = document.getElementById('acc-profil');
            if (el) {
                el.classList.add('open');
                document.querySelector('[data-target="acc-profil"]').classList.add('open');
            }
            @elseif(request()->is('berita*') || request()->is('berita/galeri') || request()->is('berita/info-penting'))
            var el = document.getElementById('acc-berita');
            if (el) {
                el.classList.add('open');
                document.querySelector('[data-target="acc-berita"]').classList.add('open');
            }
            @elseif(request()->is('elearning*'))
            var el = document.getElementById('acc-elearning');
            if (el) {
                el.classList.add('open');
                document.querySelector('[data-target="acc-elearning"]').classList.add('open');
            }
            @endif
        });
    </script>

</body>

</html>