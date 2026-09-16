@extends('layouts.public')
@section('content')
    <div class="hero-section text-white py-24 md:py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

            <span
                class="inline-block py-1 px-4 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-sm font-medium mb-6">
                <i class="fas fa-check-circle text-green-400 mr-1"></i> Portal Resmi Akademik & Administrasi
            </span>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                {!! nl2br(e($site_settings['hero_title'] ?? 'Digitalisasi Pendidikan Menuju Sekolah Unggul')) !!}
            </h1>

            <p class="text-lg text-blue-100 max-w-2xl mx-auto mb-10 font-light leading-relaxed">
                {{ $site_settings['hero_description'] ??
                    'Platform terintegrasi untuk pengelolaan data akademik, kesiswaan, kepegawaian, serta presensi biometrik.
                                                                Transparan, Akuntabel, dan Real-time.' }}
            </p>

            @php
                $heroDashboardUrl = route('login');
                if (Auth::guard('operator')->check()) {
                    $heroDashboardUrl = in_array(Auth::guard('operator')->user()->role_operator, ['Kepala Sekolah', 'principal'])
                        ? route('principal.dashboard')
                        : route('tu.dashboard');
                } elseif (Auth::guard('teacher')->check()) {
                    $heroDashboardUrl = route('teacher.dashboard');
                } elseif (Auth::guard('student')->check()) {
                    $heroDashboardUrl = route('student.dashboard');
                }
            @endphp

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ $heroDashboardUrl }}"
                    class="px-8 py-4 bg-yellow-500 text-blue-900 font-bold rounded hover:bg-yellow-400 transition shadow-lg flex items-center justify-center">
                    Akses Dashboard <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('presensi.index') }}"
                    class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded hover:bg-white/20 transition shadow-lg flex items-center justify-center">
                    <i class="fas fa-camera mr-2"></i> Kiosk Presensi
                </a>
            </div>
        </div>

        <div class="absolute bottom-0 w-full overflow-hidden leading-[0]">
            <svg class="relative block w-full h-[60px] md:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    class="fill-gray-50"></path>
            </svg>
        </div>
    </div>

    <div class="bg-gray-50 py-12 -mt-4 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-white p-6 rounded shadow-sm border-b-4 border-blue-900">
                    <div class="text-3xl font-bold text-blue-900 mb-1">{{ $student }}</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Siswa Aktif</div>
                </div>
                <div class="bg-white p-6 rounded shadow-sm border-b-4 border-yellow-500">
                    <div class="text-3xl font-bold text-blue-900 mb-1">{{ $staff }}</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Guru & Staf</div>
                </div>
                <div class="bg-white p-6 rounded shadow-sm border-b-4 border-green-500">
                    <div class="text-3xl font-bold text-blue-900 mb-1">98%</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Tingkat Kehadiran</div>
                </div>
                <div class="bg-white p-6 rounded shadow-sm border-b-4 border-purple-500">
                    <div class="text-3xl font-bold text-blue-900 mb-1">24/7</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Akses Sistem</div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Layanan Utama</h2>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Sistem ini dirancang untuk memudahkan seluruh civitas
                    akademika dalam mengelola kegiatan belajar mengajar.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-id-card-alt text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Presensi Wajah</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Sistem pencatatan kehadiran modern dengan bukti foto wajah (Face Capture) dan lokasi untuk
                        memastikan validitas kehadiran guru dan siswa.
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Laporan Akademik</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Rekapitulasi data kehadiran, nilai, dan perkembangan siswa yang dapat diakses secara real-time
                        oleh wali kelas dan orang tua.
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sarana Prasarana</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Manajemen inventaris sekolah dan peminjaman fasilitas ruangan yang terintegrasi untuk mendukung
                        kegiatan sekolah.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Kabar Sekolah</h2>
                <p class="mt-4 text-gray-600">Informasi terbaru seputar kegiatan dan prestasi sekolah.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($latest_posts as $post)
                    <article
                        class="bg-gray-50 rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://via.placeholder.com/600x400?text=SIMS+News' }}"
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                            <div
                                class="absolute top-4 left-4 bg-blue-900 text-white text-xs font-bold px-3 py-1 rounded-full">
                                {{ $post->category }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="text-xs text-gray-500 mb-2">
                                <i class="far fa-clock mr-1"></i> {{ $post->created_at->diffForHumans() }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('public.berita.show', $post->slug) }}"
                                    class="hover:text-blue-600 transition">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($post->content), 100) }}
                            </p>
                            <a href="{{ route('public.berita.show', $post->slug) }}"
                                class="text-blue-600 text-sm font-semibold hover:underline mt-auto">
                                Baca selengkapnya &rarr;
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500">Belum ada berita yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('public.berita') }}"
                    class="inline-flex items-center px-6 py-3 border border-blue-900 text-base font-medium rounded-md text-blue-900 bg-white hover:bg-blue-50 transition">
                    Lihat Semua Berita
                </a>
            </div>
        </div>
    </div>
@endsection
