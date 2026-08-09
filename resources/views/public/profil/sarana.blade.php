@extends('layouts.public')

@section('title', 'Sarana & Prasarana')
@section('header', 'Sarana & Prasarana')
@section('subheader', 'Fasilitas penunjang kegiatan belajar mengajar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('public.profil') }}" class="hover:text-blue-700">Profil</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Sarana &amp; Prasarana</span>
    </nav>

    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900">Sarana &amp; Prasarana</h2>
        <p class="text-gray-500 mt-3">Fasilitas lengkap untuk mendukung proses belajar mengajar yang optimal</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($facility as $fasilitas)
        <div class="group relative overflow-hidden rounded-2xl shadow-md h-64">
            <img src="{{ asset($fasilitas->image_path) }}"
                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                alt="{{ $fasilitas->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                <div>
                    <h3 class="text-white font-bold text-lg">{{ $fasilitas->title }}</h3>
                    @if($fasilitas->description ?? false)
                        <p class="text-white/75 text-sm mt-1">{{ $fasilitas->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-20 text-gray-400">
            <i class="fas fa-school text-5xl mb-4"></i>
            <p class="font-semibold">Data fasilitas belum tersedia</p>
            <p class="text-sm mt-1">Silakan tambahkan melalui Admin Panel</p>
        </div>
        @endforelse
    </div>

    <div class="mt-14 border-t pt-10">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 mb-4">Jelajahi Profil Lainnya</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('public.profil.sejarah') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-history"></i> Sejarah
            </a>
            <a href="{{ route('public.profil.visimisi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-bullseye"></i> Visi &amp; Misi
            </a>
            <a href="{{ route('public.profil.struktur') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-sitemap"></i> Struktur Organisasi
            </a>
            <a href="{{ route('public.profil.gtk') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-chalkboard-teacher"></i> GTK
            </a>
        </div>
    </div>
</div>
@endsection
