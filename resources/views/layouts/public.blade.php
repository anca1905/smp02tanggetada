<!DOCTYPE html>

<html lang="id">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', $site_settings['app_name'] ?? 'SIMS')</title>
    @include('partials.favicon')

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



    {{-- Desktop Navbar (Clean, High-Contrast & Anti-Overcrowding) --}}
    <nav class="nav-sticky border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                {{-- Logo Sekolah --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-3 shrink-0 mr-4 group">
                    <img src="{{ isset($site_settings['school_logo']) ? asset('storage/' . $site_settings['school_logo']) : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg' }}"
                        alt="Logo Sekolah" class="h-10 w-auto shrink-0 group-hover:scale-105 transition-transform">
                    <div class="flex flex-col whitespace-nowrap">
                        <span class="text-blue-900 font-bold text-base lg:text-lg leading-tight tracking-wide whitespace-nowrap">
                            {{ $site_settings['app_name'] ?? 'SIMS' }}
                        </span>
                        <span class="text-[11px] text-gray-500 font-medium whitespace-nowrap">
                            Sistem Informasi Manajemen Sekolah
                        </span>
                    </div>
                </a>

                {{-- Desktop Menu (Tampil pada lg ke atas) --}}
                <div class="hidden lg:flex items-center space-x-1 text-sm font-semibold text-gray-700">

                    {{-- 1. HOME --}}
                    <a href="{{ route('home') }}"
                        class="px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                        HOME
                    </a>

                    {{-- 2. PROFIL dropdown --}}
                    <div class="nav-dropdown">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg {{ request()->is('profil*') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                            PROFIL <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('public.profil.sejarah') }}"><i class="fas fa-history w-4 text-blue-500"></i> Sejarah Sekolah</a>
                            <a href="{{ route('public.profil.visimisi') }}"><i class="fas fa-bullseye w-4 text-blue-500"></i> Visi &amp; Misi</a>
                            <a href="{{ route('public.profil.struktur') }}"><i class="fas fa-sitemap w-4 text-blue-500"></i> Struktur Organisasi</a>
                            <a href="{{ route('public.profil.gtk') }}"><i class="fas fa-chalkboard-teacher w-4 text-blue-500"></i> Guru &amp; Tenaga Kependidikan</a>
                        </div>
                    </div>

                    {{-- 3. AKADEMIK & KESISWAAN dropdown --}}
                    <div class="nav-dropdown">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg {{ (request()->is('kelulusan*') || request()->is('prestasi*') || request()->is('perpustakaan*') || request()->is('ekstrakurikuler*') || request()->is('elearning*')) ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                            AKADEMIK <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div class="nav-dropdown-menu" style="min-width: 230px;">
                            <a href="{{ route('public.kelulusan') }}"><i class="fas fa-graduation-cap w-4 text-blue-600"></i> Kelulusan &amp; SKL Online</a>
                            <a href="{{ route('public.prestasi') }}"><i class="fas fa-trophy w-4 text-amber-500"></i> Prestasi Siswa</a>
                            <a href="{{ route('public.perpustakaan') }}"><i class="fas fa-book-reader w-4 text-emerald-600"></i> Perpustakaan Digital</a>
                            <a href="{{ route('public.ekstrakurikuler') }}"><i class="fas fa-campground w-4 text-indigo-600"></i> Ekstrakurikuler &amp; OSIS</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="{{ route('public.edokumen') }}"><i class="fas fa-folder-open w-4 text-purple-500"></i> E-Dokumen Akademik</a>
                            <a href="{{ route('public.webguru') }}"><i class="fas fa-chalkboard w-4 text-purple-500"></i> Web Guru Mandiri</a>
                        </div>
                    </div>

                    {{-- 4. INFORMASI & BERITA dropdown --}}
                    <div class="nav-dropdown">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg {{ (request()->is('berita*') || request()->is('buku-tamu*')) ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                            INFORMASI <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div class="nav-dropdown-menu" style="min-width: 210px;">
                            <a href="{{ route('public.berita.kegiatan') }}"><i class="fas fa-calendar-check w-4 text-green-500"></i> Kegiatan Sekolah</a>
                            <a href="{{ route('public.galeri') }}"><i class="fas fa-images w-4 text-green-500"></i> Galeri Foto</a>
                            <a href="{{ route('public.info') }}"><i class="fas fa-bell w-4 text-amber-500"></i> Pengumuman &amp; Info</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="{{ route('public.buku-tamu') }}"><i class="fas fa-address-book w-4 text-teal-600"></i> Buku Tamu Digital</a>
                        </div>
                    </div>

                    {{-- 5. KONTAK --}}
                    <a href="{{ route('public.kontak') }}"
                        class="px-3 py-2 rounded-lg {{ request()->routeIs('public.kontak') ? 'text-blue-900 font-bold bg-blue-50' : 'hover:text-blue-900 hover:bg-gray-50' }} transition">
                        KONTAK
                    </a>

                    {{-- 6. SPMB / PPDB BADGE BUTTON --}}
                    <a href="{{ route('public.spmb') }}"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 shadow-sm ml-1 {{ request()->routeIs('public.spmb') || request()->routeIs('public.ppdb*') ? 'bg-amber-400 text-amber-950 font-extrabold ring-2 ring-amber-300' : 'bg-amber-100 text-amber-800 hover:bg-amber-200' }}">
                        <i class="fas fa-user-plus text-[10px]"></i> SPMB 2026
                    </a>

                </div>

                @php
                    $activeUser = Auth::guard('operator')->user()
                        ?? Auth::guard('teacher')->user()
                        ?? Auth::guard('student')->user();

                    $activeDashboardRoute = null;
                    if (Auth::guard('operator')->check()) {
                        $activeDashboardRoute = in_array(Auth::guard('operator')->user()->role_operator, ['Kepala Sekolah', 'principal'])
                            ? route('principal.dashboard')
                            : route('tu.dashboard');
                    } elseif (Auth::guard('teacher')->check()) {
                        $activeDashboardRoute = route('teacher.dashboard');
                    } elseif (Auth::guard('student')->check()) {
                        $activeDashboardRoute = route('student.dashboard');
                    }
                @endphp

                {{-- Right: Login / Dashboard + Logout + Hamburger --}}
                <div class="flex items-center space-x-2 shrink-0">
                    @if ($activeUser && $activeDashboardRoute)
                        <a href="{{ $activeDashboardRoute }}"
                            class="bg-blue-900 hover:bg-blue-800 text-white font-medium px-3.5 py-2 rounded-lg shadow-sm transition flex items-center text-xs md:text-sm whitespace-nowrap">
                            <i class="fas fa-tachometer-alt mr-2"></i> <span>Dashboard</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium px-3 py-2 rounded-lg shadow-sm transition flex items-center text-xs md:text-sm whitespace-nowrap"
                                title="Keluar">
                                <i class="fas fa-sign-out-alt md:mr-1"></i> <span class="hidden md:inline">Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-blue-900 hover:bg-blue-800 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition flex items-center text-xs md:text-sm whitespace-nowrap">
                            <i class="fas fa-sign-in-alt mr-2"></i> <span>Login Portal</span>
                        </a>
                    @endif

                    {{-- Hamburger (Tampil pada layar di bawah lg) --}}
                    <button id="hamburger-btn" type="button"
                        class="lg:hidden text-gray-700 hover:text-blue-900 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 ml-1">
                        <i class="fas fa-bars text-xl"></i>
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



            {{-- Drawer Menu Items (Organized & Clean) --}}
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
                            <i class="fas fa-history text-xs text-blue-400"></i> Sejarah Sekolah
                        </a>
                        <a href="{{ route('public.profil.visimisi') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-bullseye text-xs text-blue-400"></i> Visi &amp; Misi
                        </a>
                        <a href="{{ route('public.profil.struktur') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-sitemap text-xs text-blue-400"></i> Struktur Organisasi
                        </a>
                        <a href="{{ route('public.profil.gtk') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-chalkboard-teacher text-xs text-blue-400"></i> Guru &amp; GTK
                        </a>
                    </div>
                </div>

                {{-- AKADEMIK & KESISWAAN accordion --}}
                <div>
                    <button class="mobile-accordion-btn w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 transition {{ (request()->is('kelulusan*') || request()->is('prestasi*') || request()->is('perpustakaan*') || request()->is('ekstrakurikuler*') || request()->is('elearning*')) ? 'bg-blue-50 text-blue-900 font-bold' : '' }}"
                        data-target="acc-akademik">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-graduation-cap w-4 text-blue-600"></i> AKADEMIK &amp; SISWA
                        </span>
                        <i class="fas fa-chevron-down chevron text-xs text-gray-400"></i>
                    </button>
                    <div id="acc-akademik" class="mobile-accordion-content pl-4 mt-1 space-y-1">
                        <a href="{{ route('public.kelulusan') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-graduation-cap text-xs text-blue-500"></i> Kelulusan &amp; SKL
                        </a>
                        <a href="{{ route('public.prestasi') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-trophy text-xs text-amber-500"></i> Prestasi Siswa
                        </a>
                        <a href="{{ route('public.perpustakaan') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-book-reader text-xs text-emerald-500"></i> Perpustakaan Digital
                        </a>
                        <a href="{{ route('public.ekstrakurikuler') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-campground text-xs text-indigo-500"></i> Ekstrakurikuler &amp; OSIS
                        </a>
                        <a href="{{ route('public.edokumen') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-folder-open text-xs text-purple-500"></i> E-Dokumen
                        </a>
                        <a href="{{ route('public.webguru') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-chalkboard text-xs text-purple-500"></i> Web Guru
                        </a>
                    </div>
                </div>

                {{-- BERITA & INFORMASI accordion --}}
                <div>
                    <button class="mobile-accordion-btn w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 transition {{ (request()->is('berita*') || request()->is('buku-tamu*')) ? 'bg-blue-50 text-blue-900 font-bold' : '' }}"
                        data-target="acc-berita">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-newspaper w-4 text-green-600"></i> INFORMASI &amp; BERITA
                        </span>
                        <i class="fas fa-chevron-down chevron text-xs text-gray-400"></i>
                    </button>
                    <div id="acc-berita" class="mobile-accordion-content pl-4 mt-1 space-y-1">
                        <a href="{{ route('public.berita.kegiatan') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-calendar-check text-xs text-green-500"></i> Kegiatan Sekolah
                        </a>
                        <a href="{{ route('public.galeri') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-images text-xs text-green-500"></i> Galeri Foto
                        </a>
                        <a href="{{ route('public.info') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-bell text-xs text-amber-500"></i> Pengumuman &amp; Info
                        </a>
                        <a href="{{ route('public.buku-tamu') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-900 transition">
                            <i class="fas fa-address-book text-xs text-teal-500"></i> Buku Tamu Digital
                        </a>
                    </div>
                </div>

                {{-- SPMB 2026 --}}
                <a href="{{ route('public.spmb') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('public.spmb') || request()->routeIs('public.ppdb*') ? 'bg-amber-100 text-amber-900 font-bold' : 'hover:bg-gray-100 text-amber-700' }} transition">
                    <i class="fas fa-user-plus w-4 text-amber-600"></i> SPMB 2026
                </a>

                {{-- KONTAK --}}
                <a href="{{ route('public.kontak') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('public.kontak') ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-100' }} transition">
                    <i class="fas fa-envelope w-4 text-blue-600"></i> KONTAK
                </a>

                {{-- Divider & Login / Dashboard + Logout --}}
                <div class="border-t border-gray-200 pt-3 mt-3 space-y-2">
                    @if ($activeUser && $activeDashboardRoute)
                        <a href="{{ $activeDashboardRoute }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-900 text-white font-semibold hover:bg-blue-800 transition">
                            <i class="fas fa-tachometer-alt w-4"></i> Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition text-left">
                                <i class="fas fa-sign-out-alt w-4"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-900 text-white font-semibold hover:bg-blue-800 transition">
                            <i class="fas fa-sign-in-alt w-4"></i> Login Portal
                        </a>
                    @endif
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

