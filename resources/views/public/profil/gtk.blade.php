@extends('layouts.public')

@section('title', 'GTK - Guru & Tenaga Kependidikan')
@section('header', 'Guru & Tenaga Kependidikan')
@section('subheader', 'Daftar pendidik dan tenaga kependidikan sekolah kami')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('public.profil') }}" class="hover:text-blue-700">Profil</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">GTK</span>
    </nav>

    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900">Guru & Tenaga Kependidikan</h2>
        <p class="text-gray-500 mt-3">Para pendidik berdedikasi yang membimbing generasi penerus bangsa</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($teachers as $guru)
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden text-center border border-gray-100 group">
            <div class="h-36 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center overflow-hidden">
                @if($guru->photo_url)
                    @php
                        $photoPath = str_starts_with($guru->photo_url, 'img/') ? asset($guru->photo_url) : asset('storage/' . $guru->photo_url);
                    @endphp
                    <img src="{{ $photoPath }}" alt="{{ $guru->name }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <i class="fas fa-user-tie text-5xl text-blue-400"></i>
                @endif
            </div>
            <div class="p-4">
                <h4 class="font-bold text-gray-800 text-sm leading-tight mb-2">{{ $guru->name }}</h4>
                @if($guru->position)
                    <p class="text-xs text-blue-600 font-medium">{{ $guru->position }}</p>
                @elseif($guru->subject)
                    <p class="text-xs text-blue-600 font-medium">Guru Mapel {{ $guru->subject }}</p>
                @else
                    <p class="text-xs text-blue-600 font-medium">Guru</p>
                @endif
                
                @if($guru->employee_id)
                <p class="text-xs text-gray-400 mt-2 font-mono">NIP/ID: {{ $guru->employee_id }}</p>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20 text-gray-400">
            <i class="fas fa-users text-5xl mb-4"></i>
            <p class="font-semibold">Data GTK belum tersedia</p>
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
            {{-- <a href="{{ route('public.profil.sarana') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-school"></i> Sarana &amp; Prasarana
            </a> --}}
        </div>
    </div>
</div>
@endsection
