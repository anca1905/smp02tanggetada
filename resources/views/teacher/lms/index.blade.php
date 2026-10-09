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
                    $data = $schedules->first();
                    $totalHours = $schedules->count();
                    $theme = $data->subject?->theme ?? [
                        'gradient' => 'linear-gradient(135deg, #1e40af 0%, #3b82f6 100%)',
                        'icon' => 'fas fa-graduation-cap',
                    ];
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                    @if($data->subject?->cover_url)
                        {{-- Cover Dari Upload Admin TU --}}
                        <div class="h-36 relative overflow-hidden bg-slate-900">
                            <img src="{{ $data->subject->cover_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $data->subject->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                            <div class="absolute top-3 right-3 z-10">
                                <span class="bg-white/95 backdrop-blur text-xs font-bold px-2.5 py-1 rounded-full text-gray-800 shadow-md">
                                    {{ $data->classroom->name }}
                                </span>
                            </div>
                            <div class="absolute bottom-3 left-4 z-10">
                                <span class="text-[11px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-md bg-black/60 text-white backdrop-blur-sm border border-white/20">
                                    {{ $data->subject->code }}
                                </span>
                            </div>
                        </div>
                    @else
                        {{-- Fallback: Tampilan Header Berwarna Modern --}}
                        <div class="h-36 relative overflow-hidden p-4 flex flex-col justify-between"
                             style="background: {{ $theme['gradient'] }};">
                            <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-white/10 pointer-events-none"></div>
                            <div class="absolute -left-6 -top-6 w-24 h-24 rounded-full bg-white/10 pointer-events-none"></div>

                            <div class="flex items-center justify-between relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-base shadow-sm border border-white/20">
                                    <i class="{{ $theme['icon'] }}"></i>
                                </div>
                                <span class="bg-white/95 backdrop-blur text-xs font-bold px-2.5 py-1 rounded-full text-gray-800 shadow-sm">
                                    {{ $data->classroom->name }}
                                </span>
                            </div>

                            <div class="relative z-10 text-white">
                                <span class="text-[11px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-md bg-black/25 text-white backdrop-blur-sm border border-white/10">
                                    {{ $data->subject->code }}
                                </span>
                            </div>
                        </div>
                    @endif

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
