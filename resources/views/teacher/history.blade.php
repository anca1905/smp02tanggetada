@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
    <div class="space-y-4">

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('teacher.history') }}">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-semibold text-gray-700">Tabel Riwayat Presensi</h3>

                    <div class="flex flex-wrap items-center gap-2">
                        <select name="month" onchange="this.form.submit()"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3">Bukti Foto</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Jam Masuk</th>
                            <th scope="col" class="px-6 py-3">Jam Pulang</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($history as $absen)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex -space-x-2 overflow-hidden">
                                        @php
                                            $fotoMasuk = $absen->arrival_photo_url
                                                ? asset($absen->arrival_photo_url)
                                                : 'https://ui-avatars.com/api/?name=In&background=dcfce7&color=166534';
                                            $fotoPulang = $absen->return_photo_url
                                                ? asset($absen->return_photo_url)
                                                : 'https://ui-avatars.com/api/?name=Out&background=dbeafe&color=1e40af';
                                        @endphp
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white"
                                            src="{{ $fotoMasuk }}" alt="Masuk" title="Foto Masuk">
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white"
                                            src="{{ $fotoPulang }}" alt="Pulang" title="Foto Pulang">
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($absen->date)->isoFormat('dddd, D MMMM Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($absen->arrival_time)
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">
                                            {{ \Carbon\Carbon::parse($absen->arrival_time)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if ($absen->return_time)
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                                            {{ \Carbon\Carbon::parse($absen->return_time)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded border border-gray-200">
                                        Hadir
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-calendar-times text-3xl mb-2 text-gray-300"></i>
                                        <p>Belum ada data presensi untuk bulan ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $history->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
