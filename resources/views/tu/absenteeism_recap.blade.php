@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')
    <div class="h-full flex flex-col">

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 p-4">
            <form method="GET" action="{{ route('tu.rekap') }}">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-end md:items-center">

                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                            <select name="kategori" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-40">
                                <option value="teacher" {{ $kategori == 'teacher' ? 'selected' : '' }}>teacher</option>
                                <option value="student" {{ $kategori == 'student' ? 'selected' : '' }}>student</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                            <select name="bulan" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-40">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        @if ($kategori == 'student')
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
                                <select name="kelas" onchange="this.form.submit()"
                                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-32">
                                    <option value="">Semua</option>
                                    @foreach (['7', '8', '9', '10', '11', '12'] as $k)
                                        <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                                            Kelas {{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="button" onclick="window.print()"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            <i class="fas fa-print mr-2"></i> Print
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex-1 flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    Data Absensi {{ ucfirst($kategori) }} - Bulan
                    {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-gray-600 font-medium border-b border-gray-200 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Nama Lengkap</th>

                            @if ($kategori == 'teacher')
                                <th class="px-6 py-3">Jam Datang</th>
                                <th class="px-6 py-3">Jam Pulang</th>
                                <th class="px-6 py-3">Status</th>
                            @else
                                <th class="px-6 py-3">Kelas</th>
                                <th class="px-6 py-3">Status Kehadiran</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($kategori == 'teacher' ? $item->date : $item->attendance->date)->isoFormat('dddd, D MMM Y') }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $kategori == 'teacher' ? $item->teacher->name : $item->student->student_name }}
                                </td>

                                @if ($kategori == 'teacher')
                                    <td class="px-6 py-4 text-blue-600 font-mono">
                                        {{ $item->arrival_time ? \Carbon\Carbon::parse($item->arrival_time)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-green-600 font-mono">
                                        {{ $item->return_time ? \Carbon\Carbon::parse($item->return_time)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Hadir
                                        </span>
                                    </td>
                                @else
                                    <td class="px-6 py-4">{{ $item->student->class }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $color = match (strtolower($item->student_status)) {
                                                'hadir' => 'bg-green-100 text-green-800',
                                                'sakit' => 'bg-yellow-100 text-yellow-800',
                                                'izin' => 'bg-blue-100 text-blue-800',
                                                'alpa' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst($item->student_status) }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-folder-open text-4xl text-gray-300 mb-2"></i>
                                        <p>Belum ada data absensi untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $data->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
