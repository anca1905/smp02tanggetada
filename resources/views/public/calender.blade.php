@extends('layouts.public')

@section('title', 'Kalender Akademik')
@section('header', 'Kalender Akademik')
@section('subheader', 'Agenda Kegiatan Sekolah Tahun Ajaran Ini')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        @if ($eventsByMonth->isEmpty())
            <div class="text-center py-12">
                <div class="inline-block p-4 rounded-full bg-blue-50 text-blue-500 mb-4">
                    <i class="fas fa-calendar-times text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada agenda</h3>
                <p class="text-gray-500">Jadwal kegiatan akademik belum dirilis.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @foreach ($eventsByMonth as $month => $events)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                        <div class="bg-blue-900 text-white py-3 px-6 font-bold text-center uppercase tracking-wide">
                            {{ $month }}
                        </div>

                        <div class="p-6 space-y-6">
                            @foreach ($events as $event)
                                <div class="flex items-start group">
                                    <div class="shrink-0 w-14 text-center border-r border-gray-100 pr-3">
                                        <span
                                            class="block text-2xl font-bold text-gray-800 group-hover:text-blue-600 transition">
                                            {{ $event->start_date->format('d') }}
                                        </span>
                                        <span class="text-xs text-gray-500 uppercase">
                                            {{ $event->start_date->isoFormat('ddd') }}
                                        </span>
                                    </div>

                                    <div class="ml-4 w-full">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition">
                                            {{ $event->title }}
                                        </h4>

                                        <div class="mt-1 mb-1">
                                            @if ($event->type == 'holiday')
                                                <span
                                                    class="inline-block px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold rounded uppercase">Libur</span>
                                            @elseif($event->type == 'academic')
                                                <span
                                                    class="inline-block px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] font-bold rounded uppercase">Akademik</span>
                                            @else
                                                <span
                                                    class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-600 text-[10px] font-bold rounded uppercase">Kegiatan</span>
                                            @endif
                                        </div>

                                        @if ($event->description)
                                            <p class="text-xs text-gray-500 line-clamp-2">{{ $event->description }}</p>
                                        @endif

                                        @if ($event->end_date && $event->end_date != $event->start_date)
                                            <p class="text-xs text-gray-400 mt-1 italic">
                                                s.d {{ $event->end_date->format('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
    </div>
@endsection
