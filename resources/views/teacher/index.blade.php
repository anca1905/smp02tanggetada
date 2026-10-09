@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">

    {{-- ── Greeting Banner ───────────────────────────────────────────────── --}}
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-800 rounded-2xl p-4 sm:p-6 text-white shadow-lg overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-full opacity-10 pointer-events-none">
            <svg viewBox="0 0 200 200" class="w-full h-full" fill="white">
                <circle cx="150" cy="50" r="80"/><circle cx="50" cy="150" r="60"/>
            </svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs sm:text-sm font-medium">Selamat Datang,</p>
                <h1 class="text-xl sm:text-2xl font-bold mt-1">{{ $teacher->name }}</h1>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-2">
                    <span class="bg-white/20 text-white text-xs font-semibold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full">
                        <i class="fas fa-chalkboard-teacher mr-1"></i>Guru Pengajar
                    </span>
                    @if($waliKelasName)
                        <span class="bg-emerald-500/80 text-white text-xs font-semibold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full">
                            <i class="fas fa-user-shield mr-1"></i>Wali Kelas {{ $waliKelasName }}
                        </span>
                    @endif
                    <span class="text-blue-200 text-xs sm:text-sm">
                        <i class="fas fa-calendar mr-1"></i>{{ $today->isoFormat('dddd, D MMMM YYYY') }}
                    </span>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-blue-200 text-xs">Sistem Informasi Manajemen Sekolah</p>
                <p class="text-white font-bold text-lg">SIMS v2.0</p>
            </div>
        </div>
    </div>

    {{-- ── KPI Cards Row (4 Kartu Elegan) ────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        {{-- Kelas Diampu --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <i class="fas fa-chalkboard text-blue-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Kelas Diampu</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalKelasDiampu }}</p>
                <p class="text-[11px] sm:text-xs text-blue-500 font-medium">Rombongan Belajar</p>
            </div>
        </div>

        {{-- Total Siswa Diajar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <i class="fas fa-user-graduate text-green-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Siswa Diajar</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalSiswaDiajar }}</p>
                <p class="text-[11px] sm:text-xs text-green-500 font-medium">Siswa Terdaftar</p>
            </div>
        </div>

        {{-- Beban Mengajar / Minggu --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <i class="fas fa-clock text-orange-500 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Beban Mengajar</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalJamMengajar }}</p>
                <p class="text-[11px] sm:text-xs text-orange-500 font-medium">Sesi / Minggu</p>
            </div>
        </div>

        {{-- Peran / Wali Kelas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                <i class="fas fa-id-badge text-purple-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Status Peran</p>
                <p class="text-base sm:text-lg font-bold text-gray-800 truncate">{{ $waliKelasName ? 'Wali '.$waliKelasName : 'Guru Mapel' }}</p>
                <p class="text-[11px] sm:text-xs text-purple-600 font-medium truncate">{{ $waliKelasName ? $totalSiswaWaliKelas.' Siswa Kelas' : 'Pengajar Reguler' }}</p>
            </div>
        </div>

    </div>

    {{-- ── Row 2: Jadwal Mengajar Hari Ini & Grafik Beban Mengajar ────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Jadwal Mengajar Hari Ini --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-day text-blue-600"></i> Jadwal Mengajar Hari Ini
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Jadwal tatap muka hari {{ $todayIndo }}, {{ $today->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $todaySchedules->count() }} Sesi Pelajaran
                    </span>
                    <a href="{{ route('teacher.student-attendance') }}" class="text-xs text-blue-600 font-medium hover:underline">
                        Input Absensi <i class="fas fa-arrow-right ml-0.5"></i>
                    </a>
                </div>
            </div>

            @if($todaySchedules->isNotEmpty())
                <div class="space-y-3">
                    @foreach($todaySchedules as $sch)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-blue-50/40 hover:border-blue-100 transition-all gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-blue-100/70 text-blue-700 flex flex-col items-center justify-center shrink-0 font-bold">
                                    <span class="text-xs">{{ substr($sch->start_time, 0, 5) }}</span>
                                    <span class="text-[10px] text-blue-500 font-normal">s.d {{ substr($sch->end_time, 0, 5) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-blue-600 text-white">
                                            {{ $sch->classroom->name ?? 'Kelas' }}
                                        </span>
                                        <h4 class="text-sm font-bold text-gray-800 truncate">
                                            {{ $sch->subject->name ?? 'Mata Pelajaran' }}
                                        </h4>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                        <span><i class="fas fa-clock text-gray-400 mr-1"></i>{{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }} WITA</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 sm:self-center shrink-0">
                                <a href="{{ route('teacher.student-attendance') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                    <i class="fas fa-user-check"></i> Presensi
                                </a>
                                <a href="{{ route('teacher.lms.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 text-xs font-semibold rounded-lg transition-colors">
                                    <i class="fas fa-book-open text-blue-600"></i> Materi
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 px-4 bg-gray-50/60 rounded-xl border border-dashed border-gray-200">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-mug-hot text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Tidak ada jadwal tatap muka hari {{ $todayIndo }}</p>
                    <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1">Anda tidak memiliki jam pelajaran hari ini. Gunakan waktu luang untuk menyiapkan materi LMS atau merekap nilai siswa.</p>
                    <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                        <a href="{{ route('teacher.student-attendance') }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg shadow-sm transition">
                            <i class="fas fa-user-check mr-1"></i> Input Presensi Siswa
                        </a>
                        <a href="{{ route('teacher.lms.index') }}" class="px-3.5 py-1.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 text-xs font-medium rounded-lg transition">
                            <i class="fas fa-book mr-1"></i> Kelola Materi (LMS)
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Beban Mengajar Mingguan (Chart) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Beban Mengajar</h3>
                        <p class="text-xs text-gray-500">Sesi per hari (Senin - Sabtu)</p>
                    </div>
                    <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                        {{ array_sum($weeklyScheduleData) }} Jam
                    </span>
                </div>
                <div class="h-48">
                    <canvas id="teacherWeeklyChart"></canvas>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500 flex items-center justify-between">
                <span>Total Jadwal Aktif:</span>
                <span class="font-bold text-gray-800">{{ $totalJamMengajar }} Pertemuan/Minggu</span>
            </div>
        </div>

    </div>

    {{-- ── Row 3: Rombel yang Diampu & Pengumuman Sekolah ────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Daftar Kelas yang Diampu --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-school text-blue-600"></i> Rombongan Belajar yang Diampu
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar kelas tempat Anda mengajar mata pelajaran</p>
                </div>
                <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2.5 py-1 rounded-full">
                    {{ $taughtClassrooms->count() }} Rombel
                </span>
            </div>

            @if($taughtClassrooms->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($taughtClassrooms as $cls)
                        <div class="p-3.5 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/20 transition-all flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ substr($cls->name, 0, 4) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-gray-800 truncate">{{ $cls->name }}</h4>
                                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                        <i class="fas fa-users text-gray-400"></i>
                                        <span>{{ $cls->students_count }} Siswa Aktif</span>
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('teacher.student-attendance') }}" class="px-2.5 py-1 bg-white hover:bg-blue-600 hover:text-white border border-gray-200 hover:border-blue-600 text-gray-600 text-xs font-medium rounded-lg transition-colors shrink-0">
                                Absensi <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 text-center py-6">Belum ada kelas yang terhubung dengan jadwal mengajar Anda.</p>
            @endif

            {{-- Pintasan Akses Cepat Guru --}}
            <div class="mt-5 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pintasan Fitur Guru</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <a href="{{ route('teacher.student-attendance') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-medium transition">
                        <i class="fas fa-user-check text-blue-600"></i>
                        <span>Input Presensi</span>
                    </a>
                    <a href="{{ route('teacher.lms.index') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-medium transition">
                        <i class="fas fa-book-reader text-emerald-600"></i>
                        <span>Materi & LMS</span>
                    </a>
                    @if($waliKelasName)
                        <a href="{{ route('teacher.promotion') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-medium transition">
                            <i class="fas fa-graduation-cap text-orange-600"></i>
                            <span>Kenaikan Kelas</span>
                        </a>
                    @else
                        <a href="{{ route('teacher.settings') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-medium transition">
                            <i class="fas fa-cog text-purple-600"></i>
                            <span>Profil & Akun</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pengumuman Terbaru --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Pengumuman Sekolah</h3>
                <span class="text-xs text-blue-600 font-medium">Terbaru</span>
            </div>
            <div class="space-y-3">
                @forelse($announcements as $post)
                    <div class="flex gap-3 p-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-bullhorn text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-gray-800 leading-tight truncate">{{ $post->title }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded font-medium">{{ $post->category }}</span>
                                <span class="text-[10px] text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada pengumuman terbaru.</p>
                @endforelse
            </div>

            @if($events->isNotEmpty())
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-700 mb-2">Agenda Terdekat</p>
                    <div class="space-y-2">
                        @foreach($events as $evt)
                            <div class="flex items-center gap-2.5 text-xs text-gray-600">
                                <div class="px-2 py-1 rounded bg-purple-50 text-purple-700 font-bold shrink-0 text-center text-[10px]">
                                    {{ $evt->start_date ? $evt->start_date->format('d M') : '-' }}
                                </div>
                                <span class="truncate font-medium text-gray-800">{{ $evt->title }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const weeklyCtx = document.getElementById('teacherWeeklyChart');
        if (weeklyCtx) {
            new Chart(weeklyCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($weeklyDays),
                    datasets: [{
                        label: 'Sesi Mengajar',
                        data: @json($weeklyScheduleData),
                        backgroundColor: 'rgba(59, 130, 246, 0.75)',
                        borderColor: '#3B82F6',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(59, 130, 246, 0.95)',
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.parsed.y} Sesi Pelajaran`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { size: 10 } },
                            grid: { color: 'rgba(0,0,0,0.04)' }
                        },
                        x: {
                            ticks: { font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
