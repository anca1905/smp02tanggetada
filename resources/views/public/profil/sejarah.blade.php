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
            <a href="{{ route('public.profil.sarana') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-school"></i> Sarana &amp; Prasarana
            </a>
        </div>
    </div>
</div>
@endsection
