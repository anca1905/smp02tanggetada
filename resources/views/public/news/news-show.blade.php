@extends('layouts.public')

@section('title', $post->title)
@section('header', 'Artikel Sekolah')
@section('subheader', $post->category)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <div class="lg:col-span-2">
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @if ($post->image)
                        <div class="h-96 overflow-hidden w-full">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-8">
                        <div class="flex items-center text-sm text-gray-500 mb-4 space-x-4">
                            <span class="flex items-center"><i class="far fa-calendar-alt mr-2 text-blue-600"></i>
                                {{ $post->created_at->isoFormat('D MMMM Y') }}</span>
                            <span class="flex items-center"><i class="far fa-folder mr-2 text-blue-600"></i>
                                {{ $post->category }}</span>
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>

                        <div class="prose max-w-none text-gray-700 leading-relaxed text-lg">
                            {!! nl2br(e($post->content)) !!}
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-100">
                            <a href="{{ route('public.berita') }}"
                                class="inline-flex items-center text-blue-900 font-semibold hover:underline">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Berita
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-6 text-lg border-b border-gray-100 pb-3">Berita Terbaru Lainnya
                    </h3>

                    <div class="space-y-6">
                        @forelse($recent_posts as $recent)
                            <div class="flex space-x-4 group">
                                <div class="shrink-0 w-20 h-20 rounded-lg overflow-hidden relative">
                                    <img src="{{ $recent->image ? asset('storage/' . $recent->image) : 'https://via.placeholder.com/150?text=News' }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                        alt="Thumb">
                                </div>
                                <div>
                                    <h4
                                        class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition mb-1">
                                        <a href="{{ route('public.berita.show', $recent->slug) }}">{{ $recent->title }}</a>
                                    </h4>
                                    <span class="text-xs text-gray-500">{{ $recent->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada berita lain.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
