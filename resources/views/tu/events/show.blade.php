@extends('layouts.app')

@section('title', 'Detail Agenda - ' . $event->title)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <a href="{{ route('tu.events.index') }}" class="text-sm text-blue-600 hover:underline mb-2 inline-flex items-center">
            <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Agenda
        </a>
        <h2 class="text-2xl font-bold text-gray-800">{{ $event->title }}</h2>
        <p class="text-sm text-gray-500">
            @if ($event->type == 'academic')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    <i class="fas fa-graduation-cap mr-1"></i> Akademik
                </span>
            @elseif($event->type == 'holiday')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                    <i class="fas fa-calendar-times mr-1"></i> Hari Libur
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    <i class="fas fa-calendar-check mr-1"></i> Kegiatan Sekolah
                </span>
            @endif
        </p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('tu.events.edit', $event->id) }}"
            class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-semibold hover:bg-yellow-600 transition shadow-sm inline-flex items-center">
            <i class="fas fa-edit mr-1.5"></i> Edit Agenda
        </a>
        <form action="{{ route('tu.events.destroy', $event->id) }}" method="POST"
            onsubmit="confirmDelete(event, this);">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition shadow-sm inline-flex items-center">
                <i class="fas fa-trash-alt mr-1.5"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Card Informasi Utama --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i> Informasi Kegiatan
                </h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Agenda</p>
                        <p class="font-semibold text-gray-800 text-base">{{ $event->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Kategori</p>
                        <p class="font-semibold text-gray-800 capitalize">
                            @if ($event->type == 'academic')
                                Akademik (Ujian, Rapor, dll)
                            @elseif($event->type == 'holiday')
                                Hari Libur / Tanggal Merah
                            @else
                                Kegiatan Sekolah (Upacara, Lomba, dll)
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                        <p class="font-semibold text-gray-800">
                            <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                            {{ $event->start_date ? $event->start_date->isoFormat('dddd, D MMMM Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                        <p class="font-semibold text-gray-800">
                            <i class="far fa-calendar-check text-gray-400 mr-1"></i>
                            {{ $event->end_date ? $event->end_date->isoFormat('dddd, D MMMM Y') : ($event->start_date ? $event->start_date->isoFormat('dddd, D MMMM Y') : '-') }}
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Deskripsi Kegiatan</p>
                    @if($event->description)
                        <div class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg whitespace-pre-line leading-relaxed">
                            {{ $event->description }}
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada deskripsi tambahan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Meta & Pintasan --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock text-gray-400 mr-2"></i> Log Waktu
            </h4>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500">Dibuat pada</p>
                    <p class="font-medium text-gray-800">{{ $event->created_at ? $event->created_at->isoFormat('D MMMM Y, HH:mm') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Terakhir diperbarui</p>
                    <p class="font-medium text-gray-800">{{ $event->updated_at ? $event->updated_at->isoFormat('D MMMM Y, HH:mm') : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 text-blue-900 text-sm space-y-2">
            <div class="flex items-center gap-2 font-bold text-blue-800">
                <i class="fas fa-lightbulb text-amber-500"></i> Informasi Agenda
            </div>
            <p class="text-xs text-blue-700 leading-relaxed">
                Agenda ini ditampilkan pada Kalender Akademik publik dan dashboard Kepala Sekolah untuk menginformasikan kegiatan sekolah terkini.
            </p>
        </div>
    </div>
</div>
@endsection
