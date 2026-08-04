@extends('layouts.auth')

@section('title', 'Login Portal Akademik')

@section('content')
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-white mb-2">Login Portal Akademik</h1>
        <p class="text-blue-100 max-w-xl mx-auto">Silakan pilih jenis akun untuk masuk ke dashboard sistem</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl">

        {{-- Card Admin TU --}}
        <a href="{{ route('login.admin-tu') }}"
            class="login-card admin-tu glass-effect rounded-2xl shadow-xl p-8 flex flex-col justify-between min-h-[380px] group cursor-pointer">
            <div class="text-center">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-secondary" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold gradient-text-secondary mb-3">Admin TU</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">Akses untuk mengelola data guru, siswa, ruang, rekap absensi, dan
                    peminjaman ruang.</p>
            </div>
            <div
                class="btn-login btn-login-tu text-white py-3 px-4 w-full text-sm font-medium rounded-xl flex items-center justify-center group-hover:shadow-lg transition-all mt-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 16l3-4m0 0l-3-4m3 4H6m3-4v-1a3 3 0 013-3h6a3 3 0 013 3v10a3 3 0 01-3 3h-6a3 3 0 01-3-3v-1" />
                </svg>
                Masuk sebagai Admin TU
            </div>
        </a>

        {{-- Card Pegawai --}}
        <a href="{{ route('login.pegawai') }}"
            class="login-card guru glass-effect rounded-2xl shadow-xl p-8 flex flex-col justify-between min-h-[380px] group cursor-pointer">
            <div class="text-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5h6a2 2 0 012 2v1h1a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V10a2 2 0 012-2h1V7a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold gradient-text-green mb-3">Pegawai</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">Akses untuk melihat riwayat presensi, menginput presensi siswa,
                    dan mengelola kenaikan kelas.</p>
            </div>
            <div
                class="btn-login btn-login-guru text-white py-3 px-4 w-full text-sm font-medium rounded-xl flex items-center justify-center group-hover:shadow-lg transition-all mt-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 16l3-4m0 0l-3-4m3 4H6m3-4v-1a3 3 0 013-3h6a3 3 0 013 3v10a3 3 0 01-3 3h-6a3 3 0 01-3-3v-1" />
                </svg>
                Masuk sebagai Pegawai
            </div>
        </a>

        {{-- Card Kepala Sekolah --}}
        <a href="{{ route('login.kepala-sekolah') }}"
            class="login-card siswa glass-effect rounded-2xl shadow-xl p-8 flex flex-col justify-between min-h-[380px] group cursor-pointer">
            <div class="text-center">
                <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold mb-3"
                    style="background: linear-gradient(135deg, #4338ca, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Kepala Sekolah</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">Akses dashboard ringkasan & pemantauan seluruh aktivitas sekolah
                    secara menyeluruh.</p>
            </div>
            <div class="text-white py-3 px-4 w-full text-sm font-medium rounded-xl flex items-center justify-center transition-all duration-200 group-hover:shadow-lg mt-auto"
                style="background: linear-gradient(135deg, #4338ca, #6366f1);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 16l3-4m0 0l-3-4m3 4H6m3-4v-1a3 3 0 013-3h6a3 3 0 013 3v10a3 3 0 01-3 3h-6a3 3 0 01-3-3v-1" />
                </svg>
                Masuk sebagai Kepala Sekolah
            </div>
        </a>

    </div>
@endsection
