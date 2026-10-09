@extends('layouts.app')

@section('title', 'Dashboard TU')

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
                <h1 class="text-xl sm:text-2xl font-bold mt-1 flex items-center gap-2">
                    {{ $operator->name ?? 'Admin TU' }}
                    <svg class="w-6 h-6 inline-block text-amber-300 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.5 2c-.8 0-1.5.7-1.5 1.5v6.5h-1V4.5C10 3.7 9.3 3 8.5 3S7 3.7 7 4.5v6.5h-1V6.5C6 5.7 5.3 5 4.5 5S3 5.7 3 6.5v8C3 18.6 6.4 22 10.5 22h3c4.1 0 7.5-3.4 7.5-7.5V11c0-.8-.7-1.5-1.5-1.5s-1.5.7-1.5 1.5v1h-1V8.5c0-.8-.7-1.5-1.5-1.5s-1.5.7-1.5 1.5V10h-1V3.5c0-.8-.7-1.5-1.5-1.5z"/></svg>
                </h1>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-2">
                    <span class="bg-white/20 text-white text-xs font-semibold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full">
                        <i class="fas fa-user-cog mr-1"></i>{{ $operator->role_operator ?? 'Tata Usaha' }}
                    </span>
                    <span class="text-blue-200 text-xs sm:text-sm">
                        <i class="fas fa-calendar mr-1"></i>{{ now()->isoFormat('dddd, D MMMM YYYY') }}
                    </span>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-blue-200 text-xs">Sistem Informasi Manajemen Sekolah</p>
                <p class="text-white font-bold text-lg">SIMS v2.0</p>
            </div>
        </div>
    </div>

    {{-- ── 4 Kartu Statistik Utama (Gaya Kepsek) ──────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        {{-- Total Guru --}}
        <a href="{{ route('tu.teacher.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <i class="fas fa-chalkboard-teacher text-blue-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Guru & Pegawai</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_guru'] }}</p>
                <p class="text-[11px] sm:text-xs text-blue-500 font-medium">PTK Terdaftar</p>
            </div>
        </a>

        {{-- Total Siswa --}}
        <a href="{{ route('tu.student.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <i class="fas fa-user-graduate text-green-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Siswa Aktif</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_siswa'] }}</p>
                <p class="text-[11px] sm:text-xs text-green-500 font-medium">Siswa Terdaftar</p>
            </div>
        </a>

        {{-- Total Kelas / Rombel --}}
        <a href="{{ route('tu.classrooms.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <i class="fas fa-chalkboard text-orange-500 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Rombongan Belajar</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_kelas'] }}</p>
                <p class="text-[11px] sm:text-xs text-orange-500 font-medium">Rombel Aktif</p>
            </div>
        </a>

        {{-- Agenda Mendatang --}}
        <a href="{{ route('tu.events.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 flex items-center gap-2.5 sm:gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                <i class="fas fa-calendar-check text-purple-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium leading-tight">Agenda Mendatang</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_agenda'] }}</p>
                <p class="text-[11px] sm:text-xs text-purple-600 font-medium">Kegiatan Sekolah</p>
            </div>
        </a>
    </div>

    {{-- ── Baris 2: Grafik Distribusi Siswa per Kelas & Aktivitas Terbaru ─────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Distribusi Siswa per Kelas</h3>
                    <p class="text-xs text-gray-500">Jumlah siswa aktif di setiap rombongan belajar</p>
                </div>
                <a href="{{ route('tu.classrooms.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">
                    Kelola Kelas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="h-64">
                <canvas id="student-bar-chart"></canvas>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-history text-gray-400"></i> Aktivitas Terbaru
                </h3>
                <span class="text-xs text-gray-400">Log Sistem</span>
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                @forelse ($aktivitas as $log)
                    <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-gray-50 transition-colors border border-gray-50 last:border-0">
                        <div class="shrink-0 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mt-0.5">
                            <i class="fas fa-bell text-blue-500 text-xs"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate">{{ $log->judul }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $log->waktu->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Baris 3: Agenda Sekolah & Proporsi Siswa ───────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        {{-- Agenda Sekolah Mendatang --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Agenda & Kegiatan Mendatang</h3>
                    <p class="text-xs text-gray-500">Jadwal kegiatan akademik dan libur sekolah</p>
                </div>
                <a href="{{ route('tu.events.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">
                    Semua Agenda <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($upcomingEvents as $evt)
                    @php
                        $colorClass = $evt->type === 'holiday' ? 'bg-red-50 text-red-700 border-red-200' : ($evt->type === 'academic' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-amber-50 text-amber-700 border-amber-200');
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50/80 transition-all gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="text-center px-3 py-1.5 bg-gray-100 rounded-lg shrink-0">
                                <span class="block text-xs font-bold text-gray-800">{{ $evt->start_date ? $evt->start_date->format('d') : '-' }}</span>
                                <span class="block text-[10px] text-gray-500 uppercase">{{ $evt->start_date ? $evt->start_date->format('M') : '' }}</span>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('tu.events.show', $evt->id) }}" class="text-sm font-semibold text-gray-800 hover:text-blue-600 truncate block">
                                    {{ $evt->title }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $evt->start_date ? $evt->start_date->isoFormat('D MMMM Y') : '-' }}
                                    @if($evt->end_date && $evt->end_date != $evt->start_date)
                                        s.d {{ $evt->end_date->isoFormat('D MMMM Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold shrink-0 {{ $colorClass }}">
                            {{ $evt->type === 'holiday' ? 'Libur' : ($evt->type === 'academic' ? 'Akademik' : 'Kegiatan') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <i class="far fa-calendar-times text-2xl mb-2 text-gray-300 block"></i>
                        Tidak ada agenda mendatang.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Proporsi Siswa per Kelas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Proporsi Siswa per Kelas</h3>
                <p class="text-xs text-gray-500 mb-4">Persentase jumlah siswa di setiap kelas</p>
                <div class="h-60">
                    <canvas id="student-chart"></canvas>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-center">
                <span class="text-xs text-gray-400">Total Siswa Terdaftar: <strong>{{ $stats['total_siswa'] }} Siswa</strong></span>
            </div>
        </div>
    </div>

    {{-- ── Baris 4: PPDB Online Summary ──────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between px-4 sm:px-6 py-4 gap-2 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-user-plus text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">PPDB Online — Penerimaan Peserta Didik Baru</h3>
                    <p class="text-xs text-gray-500">Pendaftaran dan verifikasi berkas calon siswa baru</p>
                </div>
            </div>
            <a href="{{ route('tu.ppdb.index') }}" class="text-xs sm:text-sm text-blue-600 font-bold hover:underline">
                Kelola Semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
            <div class="px-4 py-4 sm:px-6 sm:py-5 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $ppdbTotal }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold mt-1">Total Pendaftar</p>
            </div>
            <div class="px-4 py-4 sm:px-6 sm:py-5 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-amber-600">{{ $ppdbPending }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold mt-1">Menunggu Verifikasi</p>
                @if ($ppdbPending > 0)
                    <span class="inline-block mt-2 text-xs bg-amber-50 text-amber-700 font-bold px-2.5 py-0.5 rounded-full border border-amber-200">
                        Perlu Tindakan
                    </span>
                @endif
            </div>
            <div class="px-4 py-4 sm:px-6 sm:py-5 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-emerald-600">{{ $ppdbAccepted }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold mt-1">Diterima</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bar Chart Distribusi Siswa per Kelas
            const ctxBar = document.getElementById('student-bar-chart');
            if (ctxBar) {
                new Chart(ctxBar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($studentChartLabels),
                        datasets: [{
                            label: 'Jumlah Siswa',
                            data: @json($studentChartData),
                            backgroundColor: 'rgba(59, 130, 246, 0.75)',
                            borderColor: '#3B82F6',
                            borderWidth: 1,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(59, 130, 246, 0.95)',
                            maxBarThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
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
            }

            // Doughnut Chart Proporsi Siswa per Kelas
            const ctxStudent = document.getElementById('student-chart');
            if (ctxStudent) {
                new Chart(ctxStudent.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($studentChartLabels),
                        datasets: [{
                            data: @json($studentChartData),
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6',
                                '#EC4899', '#06B6D4', '#64748B', '#EF4444'
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 12, font: { size: 11 }, padding: 12 }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
