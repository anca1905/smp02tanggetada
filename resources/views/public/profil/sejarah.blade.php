@extends('layouts.public')

@section('title', 'Sejarah Sekolah')
@section('header', 'Sejarah Sekolah')
@section('subheader', 'Perjalanan panjang kami dalam mendidik generasi bangsa')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('public.profil') }}" class="hover:text-blue-700">Profil</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Sejarah</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <div class="inline-block p-2 px-4 rounded-full bg-blue-100 text-blue-800 text-sm font-bold mb-4">Sejarah Singkat</div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-snug">
                {{ $site_settings['history_title'] ?? 'Membangun Generasi Unggul' }}
            </h2>
            <div class="text-gray-600 leading-relaxed space-y-4 text-base">
                {!! nl2br(e($site_settings['history_desc'] ?? 'Sekolah ini didirikan dengan tekad kuat untuk memberikan pendidikan terbaik bagi putra-putri daerah.')) !!}
            </div>
        </div>
        <div class="relative">
            <div class="absolute -inset-4 bg-blue-100 rounded-xl transform rotate-3"></div>
            <img src="{{ isset($site_settings['history_image']) ? asset('storage/' . $site_settings['history_image']) : 'https://sman3batusangkar.sch.id/wp-content/uploads/2017/07/Gedung-Sekolah-3.jpg' }}"
                class="relative rounded-xl shadow-lg w-full" alt="Gedung Sekolah">
        </div>
    </div>

    <div class="mt-20">
        <div class="text-center mb-10">
            <div class="inline-block p-2 px-4 rounded-full bg-blue-100 text-blue-800 text-sm font-bold mb-4">Kepemimpinan</div>
            <h2 class="text-3xl font-bold text-gray-900">{{ $site_settings['sejarah_kepemimpinan_title'] ?? 'Sejarah Kepemimpinan' }}</h2>
            <p class="text-gray-600 mt-3">{{ $site_settings['sejarah_kepemimpinan_desc'] ?? 'Daftar Kepala Sekolah yang memimpin SMP Negeri 2 Tanggetada dari masa ke masa.' }}</p>
        </div>

        @if(isset($site_settings['sejarah_kepemimpinan_img']) && $site_settings['sejarah_kepemimpinan_img'])
            <div class="flex justify-center">
                <img src="{{ asset('storage/' . $site_settings['sejarah_kepemimpinan_img']) }}" 
                     alt="{{ $site_settings['sejarah_kepemimpinan_title'] ?? 'Sejarah Kepemimpinan' }}" 
                     class="w-full rounded-2xl shadow-xl border border-gray-200">
            </div>
        @else
            {{-- Tampilan Interaktif Masa ke Masa jika bagan gambar belum diupload --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $leaders = [
                        ['no' => '1', 'name' => 'Drs. Maslan', 'period' => 'Kepala Sekolah Ke-1', 'status' => 'Mantan Kepala Sekolah', 'color' => 'blue'],
                        ['no' => '2', 'name' => 'Wa Maami, S.Pd', 'period' => 'Kepala Sekolah Ke-2', 'status' => 'Mantan Kepala Sekolah', 'color' => 'indigo'],
                        ['no' => '3', 'name' => 'Drs. Nandi, M.M.Pd', 'period' => 'Kepala Sekolah Ke-3', 'status' => 'Mantan Kepala Sekolah', 'color' => 'purple'],
                        ['no' => '4', 'name' => 'Ir. Iwan Taufik Imron, S.Si., M.Si.', 'period' => 'Kepala Sekolah Ke-4', 'status' => 'Kepala Sekolah', 'color' => 'emerald'],
                    ];
                @endphp

                @foreach($leaders as $leader)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition text-center relative overflow-hidden flex flex-col justify-between">
                        <div class="w-12 h-12 mx-auto rounded-full bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-lg mb-4 border border-blue-100 shadow-sm">
                            {{ $leader['no'] }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base mb-1">{{ $leader['name'] }}</h3>
                            <p class="text-xs font-semibold text-blue-600 mb-2">{{ $leader['period'] }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <span class="inline-block px-3 py-1 text-xs rounded-full {{ $leader['no'] == '4' ? 'bg-emerald-100 text-emerald-800 font-bold' : 'bg-gray-100 text-gray-600' }}">
                                {{ $leader['status'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-16 border-t pt-10">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 mb-4">Jelajahi Profil Lainnya</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('public.profil.visimisi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-bullseye"></i> Visi &amp; Misi
            </a>
            <a href="{{ route('public.profil.struktur') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-sitemap"></i> Struktur Organisasi
            </a>
            <a href="{{ route('public.profil.gtk') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-chalkboard-teacher"></i> GTK
            </a>
            {{-- <a href="{{ route('public.profil.sarana') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-school"></i> Sarana &amp; Prasarana
            </a> --}}
        </div>
    </div>
</div>
@endsection
