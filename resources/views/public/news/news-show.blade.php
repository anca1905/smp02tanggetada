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
                            {!! nl2br(preg_replace(
                                '/(https?:\/\/[^\s<]+)/',
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline font-semibold break-all">$1</a>',
                                e($post->content)
                            )) !!}
                        </div>

                        @if (str_contains($post->content, 'forms.gle') || str_contains($post->slug, 'lomba-literasi'))
                            <div class="mt-8 p-6 rounded-2xl shadow-lg"
                                style="background: linear-gradient(135deg, #091326 0%, #1e3a8a 55%, #0f172a 100%); border: 1.5px solid rgba(234, 179, 8, 0.45); color: #ffffff;">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shrink-0"
                                        style="background-color: #facc15; color: #0f172a;">
                                        <i class="fas fa-trophy"></i>
                                    </span>
                                    <div>
                                        <h3 class="text-xl font-bold" style="color: #ffffff;">Pendaftaran & Narahubung Resmi</h3>
                                        <p class="text-sm" style="color: #bfdbfe;">Silakan lakukan pendaftaran daring dan hubungi panitia jika ada pertanyaan.</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-4">
                                    <a href="https://forms.gle/frfZEwZ9x2xwuiTM9" target="_blank" rel="noopener noreferrer"
                                        class="px-6 py-3 font-bold rounded-xl shadow transition inline-flex items-center gap-2 text-sm"
                                        style="background-color: #facc15; color: #0f172a;">
                                        <i class="fas fa-edit"></i> Isi Formulir Pendaftaran (Google Form)
                                    </a>
                                    <a href="https://wa.me/62853465489992?text=Halo%20Perpustakaan%20SMPN%202%20Tanggetada,%20saya%20ingin%20bertanya%20mengenai%20Lomba%20Literasi" target="_blank" rel="noopener noreferrer"
                                        class="px-6 py-3 font-semibold rounded-xl shadow transition inline-flex items-center gap-2 text-sm"
                                        style="background-color: #10b981; color: #ffffff;">
                                        <i class="fab fa-whatsapp text-lg"></i> Hubungi WA Panitia
                                    </a>
                                </div>
                            </div>
                        @endif

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
