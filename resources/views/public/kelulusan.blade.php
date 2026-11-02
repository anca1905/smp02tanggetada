@extends('layouts.public')

@section('title', 'Portal Pengumuman Kelulusan & SKL Online - ' . ($site_settings['app_name'] ?? 'SIMS'))
@section('header', 'Portal Pengumuman Kelulusan Siswa')
@section('subheader', 'Layanan Mandiri Pengecekan Hasil Kelulusan & Unduh Surat Keterangan Lulus (SKL) Kelas IX')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-start gap-3 shadow-sm">
            <i class="fas fa-exclamation-circle text-red-600 text-lg mt-0.5 shrink-0"></i>
            <div>
                <p class="font-bold text-sm">Pemberitahuan:</p>
                <p class="text-xs sm:text-sm mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- COUNTDOWN BANNER (Jika Ditutup / Menunggu Jadwal) --}}
    @php
        $isClosed = ($status !== '1');
        $releaseTime = $announcementDate ? \Carbon\Carbon::parse($announcementDate) : null;
        if ($isClosed && $releaseTime && now()->gte($releaseTime)) {
            $isClosed = false;
        }
    @endphp

    @if($isClosed)
        <div class="bg-gradient-to-br from-blue-950 via-indigo-900 to-slate-900 rounded-3xl p-8 sm:p-10 text-white text-center shadow-xl border border-blue-800/40 space-y-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold uppercase tracking-wider">
                <i class="fas fa-clock"></i> Hitung Mundur Rilis Pengumuman
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                Pengumuman Kelulusan Belum Dibuka
            </h2>
            <p class="text-blue-200 text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                Hasil kelulusan peserta didik kelas IX SMP Negeri 02 Tanggetada Tahun Ajaran {{ date('Y')-1 }}/{{ date('Y') }} akan diumumkan secara serentak pada jadwal resmi berikut:
            </p>

            @if($releaseTime)
                <div class="p-4 bg-white/10 rounded-2xl inline-block text-amber-300 font-bold text-lg sm:text-xl">
                    <i class="fas fa-calendar-alt mr-2"></i> {{ $releaseTime->translatedFormat('l, d F Y — H:i') }} WITA
                </div>

                {{-- Countdown timer --}}
                <div id="countdown-timer" class="grid grid-cols-4 gap-3 max-w-sm mx-auto pt-2">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10">
                        <span id="cd-days" class="text-2xl sm:text-3xl font-black block">00</span>
                        <span class="text-[10px] sm:text-xs text-blue-200 uppercase font-semibold">Hari</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10">
                        <span id="cd-hours" class="text-2xl sm:text-3xl font-black block">00</span>
                        <span class="text-[10px] sm:text-xs text-blue-200 uppercase font-semibold">Jam</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10">
                        <span id="cd-mins" class="text-2xl sm:text-3xl font-black block">00</span>
                        <span class="text-[10px] sm:text-xs text-blue-200 uppercase font-semibold">Menit</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10">
                        <span id="cd-secs" class="text-2xl sm:text-3xl font-black block">00</span>
                        <span class="text-[10px] sm:text-xs text-blue-200 uppercase font-semibold">Detik</span>
                    </div>
                </div>

                <script>
                (function() {
                    const target = new Date("{{ $releaseTime->toIso8601String() }}").getTime();
                    function update() {
                        const now = new Date().getTime();
                        const diff = target - now;
                        if (diff <= 0) {
                            location.reload();
                            return;
                        }
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const secs = Math.floor((diff % (1000 * 60)) / 1000);
                        document.getElementById('cd-days').textContent = String(days).padStart(2, '0');
                        document.getElementById('cd-hours').textContent = String(hours).padStart(2, '0');
                        document.getElementById('cd-mins').textContent = String(mins).padStart(2, '0');
                        document.getElementById('cd-secs').textContent = String(secs).padStart(2, '0');
                    }
                    setInterval(update, 1000);
                    update();
                })();
                </script>
            @endif
        </div>
    @else
        {{-- FORM CEK KELULUSAN (Status Terbuka) --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-indigo-900 p-6 sm:p-8 text-white text-center">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md text-amber-300 flex items-center justify-center text-3xl mx-auto mb-3 shadow">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2 class="text-2xl font-extrabold">Cek Kelulusan Siswa Kelas IX</h2>
                <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-md mx-auto">
                    Masukkan Nomor Induk Siswa Nasional (NISN) atau NIS yang terdaftar pada sistem sekolah.
                </p>
            </div>

            <form action="{{ route('public.kelulusan.cek') }}" method="POST" class="p-6 sm:p-10 space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Nomor Induk Siswa Nasional (NISN) / NIS <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <input type="text" name="identity" value="{{ old('identity') }}" required autofocus
                               placeholder="Contoh: 0081234567 atau 120001"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-200 text-base font-semibold text-gray-900 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1.5">Dapat menggunakan 10 digit NISN atau nomor NIS sekolah Anda.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Tanggal Lahir Siswa (Verifikasi Tambahan Opsional)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-medium text-gray-800 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-blue-900 to-indigo-800 hover:from-blue-800 hover:to-indigo-700 text-white rounded-2xl font-bold text-base shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Periksa Status Kelulusan
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Petunjuk & Bantuan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-600">
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm space-y-2">
            <div class="text-blue-600 font-bold text-sm flex items-center gap-2">
                <i class="fas fa-shield-alt"></i> Keabsahan SKL
            </div>
            <p class="leading-relaxed">
                Dokumen Surat Keterangan Lulus (SKL) resmi dilengkapi nomor surat keputusan dan barcode verifikasi digital.
            </p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm space-y-2">
            <div class="text-emerald-600 font-bold text-sm flex items-center gap-2">
                <i class="fas fa-print"></i> Format Cetak
            </div>
            <p class="leading-relaxed">
                SKL dapat langsung diunduh dalam format PDF ukuran A4 standar dan sah digunakan untuk mendaftar ke SMA/SMK.
            </p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm space-y-2">
            <div class="text-amber-600 font-bold text-sm flex items-center gap-2">
                <i class="fas fa-headset"></i> Layanan Bantuan
            </div>
            <p class="leading-relaxed">
                Jika data NISN Anda tidak ditemukan, silakan hubungi bagian Tata Usaha (TU) sekolah pada jam kerja.
            </p>
        </div>
    </div>

</div>
@endsection
