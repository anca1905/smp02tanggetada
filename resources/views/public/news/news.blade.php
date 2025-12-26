@extends('layouts.public')

@section('title', 'Berita & Pengumuman')
@section('header', 'Berita Sekolah')
@section('subheader', 'Informasi terkini seputar prestasi, kegiatan, dan pengumuman akademik.')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="flex flex-wrap gap-2 mb-10 justify-center">
            <button class="px-4 py-2 bg-blue-900 text-white rounded-full text-sm font-medium">Semua</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @forelse($posts as $post)
                <article
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 group h-full flex flex-col">
                    <div class="h-48 overflow-hidden relative">
                        <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://via.placeholder.com/600x400?text=No+Image' }}"
                            alt="{{ $post->title }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">

                        <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                            {{ $post->category }}
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <div class="text-sm text-gray-400 mb-2">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $post->created_at->isoFormat('D MMMM Y') }}
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition">
                            {{ Str::limit($post->title, 60) }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                            {{ Str::limit(strip_tags($post->content), 100) }}
                        </p>

                        <a href="{{ route('public.berita.show', $post->slug) }}"
                            class="inline-flex items-center text-blue-600 font-semibold hover:underline mt-auto">
                            Baca Selengkapnya <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-blue-50 text-blue-500 mb-4">
                        <i class="fas fa-newspaper text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada berita</h3>
                    <p class="text-gray-500">Saat ini belum ada informasi terbaru yang diterbitkan.</p>
                </div>
            @endforelse

        </div>

        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
