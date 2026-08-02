@extends('layouts.app')

@section('title', 'Dashboard TU')

@section('content')
    <div class="bg-blue-600 rounded-xl p-6 text-white shadow-lg mb-6 relative overflow-hidden ">
        <h2 class="text-2xl font-bold">Welcome Back, {{ $operator->name ?? '-' }} 👋</h2>
        <p class="text-blue-100 mt-1">Berikut adalah ringkasan aktivitas administrasi hari ini.</p>
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-500 to-transparent opacity-50"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Absen Datang</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['datang'] }} <span
                        class="text-xs text-gray-400 font-normal">/ {{ $stats['total_guru'] }}</span></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Absen Pulang</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pulang'] }} <span
                        class="text-xs text-gray-400 font-normal">/ {{ $stats['total_guru'] }}</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_siswa'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Ruang Dipinjam</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_ruang'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Statistik Peminjaman Ruangan</h3>
                <select class="text-sm border-gray-300 rounded-lg shadow-sm">
                    <option>2024</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="room-chart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @foreach ($aktivitas as $log)
                    <div class="flex items-start pb-4 border-b border-gray-50 last:border-0">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $log->judul }}</p>
                            <p class="text-xs text-gray-500">{{ $log->waktu->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Kehadiran Guru (Minggu Ini)</h3>
            <div class="h-64">
                <canvas id="teacher-chart-datang"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Jumlah Siswa per Kelas</h3>
            <div class="h-64">
                <canvas id="student-chart"></canvas>
            </div>
        </div>
    </div>

    {{-- PPDB Widget --}}
    @php
        $ppdbPending  = \App\Models\Ppdb::where('status_pendaftaran', 'Pending')->count();
        $ppdbTotal    = \App\Models\Ppdb::count();
        $ppdbAccepted = \App\Models\Ppdb::where('status_pendaftaran', 'Accepted')->count();
    @endphp
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <h3 class="font-bold text-gray-800">PPDB Online — Pendaftar Masuk</h3>
            </div>
            <a href="{{ route('tu.ppdb.index') }}" class="text-sm text-blue-600 font-bold hover:underline">
                Kelola Semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-3 divide-x divide-gray-100">
            <div class="px-6 py-5 text-center">
                <p class="text-3xl font-bold text-gray-800">{{ $ppdbTotal }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold mt-1">Total Pendaftar</p>
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-3xl font-bold text-yellow-600">{{ $ppdbPending }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold mt-1">Menunggu Verifikasi</p>
                @if ($ppdbPending > 0)
                    <span class="inline-block mt-2 text-xs bg-yellow-100 text-yellow-700 font-bold px-2 py-0.5 rounded-full animate-pulse">
                        Perlu Tindakan
                    </span>
                @endif
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-3xl font-bold text-green-600">{{ $ppdbAccepted }}</p>
                <p class="text-xs text-gray-400 uppercase font-semibold mt-1">Diterima</p>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const ctxRoom = document.getElementById('room-chart').getContext('2d');
            new Chart(ctxRoom, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                        'Des'
                    ],
                    datasets: [{
                        label: 'Peminjaman',
                        data: @json($roomChartData),
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            const ctxTeacher = document.getElementById('teacher-chart-datang').getContext('2d');
            new Chart(ctxTeacher, {
                type: 'line',
                data: {
                    labels: @json($teacherChartLabels),
                    datasets: [{
                        label: 'Guru Hadir',
                        data: @json($teacherChartData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            const ctxStudent = document.getElementById('student-chart').getContext('2d');
            new Chart(ctxStudent, {
                type: 'doughnut',
                data: {
                    labels: @json($studentChartLabels),
                    datasets: [{
                        data: @json($studentChartData),
                        backgroundColor: [
                            '#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899',
                            '#6366f1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
@endpush
