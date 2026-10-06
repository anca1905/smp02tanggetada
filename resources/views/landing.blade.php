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

    {{-- Featured Announcement Banner (Dinamis dari Admin TU) --}}
    @if (($site_settings['popup_active'] ?? '0') == '1')
        @php
            $popupImg = !empty($site_settings['popup_image'])
                ? asset('storage/' . $site_settings['popup_image'])
                : asset('images/lomba-literasi-antar-kelas-2026.jpg');
            $popupBtnUrl = $site_settings['popup_btn_url'] ?? 'https://forms.gle/frfZEwZ9x2xwuiTM9';
            $popupWa = $site_settings['popup_wa_number'] ?? '0853465489992';
            $popupCleanWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $popupWa));
        @endphp
        <div class="py-6 bg-gray-50 -mt-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-950 text-white shadow-lg border border-yellow-500/30 p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                        <div class="flex items-start gap-4">
                            @if ($popupImg)
                                <button type="button" onclick="openLiterasiModal()" class="shrink-0 group relative cursor-pointer" title="Perbesar Flyer">
                                    <img src="{{ $popupImg }}" alt="Poster" class="w-16 h-20 sm:w-20 sm:h-24 object-cover rounded-lg border border-yellow-400/40 shadow-sm group-hover:scale-105 transition">
                                    <span class="absolute inset-0 bg-black/30 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white text-xs">
                                        <i class="fas fa-search-plus"></i>
                                    </span>
                                </button>
                            @endif
                            <div class="space-y-1.5">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-yellow-400/20 text-yellow-300 text-xs font-semibold">
                                    <i class="fas fa-bullhorn text-[11px]"></i> {{ $site_settings['popup_badge'] ?? 'Pengumuman Penting' }}
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-white leading-tight">
                                    {{ $site_settings['popup_title'] ?? 'Lomba Literasi Antar Kelas' }}
                                </h2>
                                @if (!empty($site_settings['popup_subtitle']))
                                    <p class="text-xs sm:text-sm text-yellow-100/90 italic line-clamp-1">
                                        &ldquo;{{ $site_settings['popup_subtitle'] }}&rdquo;
                                    </p>
                                @endif
                                @if (!empty($site_settings['popup_deadline']))
                                    <p class="text-xs text-blue-200 flex items-center gap-1.5 font-medium">
                                        <i class="fas fa-clock text-yellow-400"></i> {{ $site_settings['popup_deadline'] }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <a href="{{ $popupBtnUrl }}" target="_blank" rel="noopener noreferrer"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-300 hover:to-amber-400 text-blue-950 font-bold text-xs sm:text-sm shadow transition flex items-center gap-2">
                                <i class="fas fa-edit"></i> {{ $site_settings['popup_btn_text'] ?? 'Daftar Sekarang' }}
                            </a>
                            <button type="button" onclick="openLiterasiModal()"
                                class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium text-xs sm:text-sm transition flex items-center gap-1.5">
                                <i class="fas fa-image text-yellow-400"></i> Lihat Poster
                            </button>
                            @if (!empty($popupWa))
                                <a href="https://wa.me/{{ $popupCleanWa }}?text=Halo,%20saya%20ingin%20bertanya%20mengenai%20{{ urlencode($site_settings['popup_title'] ?? 'Pengumuman') }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-semibold transition flex items-center gap-1.5" title="WhatsApp Panitia">
                                    <i class="fab fa-whatsapp text-sm"></i> WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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

    {{-- Pop-up Modal Interaktif (Kompak & Dinamis) --}}
    @if (($site_settings['popup_active'] ?? '0') == '1')
        @php
            $popupImg = !empty($site_settings['popup_image'])
                ? asset('storage/' . $site_settings['popup_image'])
                : asset('images/lomba-literasi-antar-kelas-2026.jpg');
            $popupBtnUrl = $site_settings['popup_btn_url'] ?? 'https://forms.gle/frfZEwZ9x2xwuiTM9';
            $popupWa = $site_settings['popup_wa_number'] ?? '0853465489992';
            $popupCleanWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $popupWa));
            $popupNewsUrl = $site_settings['popup_news_url'] ?? '';
        @endphp

        {{-- Floating Quick Button (Pojok Kanan Bawah) --}}
        <button type="button" onclick="openLiterasiModal()"
            class="fixed bottom-5 right-5 z-40 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-blue-950 font-bold px-3.5 py-2.5 rounded-full shadow-xl border-2 border-white flex items-center gap-2 transition transform hover:scale-105 cursor-pointer focus:outline-none"
            title="Buka Pengumuman">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
            </span>
            <i class="fas fa-bullhorn text-amber-900 text-sm"></i>
            <span class="text-xs font-bold tracking-wide">Pengumuman</span>
        </button>

        {{-- Pop-up Modal Box (Compact Modal) --}}
        <div id="literasiModal" class="fixed inset-0 z-50 hidden bg-black/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 transition-all duration-300">
            <div class="relative bg-white rounded-2xl max-w-sm sm:max-w-md w-full shadow-2xl border border-gray-100 flex flex-col overflow-hidden" style="max-height: 88vh;">

                {{-- Header Modal --}}
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white px-4 py-2.5 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-yellow-400 text-blue-950 flex items-center justify-center font-bold text-xs shrink-0">
                            <i class="fas fa-bullhorn"></i>
                        </span>
                        <div class="truncate">
                            <h3 class="font-bold text-xs sm:text-sm text-white truncate">{{ $site_settings['popup_title'] ?? 'Pengumuman' }}</h3>
                            <p class="text-[10px] sm:text-[11px] text-blue-200 truncate">{{ $site_settings['popup_badge'] ?? 'Informasi Sekolah' }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeLiterasiModal()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition shrink-0 ml-2" title="Tutup">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>

                {{-- Body Modal (Poster + Action) --}}
                <div class="p-3.5 sm:p-4 overflow-y-auto space-y-3 flex flex-col items-center text-center">
                    {{-- Flyer Image Container --}}
                    @if ($popupImg)
                        <div class="w-full flex justify-center">
                            <a href="{{ $popupImg }}" target="_blank" class="block group relative rounded-xl overflow-hidden shadow-sm border border-gray-200 bg-gray-50" title="Klik untuk memperbesar gambar">
                                <img src="{{ $popupImg }}" alt="Flyer Pengumuman"
                                    class="max-h-56 sm:max-h-64 w-auto object-contain mx-auto transition duration-300 group-hover:scale-102">
                                <span class="absolute inset-0 bg-blue-950/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1">
                                    <i class="fas fa-search-plus"></i> Perbesar
                                </span>
                            </a>
                        </div>
                    @endif

                    {{-- Info Ringkas Batas Waktu --}}
                    @if (!empty($site_settings['popup_deadline']))
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-amber-100 text-amber-900">
                            <i class="fas fa-clock text-amber-600"></i> {{ $site_settings['popup_deadline'] }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="w-full space-y-2 pt-0.5">
                        <a href="{{ $popupBtnUrl }}" target="_blank" rel="noopener noreferrer"
                            class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-300 hover:to-amber-400 text-blue-950 font-extrabold text-center shadow transition flex items-center justify-center gap-2 text-xs sm:text-sm">
                            <i class="fas fa-edit"></i> {{ $site_settings['popup_btn_text'] ?? 'Daftar Sekarang' }}
                        </a>

                        <div class="grid grid-cols-2 gap-2">
                            @if (!empty($popupWa))
                                <a href="https://wa.me/{{ $popupCleanWa }}?text=Halo,%20saya%20ingin%20bertanya%20mengenai%20{{ urlencode($site_settings['popup_title'] ?? 'Pengumuman') }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-center text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fab fa-whatsapp text-sm"></i> WhatsApp
                                </a>
                            @endif

                            @if (!empty($popupNewsUrl))
                                <a href="{{ $popupNewsUrl }}"
                                    class="py-2 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-center text-xs transition flex items-center justify-center gap-1.5 {{ empty($popupWa) ? 'col-span-2' : '' }}">
                                    <i class="fas fa-file-alt text-xs"></i> Rilis Berita
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex items-center justify-between shrink-0">
                    <label class="flex items-center gap-1.5 text-[11px] text-gray-500 cursor-pointer select-none">
                        <input type="checkbox" id="dontShowAgain" class="rounded text-blue-600 focus:ring-blue-500">
                        <span>Jangan tampilkan lagi sesi ini</span>
                    </label>
                    <button type="button" onclick="closeLiterasiModalWithCheckbox()"
                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition">
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
                    }, 500);
                }
            });
        </script>
    @endif
@endsection
