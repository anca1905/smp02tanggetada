@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="space-y-6">

    {{-- ── Greeting Banner ───────────────────────────────────────────────── --}}
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-800 rounded-2xl p-4 sm:p-6 text-white shadow-lg overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-full opacity-10">
            <svg viewBox="0 0 200 200" class="w-full h-full" fill="white">
                <circle cx="150" cy="50" r="80"/><circle cx="50" cy="150" r="60"/>
            </svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs sm:text-sm font-medium">Selamat Datang,</p>
                <h1 class="text-xl sm:text-2xl font-bold mt-1">{{ Auth::guard('operator')->user()->name }}</h1>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-2">
                    <span class="bg-white/20 text-white text-xs font-semibold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full">{{ Auth::guard('operator')->user()->role_operator }}</span>
                    <span class="text-blue-200 text-xs sm:text-sm"><i class="fas fa-calendar mr-1"></i>{{ $today->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-blue-200 text-xs">Sistem Informasi Manajemen Sekolah</p>
                <p class="text-white font-bold text-lg">SIMS v2.0</p>
            </div>
        </div>
    </div>

    {{-- ── KPI Cards Row ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">

        {{-- Total Guru --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-chalkboard-teacher text-blue-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium truncate">Jumlah Guru</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalGuru }}</p>
                <p class="text-[11px] sm:text-xs text-blue-500 font-medium">Aktif</p>
            </div>
        </div>

        {{-- Total Siswa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-graduate text-green-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium truncate">Jumlah Siswa</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
                <p class="text-[11px] sm:text-xs text-green-500 font-medium">Aktif</p>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-chalkboard text-orange-500 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium truncate">Jumlah Kelas</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalKelas }}</p>
                <p class="text-[11px] sm:text-xs text-orange-500 font-medium">Rombel</p>
            </div>
        </div>

        {{-- Kehadiran Siswa Hari Ini --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $kehadiranSiswaHariIni >= 80 ? 'bg-teal-50' : 'bg-red-50' }}">
                <i class="fas fa-user-check text-lg sm:text-xl {{ $kehadiranSiswaHariIni >= 80 ? 'text-teal-600' : 'text-red-500' }}"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium truncate">Kehadiran Siswa</p>
                <p class="text-xl sm:text-2xl font-bold {{ $kehadiranSiswaHariIni >= 80 ? 'text-teal-600' : 'text-red-500' }}">{{ $kehadiranSiswaHariIni }}%</p>
                <p class="text-[11px] sm:text-xs text-gray-400">Hari ini</p>
            </div>
        </div>

        {{-- Guru Hadir Hari Ini --}}
        <div class="col-span-2 sm:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-indigo-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium truncate">Guru Hadir</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $guruHadir }}<span class="text-xs sm:text-sm text-gray-400 font-normal">/{{ $totalGuru }}</span></p>
                @if($guruTerlambat > 0)
                    <p class="text-[11px] sm:text-xs text-red-500 font-medium"><i class="fas fa-arrow-down mr-1"></i>{{ $guruTerlambat }} Terlambat</p>
                @else
                    <p class="text-[11px] sm:text-xs text-green-500 font-medium">Hari ini</p>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Row 2: Attendance Chart + Top Teachers ─────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Attendance Line Chart --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Persentase Kehadiran Siswa</h3>
                    <p class="text-xs text-gray-500">6 Bulan Terakhir</p>
                </div>
                <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">6 Bulan</span>
            </div>
            <canvas id="attendanceChart" height="90"></canvas>
        </div>

        {{-- Top 5 Active Teachers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-800">Top Guru Paling Aktif</h3>
                <a href="{{ route('tu.teacher.index') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($topTeachers as $idx => $teacher)
                @php
                    $maxHadir = $topTeachers->max('hadir_count') ?: 1;
                    $pct = $maxHadir > 0 ? round(($teacher->hadir_count / $maxHadir) * 100) : 0;
                    $colors = ['bg-blue-600','bg-green-500','bg-orange-500','bg-purple-500','bg-red-500'];
                    $color = $colors[$idx] ?? 'bg-gray-400';
                @endphp
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-400 w-4">{{ $idx+1 }}</span>
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-xs font-bold text-blue-800">
                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $teacher->name }}</p>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                            <div class="{{ $color }} h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold {{ $color === 'bg-blue-600' ? 'text-blue-600' : 'text-gray-600' }}">{{ $teacher->hadir_count }}x</span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada data kehadiran guru.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── Row 3: Guru Hari Ini + Announcements + Events ──────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Teacher Attendance Donut --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-base font-bold text-gray-800 mb-5">Kehadiran Guru Hari Ini</h3>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
                <div class="relative">
                    <canvas id="guruDonut" width="120" height="120"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-gray-800">{{ $guruHadir }}</span>
                        <span class="text-xs text-gray-400">Hadir</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-600 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-gray-500">Hadir</p>
                            <p class="text-sm font-bold text-gray-800">{{ $guruHadir }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-400 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-gray-500">Terlambat</p>
                            <p class="text-sm font-bold text-gray-800">{{ $guruTerlambat }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gray-200 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-gray-500">Belum Hadir</p>
                            <p class="text-sm font-bold text-gray-800">{{ $totalGuru - $guruHadir }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-gray-100">
                @php $guruPct = $totalGuru > 0 ? round(($guruHadir / $totalGuru) * 100) : 0; @endphp
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs text-gray-500">Persentase Kehadiran</span>
                    <span class="text-xs font-bold {{ $guruPct >= 80 ? 'text-green-600' : 'text-red-500' }}">{{ $guruPct }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="{{ $guruPct >= 80 ? 'bg-green-500' : 'bg-red-400' }} h-2 rounded-full" style="width: {{ $guruPct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Latest Announcements --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-800">Pengumuman Terbaru</h3>
                <a href="{{ route('tu.posts.index') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($announcements as $post)
                <div class="flex gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-bullhorn text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 leading-tight truncate">{{ $post->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">{{ $post->category }}</span>
                            <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Belum ada pengumuman.</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Events / Agenda --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-800">Agenda Sekolah</h3>
                <a href="{{ route('tu.events.index') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($events as $event)
                @php
                    $eventColors = ['bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-green-100 text-green-700', 'bg-orange-100 text-orange-700'];
                    $ec = $eventColors[$loop->index % 4];
                @endphp
                <div class="flex gap-3 items-start">
                    <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl {{ $ec }} flex-shrink-0 text-center">
                        <span class="text-xs font-bold leading-none">{{ $event->start_date->format('d') }}</span>
                        <span class="text-xs leading-none">{{ $event->start_date->format('M') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $event->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $event->location ?? 'Lokasi tidak ditentukan' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Tidak ada agenda mendatang.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── Row 4: Student Per Class Bar Chart + Financial Summary ─────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Student per Class Chart --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Distribusi Siswa per Kelas</h3>
                    <p class="text-xs text-gray-500">Jumlah siswa aktif di setiap rombongan belajar</p>
                </div>
            </div>
            <canvas id="studentClassChart" height="80"></canvas>
        </div>

        {{-- Quick Stats --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-base font-bold text-gray-800 mb-5">Statistik Ringkasan</h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <i class="fas fa-book text-blue-600 mb-1"></i>
                    <p class="text-xl font-bold text-blue-700">{{ $totalMapel }}</p>
                    <p class="text-xs text-blue-600">Mata Pelajaran</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <i class="fas fa-chalkboard text-green-600 mb-1"></i>
                    <p class="text-xl font-bold text-green-700">{{ $totalKelas }}</p>
                    <p class="text-xs text-green-600">Total Kelas</p>
                </div>
                <div class="bg-orange-50 rounded-xl p-3 text-center">
                    <i class="fas fa-building text-orange-500 mb-1"></i>
                    <p class="text-xl font-bold text-orange-600">{{ $totalFasilitas }}</p>
                    <p class="text-xs text-orange-500">Fasilitas</p>
                </div>
                <div class="bg-indigo-50 rounded-xl p-3 text-center">
                    <i class="fas fa-users text-indigo-600 mb-1"></i>
                    <p class="text-xl font-bold text-indigo-700">{{ $totalGuru }}</p>
                    <p class="text-xs text-indigo-600">Total Guru</p>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-700 mb-3">Ringkasan Keuangan</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 flex items-center gap-1.5"><i class="fas fa-check-circle text-green-500"></i>Terbayar</span>
                        <span class="text-sm font-bold text-green-600">Rp{{ number_format($totalTerbayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 flex items-center gap-1.5"><i class="fas fa-exclamation-circle text-red-500"></i>Belum Dibayar</span>
                        <span class="text-sm font-bold text-red-500">Rp{{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
                <a href="{{ route('tu.billing.index') }}" class="mt-3 block text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 rounded-lg transition-colors">
                    Kelola Keuangan <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── Attendance Line Chart ─────────────────────────────────────────────
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'Kehadiran Siswa (%)',
                data: @json($attendanceChart),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#3B82F6',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.y}%`
                    }
                }
            },
            scales: {
                y: {
                    min: 0, max: 100,
                    ticks: { callback: v => v + '%', font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });

    // ── Teacher Donut Chart ───────────────────────────────────────────────
    const guruDonutCtx = document.getElementById('guruDonut').getContext('2d');
    const guruHadir    = {{ $guruHadir }};
    const guruLate     = {{ $guruTerlambat }};
    const guruAbsent   = {{ $totalGuru - $guruHadir }};
    new Chart(guruDonutCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [guruHadir, guruLate, guruAbsent > 0 ? guruAbsent : 0],
                backgroundColor: ['#3B82F6', '#F97316', '#E5E7EB'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: false,
            cutout: '72%',
            plugins: { legend: { display: false }, tooltip: { enabled: true } }
        }
    });

    // ── Student Per Class Bar Chart ───────────────────────────────────────
    const classCtx = document.getElementById('studentClassChart').getContext('2d');
    new Chart(classCtx, {
        type: 'bar',
        data: {
            labels: @json($studentPerClass->pluck('name')),
            datasets: [{
                label: 'Jumlah Siswa',
                data: @json($studentPerClass->pluck('students_count')),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: '#3B82F6',
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: 'rgba(59, 130, 246, 0.9)',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: { font: { size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
