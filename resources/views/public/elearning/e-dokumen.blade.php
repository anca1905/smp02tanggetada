@extends('layouts.public')

@section('title', 'E-Dokumen - E-Learning')
@section('header', 'E-Dokumen')
@section('subheader', 'Dokumen-dokumen resmi dan penting dari sekolah')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">E-Learning</span>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">E-Dokumen</span>
    </nav>

    <div class="text-center mb-12">
        <div class="w-20 h-20 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-folder-open text-3xl"></i>
        </div>
        <h2 class="text-4xl font-bold text-gray-900">E-Dokumen</h2>
        <p class="text-gray-500 mt-3">Kumpulan dokumen resmi sekolah yang dapat diunduh</p>
    </div>

    {{-- Kategori Dokumen --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
            $categories = [
                'Dokumen Administrasi' => ['icon' => 'fa-file-alt', 'color' => 'blue', 'desc' => 'Form, surat, dan berkas administrasi'],
                'Kurikulum & Program' => ['icon' => 'fa-book', 'color' => 'green', 'desc' => 'Silabus, RPP, dan program sekolah'],
                'Prestasi & Sertifikat' => ['icon' => 'fa-trophy', 'color' => 'yellow', 'desc' => 'Piagam, sertifikat akreditasi'],
                'Peraturan & Kebijakan' => ['icon' => 'fa-gavel', 'color' => 'purple', 'desc' => 'Tata tertib, SK, dan kebijakan sekolah']
            ];
        @endphp

        @foreach ($categories as $catName => $catStyle)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-{{ $catStyle['color'] }}-100 text-{{ $catStyle['color'] }}-700 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas {{ $catStyle['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $catName }}</h3>
                        <p class="text-xs text-gray-400">{{ $catStyle['desc'] }}</p>
                    </div>
                </div>
                <ul class="space-y-2 text-sm">
                    @if (isset($dokumens[$catName]) && $dokumens[$catName]->count() > 0)
                        @foreach ($dokumens[$catName] as $dok)
                            <li class="flex items-center justify-between py-2 border-b border-dashed">
                                <span class="text-gray-700 flex items-center gap-2 truncate">
                                    <i class="fas fa-file-pdf text-red-500"></i>
                                    {{ $dok->title }}
                                </span>
                                <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="text-{{ $catStyle['color'] }}-600 hover:underline shrink-0 ml-4 font-medium">Lihat</a>
                            </li>
                        @endforeach
                    @else
                        <li class="flex items-center gap-2 text-gray-500 italic py-2 border-b border-dashed">
                            <i class="fas fa-info-circle text-{{ $catStyle['color'] }}-400"></i>
                            Dokumen belum tersedia
                        </li>
                    @endif
                </ul>
            </div>
        @endforeach
    </div>

    <div class="mt-10 bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
        <i class="fas fa-envelope text-blue-600 text-2xl mb-3"></i>
        <p class="text-blue-800 font-semibold">Butuh dokumen tertentu?</p>
        <p class="text-blue-600 text-sm mt-1">Hubungi kami melalui halaman kontak atau datang langsung ke sekolah.</p>
        <a href="{{ route('public.kontak') }}" class="inline-flex items-center gap-2 mt-4 bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
            <i class="fas fa-envelope"></i> Hubungi Kami
        </a>
    </div>
</div>
@endsection
