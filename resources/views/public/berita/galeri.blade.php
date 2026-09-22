@extends('layouts.public')

@section('title', 'Galeri')
@section('header', 'Galeri Foto')
@section('subheader', 'Momen-momen berkesan dari kegiatan sekolah kami')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Galeri</span>
    </nav>

    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900">Galeri Foto</h2>
        <p class="text-gray-500 mt-3">Dokumentasi foto berbagai kegiatan dan momen sekolah</p>
    </div>

    {{-- Masonry/Grid Galeri --}}
    <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
        @forelse($posts as $post)
        @if($post->image)
        <a href="{{ route('public.berita.show', $post->slug) }}"
            class="group block break-inside-avoid overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition border border-gray-100">
            <div class="relative overflow-hidden">
                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                    class="w-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-end p-3 opacity-0 group-hover:opacity-100">
                    <p class="text-white text-sm font-semibold line-clamp-2">{{ $post->title }}</p>
                </div>
            </div>
        </a>
        @endif
        @empty
        <div class="col-span-4 text-center py-20 text-gray-400">
            <i class="fas fa-images text-5xl mb-4"></i>
            <p class="font-semibold">Belum ada foto di galeri</p>
        </div>
        @endforelse
    </div>

    @if($posts->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
