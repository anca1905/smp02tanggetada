@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="mb-4 sm:mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
            Selamat Datang, {{ explode(' ', $teacher->name)[0] }}
            <svg class="w-6 h-6 inline-block text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.5 2c-.8 0-1.5.7-1.5 1.5v6.5h-1V4.5C10 3.7 9.3 3 8.5 3S7 3.7 7 4.5v6.5h-1V6.5C6 5.7 5.3 5 4.5 5S3 5.7 3 6.5v8C3 18.6 6.4 22 10.5 22h3c4.1 0 7.5-3.4 7.5-7.5V11c0-.8-.7-1.5-1.5-1.5s-1.5.7-1.5 1.5v1h-1V8.5c0-.8-.7-1.5-1.5-1.5s-1.5.7-1.5 1.5V10h-1V3.5c0-.8-.7-1.5-1.5-1.5z"/></svg>
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm">Hari ini tanggal {{ $todayFormatted }}</p>
    </div>

    {{-- Status 3 Sesi Absensi Hari Ini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
        @php
            $sesiBadge = [
                'apel'   => ['icon' => 'fa-sun',        'color_bg' => 'bg-yellow-100', 'color_text' => 'text-yellow-600', 'label' => 'Apel Pagi'],
                'kelas'  => ['icon' => 'fa-chalkboard', 'color_bg' => 'bg-blue-100',   'color_text' => 'text-blue-600',   'label' => 'Di Kelas'],
                'pulang' => ['icon' => 'fa-home',       'color_bg' => 'bg-green-100',  'color_text' => 'text-green-600',  'label' => 'Pulang'],
            ];
        @endphp

        @foreach ($sessionStatus as $key => $sesi)
            <div class="bg-white p-3.5 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 {{ $sesiBadge[$key]['color_bg'] }} rounded-bl-full -mr-2 -mt-2"></div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 relative z-10">
                    Sesi {{ $sesi['label'] }}
                </h3>
                <div class="flex items-center relative z-10">
                    <div class="w-10 h-10 rounded-full {{ $sesiBadge[$key]['color_bg'] }} {{ $sesiBadge[$key]['color_text'] }} flex items-center justify-center mr-3 shrink-0">
                        <i class="fas {{ $sesiBadge[$key]['icon'] }} text-base sm:text-lg"></i>
                    </div>
                    @if ($sesi['done'])
                        <span class="text-xs sm:text-sm font-bold text-green-600">
                            <i class="fas fa-check-circle mr-1"></i> Sudah Dilakukan
                        </span>
                    @else
                        <span class="text-xs sm:text-sm font-medium text-gray-400">Belum ada data</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">

        {{-- Statistik Sesi Kelas Bulan Ini --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-base sm:text-lg font-bold text-gray-700 mb-4">Sesi Absensi Bulan Ini</h3>
            <div class="flex items-center">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 sm:mr-4 shrink-0">
                    <i class="fas fa-clipboard-check text-xl sm:text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-4xl font-bold text-gray-800">{{ $totalSesiKelas }}</p>
                    <p class="text-xs sm:text-sm text-gray-400">Total sesi dilakukan</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('teacher.student-attendance') }}"
                    class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm py-2.5 rounded-lg transition shadow-sm">
                    <i class="fas fa-user-check"></i> Input Absensi Siswa
                </a>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="lg:col-span-2 bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-base sm:text-lg font-bold text-gray-700 mb-4">Aktivitas Absensi Terbaru</h3>
            <div class="space-y-3">
                @forelse ($aktivitas as $log)
                    <div class="flex items-start pb-3 border-b border-gray-50 last:border-0">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mt-0.5">
                            <i class="fas fa-clipboard-check text-xs"></i>
                        </div>
                        <div class="ml-3 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-800">{{ $log->title }}</p>
                            <p class="text-[11px] sm:text-xs text-gray-400">{{ $log->time->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas absensi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Riwayat Sesi Terakhir --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-gray-100">
            <h3 class="text-base sm:text-lg font-bold text-gray-700">Riwayat Sesi Absensi Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-gray-600 min-w-[500px]">
                <thead class="bg-gray-50 text-[11px] sm:text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 whitespace-nowrap">Tanggal</th>
                        <th class="px-3 sm:px-6 py-3">Sesi</th>
                        <th class="px-3 sm:px-6 py-3 text-center">Hadir</th>
                        <th class="px-3 sm:px-6 py-3 text-center">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($riwayat as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 font-medium text-gray-900 whitespace-nowrap">{{ $row['tgl'] }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @php
                                    $sesiColor = match($row['sesi']) {
                                        'Apel Pagi' => 'bg-yellow-100 text-yellow-700',
                                        'Di Kelas'  => 'bg-blue-100 text-blue-700',
                                        'Pulang'    => 'bg-green-100 text-green-700',
                                        default     => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2 sm:px-2.5 py-0.5 rounded-full text-[11px] sm:text-xs font-medium {{ $sesiColor }}">
                                    {{ $row['sesi'] }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-center text-green-600 font-bold">{{ $row['hadir'] }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-center text-gray-500">{{ $row['total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 sm:px-6 py-8 text-center text-gray-400">Belum ada data riwayat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
