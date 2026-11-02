@extends('layouts.app')

@section('title', 'Presensi Guru & GTK (PRO)')

@section('content')
<div class="space-y-6">
    {{-- Header Banner --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 rounded-2xl text-white shadow-lg" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%); background-color: #1e3a8a;">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-amber-400 text-amber-950 text-xs font-bold px-2 py-0.5 rounded shadow">👑 FITUR PRO</span>
                <span class="bg-emerald-500/20 text-emerald-300 text-xs font-semibold px-2 py-0.5 rounded border border-emerald-400/30">AKTIF</span>
            </div>
            <h1 class="text-2xl font-bold">Presensi Harian Guru & GTK</h1>
            <p class="text-blue-200 text-sm mt-1">Pemantauan kehadiran tenaga pendidik & kependidikan secara real-time.</p>
        </div>
        <div>
            <form method="GET" action="{{ route('tu.teacher-attendance.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 bg-white/10 text-white rounded-lg border border-white/20 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    {{-- Tabel Data Kehadiran Guru --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-base">
                Daftar Kehadiran — {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
            </h2>
            <span class="text-xs text-gray-500">Total: {{ $teachers->total() }} Guru & Pegawai</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-gray-700">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5">Nama Guru / GTK</th>
                        <th class="px-6 py-3.5">NIP / Jabatan</th>
                        <th class="px-6 py-3.5">Jam Masuk</th>
                        <th class="px-6 py-3.5">Jam Pulang</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($teachers as $teacher)
                        @php
                            $attendance = $teacher->teacherAttendances->first();
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                                <img src="{{ $teacher->photo_url ? (str_starts_with($teacher->photo_url, 'img/') ? asset($teacher->photo_url) : asset('storage/' . $teacher->photo_url)) : 'https://ui-avatars.com/api/?background=random&color=fff&name=' . urlencode($teacher->name) }}" class="w-8 h-8 rounded-full object-cover border border-gray-200" alt="Avatar">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $teacher->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $teacher->subject ?? 'Guru Mata Pelajaran' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 font-mono">
                                {{ $teacher->nip ?? '-' }}
                                <div class="text-[11px] text-gray-400 font-sans">{{ $teacher->position ?? 'Tenaga Pengajar' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                @if ($attendance && $attendance->arrival_time)
                                    <span class="text-emerald-700 font-semibold">{{ $attendance->arrival_time }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                @if ($attendance && $attendance->return_time)
                                    <span class="text-blue-700 font-semibold">{{ $attendance->return_time }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($attendance && $attendance->arrival_time)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Hadir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        Belum Hadir
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                                Belum ada data guru terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($teachers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
