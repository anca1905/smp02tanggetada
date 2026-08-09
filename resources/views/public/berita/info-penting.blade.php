@extends('layouts.public')

@section('title', 'Info Penting')
@section('header', 'Info Penting')
@section('subheader', 'Pengumuman dan informasi penting dari sekolah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Info Penting</span>
    </nav>

    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900">Info Penting</h2>
        <p class="text-gray-500 mt-3">Pengumuman dan informasi terkini dari sekolah</p>
    </div>

    <div class="space-y-5">
        @forelse($posts as $post)
        <a href="{{ route('public.berita.show', $post->slug) }}"
            class="group flex gap-5 bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 border border-gray-100 items-start">
            <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-bell text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-800 text-base group-hover:text-blue-900 transition">{{ $post->title }}</h3>
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ \Carbon\Carbon::parse($post->created_at)->isoFormat('D MMMM Y') }}
                </p>
                @if($post->excerpt ?? false)
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                @endif
            </div>
            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition flex-shrink-0 mt-1"></i>
        </a>
        @empty
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-bell-slash text-5xl mb-4"></i>
            <p class="font-semibold">Belum ada info penting saat ini</p>
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
