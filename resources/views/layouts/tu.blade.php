<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - Yayasan Megatama Jambi</title>

    <link rel="stylesheet" href="{{ asset('css/final.css') }}">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 font-poppins text-gray-800">

    @php
        $user = $operator ?? (object) [
            'nama_operator' => 'Admin Tamu',
            'role_operator' => 'Staff',
            'foto_url' => null
        ];
    @endphp

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 z-20 bg-black bg-opacity-50 hidden lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-900 text-white transition-transform duration-300 -translate-x-full lg:translate-x-0 flex flex-col">
        <div class="flex items-center h-16 px-4 border-b border-blue-800 bg-blue-950">
            <svg class="w-8 h-8 text-white mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
            <span class="font-bold text-lg leading-tight">Yayasan<br>Megatama</span>
            <button onclick="toggleSidebar()" class="ml-auto lg:hidden text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Admin</p>

            <a href="{{ route('tu.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.dashboard') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('tu.guru.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.guru.index') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }} transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                Data Guru
            </a>
            <a href="{{ route('tu.student.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.student.index') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }} transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Data Siswa
            </a>
            <a href="{{ route('tu.ruangan.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.ruangan.index') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }} transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Data Ruangan
            </a>
            <a href="{{ route('tu.peminjaman.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.peminjaman.index') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }} transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Peminjaman Ruang
            </a>
            <a href="{{ route('tu.rekap') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.rekap') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }} transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Rekap Absensi
            </a>
            <a href="{{ route('tu.settings') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('tu.settings') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }} transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </div>

        <div class="p-4 border-t border-blue-800">
            <form action="" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-blue-100 rounded-lg hover:bg-red-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div id="main-content" class="lg:ml-64 transition-all duration-300 min-h-screen flex flex-col">
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10 h-16 flex items-center justify-between px-6">
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-gray-800">{{ $user->nama_operator }}</p>
                    <p class="text-xs text-gray-500">{{ $user->role_operator }}</p>
                </div>
                <img class="h-9 w-9 rounded-full object-cover border border-gray-300" src="{{ $user->foto_url ? asset($user->foto_url) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_operator) }}" alt="Avatar">
            </div>
        </header>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
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
</body>
</html>
