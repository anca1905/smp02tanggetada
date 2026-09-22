@extends('layouts.public')

@section('title', 'Kegiatan Sekolah')
@section('header', 'Kegiatan Sekolah')
@section('subheader', 'Berbagai aktivitas dan kegiatan yang berlangsung di sekolah kami')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Kegiatan Sekolah</span>
    </nav>

    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900">Kegiatan Sekolah</h2>
        <p class="text-gray-500 mt-3">Dokumentasi berbagai kegiatan belajar dan ekstrakurikuler</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($posts as $post)
        <a href="{{ route('public.berita.show', $post->slug) }}"
            class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100">
            @if($post->image)
            <div class="h-48 overflow-hidden">
                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            </div>
            @else
            <div class="h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                <i class="fas fa-newspaper text-4xl text-blue-400"></i>
            </div>
            @endif
            <div class="p-5">
                <p class="text-xs text-blue-600 font-medium mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ \Carbon\Carbon::parse($post->created_at)->isoFormat('D MMMM Y') }}
                </p>
                <h3 class="font-bold text-gray-800 text-base leading-snug group-hover:text-blue-900 transition line-clamp-2">
                    {{ $post->title }}
                </h3>
                @if($post->excerpt ?? false)
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-20 text-gray-400">
            <i class="fas fa-newspaper text-5xl mb-4"></i>
            <p class="font-semibold">Belum ada kegiatan yang dipublikasikan</p>
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
