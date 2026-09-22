@extends('layouts.public')

@section('title', 'Visi & Misi')
@section('header', 'Visi & Misi')
@section('subheader', 'Arah dan tujuan pendidikan kami')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('public.profil') }}" class="hover:text-blue-700">Profil</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Visi & Misi</span>
    </nav>

    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900">Visi &amp; Misi</h2>
        <p class="text-gray-500 mt-3">Landasan arah pendidikan kami untuk masa depan yang lebih cerah</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white p-8 rounded-2xl shadow-sm border-t-4 border-blue-600">
            <div class="w-14 h-14 bg-blue-100 text-blue-900 rounded-xl flex items-center justify-center mb-6">
                <i class="fas fa-eye text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Visi</h3>
            <p class="text-lg text-gray-700 italic leading-relaxed">
                "{!! nl2br(e($site_settings['school_visi'] ?? 'Menjadi sekolah unggul, berkarakter, dan berwawasan global.')) !!}"
            </p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border-t-4 border-yellow-500">
            <div class="w-14 h-14 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center mb-6">
                <i class="fas fa-list-ul text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Misi</h3>
            <div class="text-gray-700 leading-relaxed space-y-2">
                {!! nl2br(e($site_settings['school_misi'] ?? "- Menyelenggarakan pendidikan berkualitas.\n- Mengembangkan potensi siswa.\n- Membangun karakter dan akhlak mulia.")) !!}
            </div>
        </div>
    </div>

    <div class="mt-10 border-t pt-10">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 mb-4">Jelajahi Profil Lainnya</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('public.profil.sejarah') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                <i class="fas fa-history"></i> Sejarah
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
