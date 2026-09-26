<!DOCTYPE html>
<html lang="id" class="overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Dashboard') - Sistem Informasi Manajemen Sekolah</title>

    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #172554;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #3b82f6;
            border-radius: 10px;
        }

        /* Mobile Collapsible Sidebar Styles */
        #sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 40;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.3s;
        }

        #sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        @media (max-width: 1023.98px) {
            #sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 50;
                width: 16rem;
                max-width: calc(100vw - 3rem);
                transform: translateX(-100%);
                visibility: hidden;
                pointer-events: none;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.3s;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            }

            #sidebar.sidebar-open {
                transform: translateX(0);
                visibility: visible;
                pointer-events: auto;
            }

            #main-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }
        }

        @media (min-width: 1024px) {
            #sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 30;
                width: 16rem;
                transform: none !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }

            #sidebar-overlay {
                display: none !important;
            }
        }

        /* Isolated Table Horizontal Scroll */
        .overflow-x-auto {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('head')
</head>

<body class="bg-gray-100 font-poppins text-gray-800 mode-2 overflow-x-hidden">

    {{--
        =========================================================
        LOGIKA DETEKSI USER (OPERATOR / GURU / SISWA)
        =========================================================
    --}}
    @php
        $activeRole = 'guest';
        $name = 'Guest';
        $roleLabel = 'Visitor';
        $photoUrl = null;
        $waliKelas = null;

        // 1. Cek Login Operator (Admin TU / Kepala Sekolah)
        if (Auth::guard('operator')->check()) {
            $u = Auth::guard('operator')->user();
            $photoUrl = $u->photo_url ?? null;
            $name = $u->name;
            $roleLabel = $u->role_operator ?? 'Tata Usaha';
            // Kepala Sekolah role
            if (in_array($u->role_operator, ['Kepala Sekolah', 'principal'])) {
                $activeRole = 'principal';
            } else {
                $activeRole = 'operator';
            }
        }
        // 2. Cek Login Guru
        elseif (Auth::guard('teacher')->check()) {
            $u = Auth::guard('teacher')->user();
            $activeRole = 'teacher';
            $name = $u->name;
            $roleLabel = 'Guru Mata Pelajaran';

            // Cek apakah dia wali kelas
            if ($u->classroom) {
                $roleLabel = 'Wali Kelas ' . $u->classroom->name;
                $waliKelas = $u->classroom->name;
            }
            $photoUrl = $u->photo_url ?? null;
        }
        // 3. Cek Login Siswa (BARU)
        elseif (Auth::guard('student')->check()) {
            $u = Auth::guard('student')->user();
            $activeRole = 'student';
            $name = $u->student_name;
            $roleLabel = 'Siswa Kelas ' . ($u->classroom->name ?? '-');
            $photoUrl = null; // Bisa tambahkan kolom photo_url di tabel students nanti
        }
        // 4. Fallback (Jika variabel dikirim manual dari Controller)
        elseif (isset($operator)) {
            $activeRole = 'operator';
            $name = $operator->name;
            $roleLabel = $operator->role_operator;
        } elseif (isset($user)) {
            $activeRole = 'teacher';
            $name = $user->name;
            $roleLabel = $user->homeroom_class ?? 'Guru';
        }

        // Avatar Generator (Jika tidak ada foto)
        $avatar = $photoUrl
            ? (str_starts_with($photoUrl, 'img/') ? asset($photoUrl) : asset('storage/' . $photoUrl))
            : 'https://ui-avatars.com/api/?background=random&color=fff&name=' . urlencode($name);
    @endphp

    <div id="sidebar-overlay" onclick="toggleSidebar(false)"
        class="fixed inset-0 z-40 lg:hidden" aria-hidden="true"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-900 text-white flex flex-col shadow-xl"
        aria-label="Sidebar Navigasi">

        <div class="flex items-center h-16 px-4 border-b border-blue-800 bg-blue-950 shrink-0">
            <svg class="w-8 h-8 text-white mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
            </svg>
            <a href="#" class="flex items-center space-x-2">
                <span class="text-xl font-bold text-white">SIMS <span
                        class="text-xs font-normal text-blue-300">v2.0</span></span>
            </a>
            <button onclick="toggleSidebar(false)"
                class="ml-auto lg:hidden text-blue-200 hover:text-white p-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors"
                aria-label="Tutup Menu">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 sidebar-scroll">

            {{-- ==================== MENU ADMIN TU ==================== --}}
            @if ($activeRole === 'operator')
                <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Utama</p>

                <a href="{{ route('tu.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.dashboard') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-home w-5 mr-3 text-center"></i> Dashboard
                </a>

                {{-- Master Data --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                </div>

                <a href="{{ route('tu.teacher.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.teacher.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-chalkboard-teacher w-5 mr-3 text-center"></i> Data Guru
                </a>
                <a href="{{ route('tu.student.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.student.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-user-graduate w-5 mr-3 text-center"></i> Data Siswa
                </a>

                {{-- Akademik --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Akademik & LMS</p>
                </div>

                <a href="{{ route('tu.academic-years.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.academic-years.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-calendar-week w-5 mr-3 text-center"></i> <span>Tahun Ajaran</span>
                </a>
                <a href="{{ route('tu.classrooms.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.classrooms.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-chalkboard w-5 mr-3 text-center"></i> <span>Data Kelas</span>
                </a>
                <a href="{{ route('tu.subjects.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.subjects.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-book w-5 mr-3 text-center"></i> <span>Mata Pelajaran</span>
                </a>
                <a href="{{ route('tu.schedules.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.schedules.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-calendar-alt w-5 mr-3 text-center"></i> <span>Jadwal Pelajaran</span>
                </a>

                {{-- Administrasi & Portal --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administrasi</p>
                </div>
                <a href="{{ route('tu.rekap') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.rekap') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-clipboard-list w-5 mr-3 text-center"></i> Rekap Absensi
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Portal Website</p>
                </div>
                <a href="{{ route('tu.inbox.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.inbox.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-inbox w-5 mr-3 text-center"></i> <span>Kotak Masuk</span>
                </a>
                <a href="{{ route('tu.posts.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.posts.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-newspaper w-5 mr-3 text-center"></i> <span>Berita & Artikel</span>
                </a>
                <a href="{{ route('tu.events.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.events.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-calendar-check w-5 mr-3 text-center"></i> <span>Agenda Sekolah</span>
                </a>
                <a href="{{ route('tu.settings.website') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.settings.website') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-sliders-h w-5 mr-3 text-center"></i> <span>Pengaturan Web</span>
                </a>
                <a href="{{ route('tu.settings.card') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.settings.card') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-id-card w-5 mr-3 text-center"></i> <span>Desain ID Card</span>
                </a>
                {{-- <a href="{{ route('tu.facility.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.facility.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-building w-5 mr-3 text-center"></i> <span>Fasilitas Sekolah</span>
                </a> --}}

                {{-- PPDB --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">PPDB Online</p>
                </div>
                <a href="{{ route('tu.ppdb.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.ppdb.*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-user-plus w-5 mr-3 text-center"></i> <span>Data Pendaftar</span>
                </a>
                <a href="{{ route('public.ppdb') }}" target="_blank"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800">
                    <i class="fas fa-external-link-alt w-5 mr-3 text-center text-xs"></i> <span>Halaman PPDB Publik</span>
                </a>

            {{-- ==================== MENU KEPALA SEKOLAH ==================== --}}
            @elseif($activeRole === 'principal')
                <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Dashboard</p>

                <a href="{{ route('principal.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('principal.dashboard') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i> Ringkasan Sekolah
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Monitoring</p>
                </div>
                <a href="{{ route('tu.rekap') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.rekap') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-clipboard-list w-5 mr-3 text-center"></i> Rekap Absensi
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Sekolah</p>
                </div>
                <a href="{{ route('tu.student.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.student.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-user-graduate w-5 mr-3 text-center"></i> Data Siswa
                </a>
                <a href="{{ route('tu.teacher.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.teacher.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-chalkboard-teacher w-5 mr-3 text-center"></i> Data Guru
                </a>
                <a href="{{ route('tu.classrooms.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.classrooms.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-chalkboard w-5 mr-3 text-center"></i> Data Kelas
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Laporan & Publik</p>
                </div>
                <a href="{{ route('tu.ppdb.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.ppdb.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-user-plus w-5 mr-3 text-center"></i> Laporan PPDB
                </a>
                <a href="{{ route('tu.posts.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.posts.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-newspaper w-5 mr-3 text-center"></i> Portal Berita
                </a>
                <a href="{{ route('tu.inbox.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.inbox.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-inbox w-5 mr-3 text-center"></i> Kotak Masuk
                </a>

                {{-- ==================== MENU GURU ==================== --}}
            @elseif($activeRole === 'teacher')
                <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Guru</p>

                <a href="{{ route('teacher.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.dashboard') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-home w-5 mr-3 text-center"></i> Dashboard
                </a>
                <a href="{{ route('teacher.student-attendance') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.student-attendance') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-user-check w-5 mr-3 text-center"></i> Presensi Siswa
                </a>

                {{-- MENU LMS GURU --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Kegiatan Belajar</p>
                </div>
                <a href="{{ route('teacher.lms.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.lms.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-book-reader w-5 mr-3 text-center"></i> Kelas Saya (LMS)
                </a>

                {{-- MENU WALI KELAS --}}
                @if ($waliKelas)
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Wali Kelas</p>
                    </div>
                    @php $isWaliKelasAkhir = preg_match('/Kelas\s*(9|12)/i', $waliKelas); @endphp

                    @if ($isWaliKelasAkhir)
                        <a href="{{ route('teacher.graduation') }}"
                            class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.graduation') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                            <i class="fas fa-graduation-cap w-5 mr-3 text-center"></i> Kelulusan Siswa
                        </a>
                    @else
                        <a href="{{ route('teacher.promotion') }}"
                            class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.promotion') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                            <i class="fas fa-level-up-alt w-5 mr-3 text-center"></i> Kenaikan Kelas
                        </a>
                    @endif
                @endif

                {{-- ==================== MENU SISWA (BARU) ==================== --}}
            @elseif($activeRole === 'student')
                <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Siswa</p>

                <a href="{{ route('student.lms.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('student.lms.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-book-open w-5 mr-3 text-center"></i> Ruang Belajar
                </a>

                {{-- Bisa tambah menu lain nanti, misal: Riwayat Nilai, Profil, dll --}}
            @endif

            {{-- MENU UMUM --}}
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Profil</p>
            </div>

            @if ($activeRole === 'operator')
                <a href="{{ route('tu.settings.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('*.settings.index') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-cog w-5 mr-3 text-center"></i> Settings
                </a>
            @elseif($activeRole === 'teacher')
                <a href="{{ route('teacher.settings') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.settings') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-cog w-5 mr-3 text-center"></i> Settings
                </a>
            @endif

        </div>

        <div class="p-4 border-t border-blue-800 bg-blue-950">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-3 py-2 text-blue-100 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                    <i class="fas fa-sign-out-alt w-5 mr-3 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div id="main-content" class="lg:ml-64 transition-all duration-300 min-h-screen flex flex-col min-w-0 max-w-full overflow-x-hidden">
        <header
            class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 h-16 flex items-center justify-between px-4 sm:px-6">
            <div class="flex items-center min-w-0 mr-3">
                <button id="sidebar-toggle-btn" onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 mr-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shrink-0 transition-colors"
                    aria-label="Buka Menu" aria-expanded="false" aria-controls="sidebar">
                    <i class="fas fa-bars text-lg sm:text-xl"></i>
                </button>
                <h1 class="text-base sm:text-xl font-bold text-gray-800 truncate">@yield('title')</h1>
            </div>

            <div class="flex items-center space-x-3 shrink-0">
                <div class="text-right hidden md:block leading-tight">
                    <p class="text-sm font-semibold text-gray-800">{{ $name }}</p>
                    <p class="text-xs text-gray-500">{{ $roleLabel }}</p>
                </div>
                <img class="h-9 w-9 rounded-full object-cover border border-gray-300 bg-gray-100"
                    src="{{ $avatar }}" alt="Avatar">
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 min-w-0 max-w-full overflow-x-hidden">
            @yield('content')
        </main>

        <footer class="bg-white border-t py-4 px-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Sistem Informasi Manajemen Sekolah. All rights reserved.
        </footer>
    </div>

    <script>
        function toggleSidebar(forceState) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            if (!sidebar || !overlay) return;

            const isOpen = sidebar.classList.contains('sidebar-open');
            const shouldOpen = typeof forceState === 'boolean' ? forceState : !isOpen;

            if (shouldOpen) {
                sidebar.classList.add('sidebar-open');
                overlay.classList.add('active');
                document.body.classList.add('overflow-hidden');
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-expanded', 'true');
                    toggleBtn.setAttribute('aria-label', 'Tutup Menu');
                }
            } else {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('active');
                document.body.classList.remove('overflow-hidden');
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    toggleBtn.setAttribute('aria-label', 'Buka Menu');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 1024) {
                            toggleSidebar(false);
                        }
                    });
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth < 1024) {
                    toggleSidebar(false);
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    toggleSidebar(false);
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{!! session('success') !!}',
                    confirmButtonColor: '#3b82f6',
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{!! session('error') !!}',
                    confirmButtonColor: '#ef4444',
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorHtml = '<ul style="text-align: left; list-style-type: disc; padding-left: 20px;">';
                @foreach ($errors->all() as $error)
                    errorHtml += '<li>{{ $error }}</li>';
                @endforeach
                errorHtml += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errorHtml,
                    confirmButtonColor: '#ef4444',
                });
            });
        </script>
    @endif

    <script>
        function confirmDelete(event, formElement) {
            confirmAction(event, formElement, 'Hapus data ini?', 'Tindakan ini tidak dapat dibatalkan!', 'Ya, Hapus!', '#ef4444');
        }

        function confirmAction(event, formElement, title, text, confirmText, confirmColor = '#3b82f6') {
            event.preventDefault();
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#9ca3af',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
        }
    </script>
    @stack('js')
</body>

</html>
