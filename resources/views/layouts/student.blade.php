<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Ruang Belajar') - SIMS Siswa</title>

    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #fafafa;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding-bottom: 80px; /* space for bottom nav */
        }
        /* Hide scrollbar for clean app look */
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>
<body>
    @php
        $u = Auth::guard('student')->user();
        $name = $u ? $u->student_name : 'Siswa';
    @endphp

    <div class="mobile-container">
        {{-- Header App --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-b-[2rem] px-6 pt-10 pb-8 shadow-md relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10">
                <i class="fas fa-shapes text-9xl -mt-4 -mr-4"></i>
            </div>
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-indigo-100 text-sm font-medium mb-1">Selamat datang kembali,</p>
                    <h1 class="text-2xl font-bold line-clamp-1 leading-tight">{{ $name }}</h1>
                </div>
                <div class="bg-white/20 p-1 rounded-full backdrop-blur-sm shrink-0">
                    <img src="https://ui-avatars.com/api/?background=ffffff&color=4f46e5&name={{ urlencode($name) }}&bold=true" class="w-12 h-12 rounded-full border-2 border-white object-cover">
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <main class="p-5 -mt-6 relative z-20">
            @yield('content')
        </main>

        {{-- Bottom Navigation --}}
        <div class="fixed bottom-0 w-full max-w-[480px] left-1/2 transform -translate-x-1/2 bg-white border-t border-gray-100 flex justify-around items-center py-3 px-6 shadow-[0_-10px_20px_rgba(0,0,0,0.03)] z-50 rounded-t-3xl">
            <a href="{{ route('student.lms.index') }}" class="flex flex-col items-center {{ request()->routeIs('student.lms.*') ? 'text-indigo-600' : 'text-gray-400 hover:text-indigo-400' }} transition">
                <div class="{{ request()->routeIs('student.lms.*') ? 'bg-indigo-50 p-2 rounded-xl mb-1' : 'mb-1' }}">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <span class="text-[10px] font-bold">Ruang Belajar</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="flex flex-col items-center text-gray-400 hover:text-red-500 transition cursor-pointer" onclick="this.submit()">
                @csrf
                <div class="mb-1">
                    <i class="fas fa-power-off text-xl"></i>
                </div>
                <span class="text-[10px] font-bold">Keluar</span>
            </form>
        </div>
    </div>

    {{-- SweetAlert Logic --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{!! session('success') !!}',
                confirmButtonColor: '#4f46e5', toast: true, position: 'top', showConfirmButton: false, timer: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error', title: 'Gagal!', text: '{!! session('error') !!}',
                confirmButtonColor: '#ef4444', toast: true, position: 'top', showConfirmButton: false, timer: 3000
            });
        </script>
    @endif

    @stack('js')
</body>
</html>
