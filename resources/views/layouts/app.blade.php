<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Dashboard') - Yayasan Megatama Jambi</title>

    <link rel="stylesheet" href="{{ asset('assets/css/final.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
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
    </style>
</head>

<body class="bg-gray-100 font-poppins text-gray-800 mode-2">

    {{-- 
        =========================================================
        LOGIKA DETEKSI USER
        =========================================================
    --}}
    @php
        $activeRole = 'guest';
        $name = 'Guest';
        $roleLabel = 'Visitor';
        $photoUrl = null;
        $waliKelas = null;

        if (isset($operator)) {
            $activeRole = 'operator';
            $name = $operator->name ?? 'Admin TU';
            $roleLabel = $operator->role_operator ?? 'Tata Usaha';
            $photoUrl = $operator->foto_url ?? null;
        } elseif (isset($user)) {
            $activeRole = 'teacher';
            $name = $user->name ?? 'Guru';
            $roleLabel = 'Guru Mata Pelajaran';
            $waliKelas = $user->homeroom_class ?? null;
            if ($waliKelas && $waliKelas !== '-') {
                $roleLabel = $waliKelas;
            }
            $photoUrl = $user->foto_url ?? null;
        } elseif (Auth::check()) {
            $u = Auth::user();
            if (isset($u->role_operator)) {
                $activeRole = 'operator';
                $name = $u->name;
                $roleLabel = $u->role_operator;
            } else {
                $activeRole = 'teacher';
                $name = $u->name ?? $u->name;
                $roleLabel = $u->homeroom_class ?? 'Guru';
                $waliKelas = $u->homeroom_class;
            }
            $photoUrl = $u->foto_url ?? null;
        } else {
            $activeRole = 'guru';
            $name = 'Bapak Guru Dummy';
            $roleLabel = 'Wali Kelas 12';
            $waliKelas = 'Wali Kelas 12';
            $photoUrl = null;
        }

        $avatar = $photoUrl
            ? asset($photoUrl)
            : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($name);
    @endphp

    <div id="sidebar-overlay" onclick="toggleSidebar()"
        class="fixed inset-0 z-20 bg-black bg-opacity-50 hidden lg:hidden transition-opacity"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-900 text-white transition-transform duration-300 -translate-x-full lg:translate-x-0 flex flex-col shadow-xl">

        <div class="flex items-center h-16 px-4 border-b border-blue-800 bg-blue-950">
            <svg class="w-8 h-8 text-white mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
            </svg>
            <a href="#" class="flex items-center space-x-2">
                <span class="text-xl font-bold text-white">SIMS <span
                        class="text-xs font-normal text-blue-300">v1.0</span></span>
            </a>
            <button onclick="toggleSidebar()"
                class="ml-auto lg:hidden text-gray-400 hover:text-white focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 sidebar-scroll">

            {{-- ==================== MENU ADMIN TU ==================== --}}
            @if ($activeRole === 'operator')
                <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Admin</p>

                <a href="{{ route('tu.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.dashboard') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-home w-5 mr-3 text-center"></i> Dashboard
                </a>
                <a href="{{ route('tu.teacher.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.teacher.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-chalkboard-teacher w-5 mr-3 text-center"></i> Data Guru
                </a>
                <a href="{{ route('tu.student.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.student.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-user-graduate w-5 mr-3 text-center"></i> Data Siswa
                </a>
                <a href="{{ route('tu.room.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.room.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-door-open w-5 mr-3 text-center"></i> Data Ruangan
                </a>
                <a href="{{ route('tu.borrowing.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.borrowing.*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-calendar-check w-5 mr-3 text-center"></i> Peminjaman
                </a>
                <a href="{{ route('tu.rekap') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.rekap') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-clipboard-list w-5 mr-3 text-center"></i> Rekap Absensi
                </a>
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Akademik
                    </p>
                </div>
                <a href="{{ route('tu.schedules.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.schedules*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-book-open w-5 mr-3 text-center"></i>
                    <span>Jadwal Pelajaran</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Portal Website
                    </p>
                </div>
                <a href="{{ route('tu.inbox.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('admin.inbox*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-inbox w-5 mr-3 text-center"></i>
                    <span>Kotak Masuk</span>
                </a>
                <a href="{{ route('tu.posts.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.posts*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-newspaper w-5 mr-3 text-center"></i>
                    <span>Berita & Artikel</span>
                </a>
                <a href="{{ route('tu.events.index') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.events*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-calendar-alt w-5 mr-3 text-center"></i>
                    <span>Agenda Sekolah</span>
                </a>
                <a href="{{ route('tu.settings.website') }}"
                    class="flex items-center px-4 py-2 text-gray-100 hover:bg-blue-800 {{ request()->routeIs('tu.settings*') ? 'bg-blue-800 border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-sliders-h w-5 mr-3 text-center"></i>
                    <span>Pengaturan Web</span>
                </a>

                {{-- ==================== MENU GURU ==================== --}}
            @elseif($activeRole === 'teacher')
                <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Guru</p>

                <a href="{{ route('teacher.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.dashboard') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-home w-5 mr-3 text-center"></i> Dashboard
                </a>
                <a href="{{ route('teacher.history') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.history') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-history w-5 mr-3 text-center"></i> Riwayat Presensi
                </a>
                <a href="{{ route('teacher.student-attendance') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.student-attendance') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fas fa-user-check w-5 mr-3 text-center"></i> Presensi Siswa
                </a>

                @php
                    $isWaliKelasAkhir = preg_match('/Kelas\s*(9|12)/i', $waliKelas ?? '');
                @endphp

                @if ($isWaliKelasAkhir)
                    <a href="{{ route('teacher.graduation') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.graduation') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                        <i class="fas fa-graduation-cap w-5 mr-3 text-center"></i> Kelulusan Siswa
                    </a>
                @elseif($waliKelas)
                    <a href="{{ route('teacher.promotion') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('teacher.promotion') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                        <i class="fas fa-level-up-alt w-5 mr-3 text-center"></i> Kenaikan Kelas
                    </a>
                @endif
            @endif

            {{-- MENU UMUM --}}
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Profil
                </p>
            </div>

            <a href="{{ $activeRole === 'operator' ? route('tu.settings') : route('teacher.settings') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('*.settings') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-blue-100 hover:bg-blue-800' }}">
                <i class="fas fa-cog w-5 mr-3 text-center"></i> Settings
            </a>

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

    <div id="main-content" class="lg:ml-64 transition-all duration-300 min-h-screen flex flex-col">
        <header
            class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10 h-16 flex items-center justify-between px-6">
            <div class="flex items-center">
                <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 mr-2 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            </div>

            <div class="flex items-center space-x-3">
                <div class="text-right hidden md:block leading-tight">
                    <p class="text-sm font-semibold text-gray-800">{{ $name }}</p>
                    <p class="text-xs text-gray-500">{{ $roleLabel }}</p>
                </div>
                <img class="h-9 w-9 rounded-full object-cover border border-gray-300 bg-gray-100"
                    src="{{ $avatar }}" alt="Avatar">
            </div>
        </header>

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <footer class="bg-white border-t py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Sistem Informasi Manajemen Sekolah. All rights reserved.
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
    @stack('js')
</body>

</html>
