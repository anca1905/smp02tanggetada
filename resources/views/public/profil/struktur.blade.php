@extends('layouts.public')

@section('title', 'Struktur Organisasi')
@section('header', 'Struktur Organisasi')
@section('subheader', 'Bagan kepengurusan sekolah tahun ajaran ini')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('public.profil') }}" class="hover:text-blue-700">Profil</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Struktur Organisasi</span>
    </nav>

    <div class="text-center mb-10">
        <h2 class="text-4xl font-bold text-gray-900">Struktur Organisasi</h2>
        <p class="text-gray-500 mt-3">Bagan kepengurusan sekolah tahun ajaran ini</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
        @if(isset($site_settings['struktur_img']))
            <img src="{{ asset('storage/' . $site_settings['struktur_img']) }}"
                alt="Struktur Organisasi" class="max-w-full h-auto rounded-lg mx-auto">
        @else
            <div class="w-full h-96 bg-gray-50 flex items-center justify-center rounded-xl border-2 border-dashed border-gray-300">
                <div class="text-center text-gray-400">
                    <i class="fas fa-sitemap text-5xl mb-4"></i>
                    <p class="font-semibold">Bagan Struktur Organisasi belum diunggah</p>
                    <p class="text-xs mt-1">Silakan upload melalui Admin Panel → Settings</p>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-10 border-t pt-10">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 mb-4">Jelajahi Profil Lainnya</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('public.profil.sejarah') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-history"></i> Sejarah
            </a>
            <a href="{{ route('public.profil.visimisi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-bullseye"></i> Visi &amp; Misi
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
