@extends('layouts.auth')

@section('title', 'Login Pegawai')

@section('content')
    <div class="w-full max-w-md mx-auto">

        {{-- Back link --}}
        <a href="{{ route('login') }}" class="inline-flex items-center text-blue-100 hover:text-white mb-6 transition-colors text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke pilihan akun
        </a>

        <div class="login-card guru glass-effect rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5h6a2 2 0 012 2v1h1a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V10a2 2 0 012-2h1V7a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l2 2 4-4" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold gradient-text-green mb-1">Login Pegawai</h2>
                <p class="text-gray-600 text-sm">Masuk untuk melihat riwayat presensi, menginput presensi siswa, dan mengelola kenaikan kelas.</p>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium text-red-700">Login gagal</p>
                    </div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600 ml-7">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="space-y-4" action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="hidden" name="role_type" value="teacher">

                <div>
                    <label for="identity-guru" class="block text-sm font-medium text-gray-700 mb-1">ID/Nama</label>
                    <div class="relative autocomplete-container">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" id="identity-guru" name="username" required minlength="3"
                            placeholder="Ketik ID/Nama" value="{{ old('username') }}"
                            class="input-effect w-full pl-10 pr-3 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <div id="autocomplete-dropdown-guru" class="autocomplete-dropdown hidden"></div>
                    </div>
                </div>

                <div>
                    <label for="password-guru" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password-guru" name="password" required
                            placeholder="Masukkan password"
                            class="input-effect w-full pl-10 pr-10 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <button type="button" data-toggle="password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="eye-icon h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="eye-slash-icon h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="btn-login btn-login-guru text-white py-2.5 px-4 w-full text-sm font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 16l3-4m0 0l-3-4m3 4H6m3-4v-1a3 3 0 013-3h6a3 3 0 013 3v10a3 3 0 01-3 3h-6a3 3 0 01-3-3v-1" />
                    </svg>
                    Login sebagai Pegawai
                </button>
            </form>
        </div>
    </div>
@endsection
