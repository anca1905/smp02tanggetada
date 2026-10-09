@extends('layouts.app')

@section('title', 'Ruang Mengajar')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 text-white shadow-lg">
            <h2 class="text-2xl font-bold">Kelas Saya ({{ $activeYear->name ?? '-' }})</h2>
            <p class="text-blue-100 mt-1">Kelola materi dan tugas untuk siswa Anda di sini.</p>
        </div>

        {{-- Grid Kelas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myClasses as $key => $schedules)
                @php
                    // Ambil data pertama dari grup untuk info kartu
                    $data = $schedules->first();
                    // Hitung total jam/pertemuan dalam seminggu
                    $totalHours = $schedules->count();
                    $theme = $data->subject?->theme ?? [
                        'gradient' => 'from-blue-600 to-indigo-800',
                        'icon' => 'fas fa-graduation-cap',
                    ];
                @endphp

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                    <div class="h-32 bg-gradient-to-br {{ $theme['gradient'] }} relative overflow-hidden p-4 flex flex-col justify-between">
                        {{-- Watermark Icon --}}
                        <div class="absolute -right-3 -bottom-4 text-white/15 text-8xl pointer-events-none transform group-hover:scale-110 group-hover:rotate-6 transition duration-300">
                            <i class="{{ $theme['icon'] }}"></i>
                        </div>
                        <div class="absolute -left-6 -top-6 w-24 h-24 rounded-full bg-white/10 pointer-events-none"></div>

                        <div class="flex items-center justify-between relative z-10">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/20 text-white backdrop-blur-sm shadow-sm">
                                <i class="{{ $theme['icon'] }} text-sm"></i>
                            </span>
                            <span
                                class="bg-white/95 backdrop-blur text-xs font-bold px-2.5 py-1 rounded-full text-gray-800 shadow-sm">
                                {{ $data->classroom->name }}
                            </span>
                        </div>

                        <div class="relative z-10 text-white">
                            <span class="text-[11px] font-semibold tracking-wider uppercase px-2 py-0.5 rounded bg-black/20 backdrop-blur-sm">
                                {{ $data->subject->code }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-lg text-gray-800 mb-1 line-clamp-1" title="{{ $data->subject->name }}">
                            {{ $data->subject->name }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">{{ $data->subject->code }}</p>

                        <div class="flex items-center justify-between text-xs text-gray-500 border-t pt-4">
                            <div class="flex items-center" title="Jumlah Pertemuan per Minggu">
                                <i class="far fa-clock mr-1.5"></i> {{ $totalHours }} Pertemuan/Minggu
                            </div>
                            <a href="{{ route('teacher.lms.show', $data->id) }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                Masuk Kelas <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="inline-block p-4 rounded-full bg-blue-50 mb-4">
                        <i class="fas fa-chalkboard-teacher text-4xl text-blue-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Belum Ada Kelas</h3>
                    <p class="text-gray-500">Anda belum memiliki jadwal mengajar di tahun ajaran aktif ini.</p>
                    <p class="text-xs text-gray-400 mt-2">Hubungi Admin TU jika ini kesalahan.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
