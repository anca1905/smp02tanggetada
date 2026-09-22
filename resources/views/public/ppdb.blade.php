@extends('layouts.public')

@section('title', 'PPDB Online - Pendaftaran Peserta Didik Baru')

@push('styles')
<style>
    .ppdb-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);
        position: relative;
        overflow: hidden;
    }
    .ppdb-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
    }
    .ppdb-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: linear-gradient(to bottom, transparent, #f9fafb);
    }
    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.2;
    }
    .blob-1 { width: 400px; height: 400px; background: #facc15; top: -100px; right: -80px; }
    .blob-2 { width: 300px; height: 300px; background: #3b82f6; bottom: 50px; left: -60px; }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .float-anim { animation: float 4s ease-in-out infinite; }
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fade-up 0.7s ease both; }
    .fade-up-2 { animation: fade-up 0.7s ease 0.15s both; }
    .fade-up-3 { animation: fade-up 0.7s ease 0.3s both; }
    .jurusan-card:hover { transform: translateY(-6px); }
    .jurusan-card { transition: all 0.3s ease; }
    .timeline-step::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 44px;
        bottom: -20px;
        width: 2px;
        background: linear-gradient(to bottom, #1e3a8a, transparent);
    }
    .timeline-step:last-child::before { display: none; }
</style>
@endpush

@section('content')

    {{-- ===== HERO SECTION ===== --}}
    <section class="ppdb-hero text-white py-24 md:py-36 relative">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">

                {{-- Left: Text --}}
                <div class="lg:w-1/2 text-center lg:text-left">
                    <span class="fade-up inline-block bg-yellow-400/20 border border-yellow-400/40 text-yellow-300 text-sm font-semibold px-4 py-1.5 rounded-full mb-6">
                        <i class="fas fa-bullhorn mr-2"></i> Pendaftaran Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                    </span>

                    <h1 class="fade-up-2 text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        PPDB Tahun<br>
                        <span class="text-yellow-400">{{ date('Y') }}</span> Telah Dibuka
                    </h1>

                    <p class="fade-up-3 text-blue-100 text-lg leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                        Bergabunglah bersama kami dan wujudkan impianmu menjadi generasi yang berkarakter, cerdas, dan berakhlak mulia.
                    </p>

                    <div class="fade-up-3 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('public.ppdb.daftar') }}"
                            class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-yellow-400 text-blue-900 font-bold rounded-xl hover:bg-yellow-300 transition-all shadow-xl hover:shadow-yellow-400/30 hover:scale-105">
                            <i class="fas fa-user-plus text-lg"></i>
                            Daftar Sekarang
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#info-ppdb"
                            class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white/10 backdrop-blur border border-white/30 text-white font-semibold rounded-xl hover:bg-white/20 transition-all">
                            <i class="fas fa-info-circle"></i>
                            Info Lengkap
                        </a>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="fade-up-3 mt-12 flex flex-wrap justify-center lg:justify-start gap-8">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-yellow-400">Gratis</p>
                            <p class="text-xs text-blue-200 uppercase tracking-wide mt-1">Pendaftaran</p>
                        </div>
                        <div class="w-px bg-white/20 hidden sm:block"></div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-yellow-400">Online</p>
                            <p class="text-xs text-blue-200 uppercase tracking-wide mt-1">Proses Daftar</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Floating Card --}}
                <div class="lg:w-1/2 w-full max-w-sm mx-auto lg:max-w-none">
                    <div class="float-anim relative">
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl">
                            <h3 class="font-bold text-lg mb-5 text-center text-white flex items-center justify-center gap-2">
                                <i class="fas fa-calendar-check text-yellow-400"></i> Timeline PPDB {{ date('Y') }}
                            </h3>
                            <div class="space-y-4">
                                @foreach ([
                                    ['Pembuatan Akun & Verifikasi', '8 Mei – 5 Juni ' . date('Y'), 'fa-user-check', 'bg-yellow-400'],
                                    ['Pendaftaran', '3 Juni – 5 Juni ' . date('Y'), 'fa-pencil-alt', 'bg-blue-400'],
                                    ['Pengumuman', '10 Juni ' . date('Y'), 'fa-bullhorn', 'bg-purple-400'],
                                    ['Daftar Ulang', '10 – 12 Juni ' . date('Y'), 'fa-check-double', 'bg-green-400'],
                                ] as [$label, $tanggal, $icon, $color])
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 {{ $color }} rounded-xl flex items-center justify-center shrink-0 shadow">
                                        <i class="fas {{ $icon }} text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white text-sm">{{ $label }}</p>
                                        <p class="text-blue-200 text-xs">{{ $tanggal }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="{{ route('public.ppdb.daftar') }}"
                                class="mt-6 block w-full bg-yellow-400 text-blue-900 text-center font-bold py-3 rounded-xl hover:bg-yellow-300 transition text-sm">
                                Mulai Daftar →
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===== KEUNGGULAN ===== --}}
    <section class="bg-blue-900 py-12" id="info-ppdb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white text-center">
                @foreach ([
                    ['fa-school', 'Fasilitas Lengkap', 'Ruang kelas nyaman dan fasilitas olahraga yang mendukung bakat siswa.'],
                    ['fa-chalkboard-teacher', 'Guru Bersertifikat', 'Pengajar profesional dengan sertifikasi kompetensi di bidangnya masing-masing.'],
                    ['fa-mosque', 'Unggul Imtq & Iptek', 'Mengutamakan karakter mulia, akhlak Islami, dan teknologi terkini.'],
                ] as [$icon, $title, $desc])
                <div class="flex flex-col items-center gap-3 p-4">
                    <div class="w-14 h-14 bg-yellow-400/20 rounded-full flex items-center justify-center">
                        <i class="fas {{ $icon }} text-yellow-400 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">{{ $title }}</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SYARAT PENDAFTARAN ===== --}}
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-16 items-center">

                <div class="lg:w-1/2">
                    <span class="text-yellow-500 font-semibold text-sm uppercase tracking-widest">Persyaratan</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-2 mb-6">Syarat & Ketentuan Pendaftaran</h2>
                    <div class="space-y-4">
                        @foreach ([
                            ['fa-user-check', 'Persyaratan Umum', 'Usia maks 15 tahun (1 Juli) dan Lulus SD/sederajat.'],
                            ['fa-image', 'Pas Photo 3x4', 'File pas foto ukuran 3x4 (JPG/PNG).'],
                            ['fa-graduation-cap', 'Ijazah / SKL', 'Scan Ijazah SD atau Surat Keterangan Lulus (PDF).'],
                            ['fa-file-alt', 'Transkrip & Sertifikat TKA', 'Scan Transkrip Nilai dan Sertifikat TKA (PDF).'],
                            ['fa-id-card', 'Akta Kelahiran & KK', 'Scan Akta Kelahiran dan Kartu Keluarga (PDF).'],
                            ['fa-id-badge', 'KTP Orang Tua', 'Scan KTP Ayah dan Ibu kandung (PDF).'],
                            ['fa-print', 'Bukti Pendaftaran', 'Calon murid mencetak formulir bukti pendaftaran online.'],
                        ] as [$icon, $judul, $keterangan])
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas {{ $icon }} text-blue-700 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $judul }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $keterangan }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Alur Pendaftaran --}}
                <div class="lg:w-1/2">
                    <span class="text-yellow-500 font-semibold text-sm uppercase tracking-widest">Cara Mendaftar</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-2 mb-8">Alur Pendaftaran Online</h2>
                    <div class="space-y-6">
                        @foreach ([
                            ['1', 'Buka Formulir Online', 'Klik tombol "Daftar Sekarang" dan isi data diri dengan lengkap dan benar.', 'bg-blue-600'],
                            ['2', 'Simpan No. Registrasi', 'Setelah berhasil daftar, catat nomor registrasi Anda untuk pelacakan status.', 'bg-yellow-500'],
                            ['3', 'Verifikasi Berkas', 'Datang ke sekolah dengan membawa berkas fisik untuk diverifikasi panitia.', 'bg-purple-600'],
                            ['4', 'Pengumuman & Daftar Ulang', 'Pantau pengumuman kelulusan dan lakukan daftar ulang sesuai jadwal.', 'bg-green-600'],
                        ] as [$no, $judul, $keterangan, $color])
                        <div class="timeline-step relative flex items-start gap-5 pb-6">
                            <div class="w-10 h-10 {{ $color }} text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-lg z-10">
                                {{ $no }}
                            </div>
                            <div class="pt-1">
                                <h4 class="font-bold text-gray-800">{{ $judul }}</h4>
                                <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $keterangan }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="bg-gradient-to-r from-blue-900 to-blue-700 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Siap Bergabung Bersama Kami?</h2>
            <p class="text-blue-200 text-lg mb-10 leading-relaxed">
                Pendaftaran dilakukan secara online — mudah, cepat, dan bisa dari mana saja.<br>
                Jangan lewatkan kesempatan emas ini!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.ppdb.daftar') }}"
                    class="group inline-flex items-center justify-center gap-3 px-10 py-4 bg-yellow-400 text-blue-900 font-bold rounded-xl hover:bg-yellow-300 transition shadow-xl text-lg hover:scale-105">
                    <i class="fas fa-user-plus"></i>
                    Daftar Sekarang
                    <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('public.kontak') }}"
                    class="inline-flex items-center justify-center gap-3 px-10 py-4 bg-white/10 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/20 transition text-lg">
                    <i class="fas fa-phone"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

@endsection
