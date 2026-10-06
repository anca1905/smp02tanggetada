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
                {{-- <a href="{{ route('presensi.index') }}"
                    class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded hover:bg-white/20 transition shadow-lg flex items-center justify-center">
                    <i class="fas fa-camera mr-2"></i> Kiosk Presensi
                </a> --}}
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

    {{-- Featured Event Banner: Lomba Literasi Bulan Bahasa --}}
    <div class="py-8 bg-gray-50 -mt-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 text-white shadow-xl border border-yellow-500/30">
                <div class="relative z-10 p-6 sm:p-10 lg:p-12">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-4">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-yellow-400/20 border border-yellow-400/30 text-yellow-300 text-xs sm:text-sm font-semibold tracking-wide">
                                <i class="fas fa-bullhorn text-yellow-400"></i> PERINGATAN BULAN BAHASA 2026
                            </div>

                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                                LOMBA LITERASI ANTAR KELAS
                            </h2>

                            <p class="text-yellow-200/90 text-sm sm:text-base italic font-light">
                                &ldquo;Utamakan Bahasa Indonesia, Lestarikan Bahasa Daerah, Kuasai Bahasa Asing&rdquo;
                            </p>

                            <p class="text-blue-100 text-sm sm:text-base leading-relaxed">
                                Diselenggarakan oleh <strong class="text-yellow-300">Perpustakaan SMP Negeri 2 Tanggetada</strong>. Saatnya generasi muda menunjukkan kreativitas, keberanian, dan semangat kompetisi!
                            </p>

                            {{-- Badges Cabang Lomba --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                                <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-400/20 text-yellow-300 flex items-center justify-center shrink-0">
                                        <i class="fas fa-brain text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-300">Cerdas Cermat</div>
                                        <div class="text-sm font-bold text-white">3 Orang / Kelas</div>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-400/20 text-yellow-300 flex items-center justify-center shrink-0">
                                        <i class="fas fa-microphone-alt text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-300">Pidato</div>
                                        <div class="text-sm font-bold text-white">1 Orang / Kelas</div>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-400/20 text-yellow-300 flex items-center justify-center shrink-0">
                                        <i class="fas fa-feather-alt text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-300">Puisi</div>
                                        <div class="text-sm font-bold text-white">1 Orang / Kelas</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Jadwal & Lokasi --}}
                            <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-blue-200 pt-2">
                                <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/10">
                                    <i class="fas fa-calendar-alt text-yellow-400"></i> 28 Oktober 2026 (09.00 - Selesai)
                                </span>
                                <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/10">
                                    <i class="fas fa-map-marker-alt text-yellow-400"></i> Lab. Komputer SMPN 2 Tanggetada
                                </span>
                                <span class="flex items-center gap-1.5 bg-red-500/20 text-red-200 px-3 py-1.5 rounded-lg border border-red-500/30 font-medium">
                                    <i class="fas fa-clock text-red-400"></i> Batas Daftar: 10 - 24 Oktober 2026
                                </span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="lg:col-span-4 flex flex-col gap-3 justify-center">
                            <a href="https://forms.gle/frfZEwZ9x2xwuiTM9" target="_blank" rel="noopener noreferrer"
                                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-300 hover:to-amber-400 text-blue-950 font-extrabold text-center shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-base">
                                <i class="fas fa-edit text-lg"></i> Daftar Sekarang (Google Form)
                            </a>

                            <button type="button" onclick="openLiterasiModal()"
                                class="w-full py-3 px-5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-center transition flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-image text-yellow-400"></i> Lihat Poster & Rincian Lomba
                            </button>

                            <a href="https://wa.me/62853465489992?text=Halo%20Perpustakaan%20SMPN%202%20Tanggetada,%20saya%20ingin%20bertanya%20mengenai%20Lomba%20Literasi" target="_blank" rel="noopener noreferrer"
                                class="w-full py-2.5 px-4 rounded-xl text-emerald-300 hover:text-emerald-200 hover:bg-emerald-500/10 text-center transition flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <i class="fab fa-whatsapp text-emerald-400 text-base"></i> Hubungi WA Panitia: 0853465489992
                            </a>
                        </div>
                    </div>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
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

                {{-- <div class="bg-gray-50 p-8 rounded-lg border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sarana Prasarana</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Manajemen inventaris sekolah dan peminjaman fasilitas ruangan yang terintegrasi untuk mendukung
                        kegiatan sekolah.
                    </p>
                </div> --}}
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

    {{-- Floating Quick Button (Pojok Kanan Bawah) --}}
    <button type="button" onclick="openLiterasiModal()"
        class="fixed bottom-6 right-6 z-40 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-blue-950 font-bold px-4 py-3 rounded-full shadow-2xl border-2 border-white flex items-center gap-2.5 transition transform hover:scale-105 cursor-pointer focus:outline-none"
        title="Buka Info Lomba Literasi">
        <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
        </span>
        <i class="fas fa-trophy text-amber-900 text-base"></i>
        <span class="text-xs sm:text-sm font-extrabold tracking-wide">Info Lomba Literasi</span>
    </button>

    {{-- Pop-up Modal Interaktif --}}
    <div id="literasiModal" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 transition-all duration-300">
        <div class="relative bg-white rounded-3xl max-w-4xl w-full max-h-[92vh] overflow-hidden shadow-2xl border border-gray-100 flex flex-col">
            
            {{-- Header Modal --}}
            <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-yellow-400 text-blue-950 flex items-center justify-center font-bold">
                        <i class="fas fa-trophy"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-base sm:text-lg leading-tight">Pengumuman: Lomba Literasi Antar Kelas</h3>
                        <p class="text-xs text-blue-200">Perpustakaan SMP Negeri 2 Tanggetada &bull; Peringatan Bulan Bahasa</p>
                    </div>
                </div>
                <button type="button" onclick="closeLiterasiModal()" class="text-white/80 hover:text-white p-2 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    
                    {{-- Sisi Kiri: Poster --}}
                    <div class="md:col-span-5 flex flex-col items-center">
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200 bg-gray-50 group relative max-w-xs w-full">
                            <img src="{{ asset('images/lomba-literasi-antar-kelas-2026.jpg') }}"
                                alt="Poster Lomba Literasi SMPN 2 Tanggetada"
                                class="w-full h-auto object-cover transition duration-300 group-hover:scale-102">
                            <a href="{{ asset('images/lomba-literasi-antar-kelas-2026.jpg') }}" target="_blank"
                                class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white font-semibold text-xs transition gap-2">
                                <i class="fas fa-search-plus text-base"></i> Buka Gambar Penuh
                            </a>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Detail & Cabang Lomba --}}
                    <div class="md:col-span-7 space-y-4">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-2">
                                📅 Pendaftaran: 10 &ndash; 24 Oktober 2026
                            </span>
                            <h4 class="text-xl sm:text-2xl font-bold text-gray-900 leading-snug">
                                Semarak Bulan Bahasa: Lomba Literasi Siswa
                            </h4>
                            <p class="text-xs sm:text-sm text-gray-600 italic mt-1">
                                &ldquo;Utamakan Bahasa Indonesia, Lestarikan Bahasa Daerah, Kuasai Bahasa Asing&rdquo;
                            </p>
                        </div>

                        {{-- Cabang Lomba Card --}}
                        <div class="space-y-2 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis-Jenis Lomba:</div>
                            <div class="flex items-start gap-2.5 text-xs sm:text-sm text-gray-700">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</span>
                                <div><strong class="text-gray-900">Lomba Cerdas Cermat:</strong> Regu 3 orang per kelas</div>
                            </div>
                            <div class="flex items-start gap-2.5 text-xs sm:text-sm text-gray-700">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</span>
                                <div><strong class="text-gray-900">Lomba Pidato:</strong> 1 orang per kelas (Tema: <em>&ldquo;Bahasa Sebagai Pemersatu Bangsa&rdquo;</em>)</div>
                            </div>
                            <div class="flex items-start gap-2.5 text-xs sm:text-sm text-gray-700">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</span>
                                <div><strong class="text-gray-900">Lomba Puisi:</strong> 1 orang per kelas (Tema: <em>&ldquo;Pahlawanku&rdquo;</em>)</div>
                            </div>
                        </div>

                        {{-- Info Pelaksanaan --}}
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-100">
                                <div class="text-gray-500">Pelaksanaan:</div>
                                <div class="font-bold text-blue-900">28 Oktober 2026</div>
                                <div>Pukul 09.00 - Selesai</div>
                            </div>
                            <div class="bg-amber-50/70 p-2.5 rounded-xl border border-amber-100">
                                <div class="text-gray-500">Lokasi:</div>
                                <div class="font-bold text-amber-900">Lab. Komputer</div>
                                <div>SMPN 2 Tanggetada</div>
                            </div>
                        </div>

                        {{-- Tombol Tindakan --}}
                        <div class="pt-2 space-y-2">
                            <a href="https://forms.gle/frfZEwZ9x2xwuiTM9" target="_blank" rel="noopener noreferrer"
                                class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-300 hover:to-amber-400 text-blue-950 font-extrabold text-center shadow-lg transition flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-external-link-alt"></i> Buka Formulir Pendaftaran (Google Form)
                            </a>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <a href="https://wa.me/62853465489992?text=Halo%20Perpustakaan%20SMPN%202%20Tanggetada,%20saya%20ingin%20bertanya%20mengenai%20Lomba%20Literasi" target="_blank" rel="noopener noreferrer"
                                    class="py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-center text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fab fa-whatsapp text-sm"></i> WA: 0853465489992
                                </a>
                                <a href="{{ route('public.berita.show', 'lomba-literasi-antar-kelas-bulan-bahasa-2026') }}"
                                    class="py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-center text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fas fa-file-alt"></i> Baca Rilis Berita
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex items-center justify-between shrink-0">
                <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer select-none">
                    <input type="checkbox" id="dontShowAgain" class="rounded text-blue-600 focus:ring-blue-500">
                    <span>Jangan tampilkan otomatis pada sesi ini</span>
                </label>
                <button type="button" onclick="closeLiterasiModalWithCheckbox()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- Script Modal Literasi --}}
    <script>
        function openLiterasiModal() {
            var modal = document.getElementById('literasiModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeLiterasiModal(permanentSession) {
            var modal = document.getElementById('literasiModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            if (permanentSession) {
                sessionStorage.setItem('literasi_modal_dismissed', 'true');
            }
        }

        function closeLiterasiModalWithCheckbox() {
            var checkbox = document.getElementById('dontShowAgain');
            closeLiterasiModal(checkbox && checkbox.checked);
        }

        document.addEventListener('click', function(e) {
            var modal = document.getElementById('literasiModal');
            if (modal && e.target === modal) {
                closeLiterasiModalWithCheckbox();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('literasiModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeLiterasiModalWithCheckbox();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('literasi_modal_dismissed')) {
                setTimeout(function() {
                    openLiterasiModal();
                }, 600);
            }
        });
    </script>
@endsection
