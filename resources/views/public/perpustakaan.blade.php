@extends('layouts.public')

@section('title', 'Perpustakaan & Katalog Buku')
@section('header', 'Perpustakaan Sekolah')
@section('subheader', 'Pusat sumber belajar, sirkulasi buku BOS, dan literasi siswa')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-amber-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-amber-900 font-semibold">Perpustakaan</span>
    </nav>

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-amber-700 via-amber-800 to-amber-950 text-white rounded-3xl p-8 sm:p-10 shadow-xl mb-10 flex flex-col md:flex-row items-center gap-8">
        <div class="shrink-0 w-24 h-24 sm:w-28 sm:h-28 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner">
            <i class="fas fa-book-open text-5xl text-amber-200"></i>
        </div>
        <div class="space-y-2 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-amber-200 text-xs font-semibold">
                <i class="fas fa-check-circle"></i> E-Katalog & Sirkulasi Terintegrasi
            </div>
            <h2 class="text-3xl font-bold">Perpustakaan {{ $site_settings['app_name'] ?? 'SMPN 2 Tanggetada' }}</h2>
            <p class="text-amber-100 text-sm sm:text-base leading-relaxed max-w-2xl">
                Menyediakan ribuan eksemplar buku teks utama pendanaan BOS untuk seluruh siswa kelas 7, 8, dan 9,
                serta buku referensi ilmiah, sastra fiksi, dan ensiklopedia.
            </p>
        </div>
    </div>

    {{-- Highlight Event Dinamis: Lomba Literasi Antar Kelas --}}
    @if (($site_settings['popup_active'] ?? '0') == '1')
        @php
            $perpustakaanImg = !empty($site_settings['popup_image'])
                ? asset('storage/' . $site_settings['popup_image'])
                : asset('images/lomba-literasi-antar-kelas-2026.jpg');
            $perpustakaanBtnUrl = $site_settings['popup_btn_url'] ?? 'https://forms.gle/frfZEwZ9x2xwuiTM9';
            $perpustakaanWa = $site_settings['popup_wa_number'] ?? '0853465489992';
            $perpustakaanCleanWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $perpustakaanWa));
        @endphp
        <div class="rounded-3xl p-6 sm:p-8 shadow-xl mb-10"
            style="background: linear-gradient(135deg, #091326 0%, #1e3a8a 55%, #0f172a 100%); border: 1.5px solid rgba(234, 179, 8, 0.45); color: #ffffff;">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-4 flex justify-center">
                    <a href="{{ $perpustakaanImg }}" target="_blank" class="block group relative rounded-2xl overflow-hidden shadow-md max-w-[240px]">
                        <img src="{{ $perpustakaanImg }}" alt="Poster" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-semibold text-white transition gap-1.5">
                            <i class="fas fa-search-plus"></i> Perbesar Poster
                        </div>
                    </a>
                </div>
                <div class="md:col-span-8 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
                        style="background-color: rgba(234, 179, 8, 0.2); color: #fde047; border: 1px solid rgba(234, 179, 8, 0.4);">
                        <i class="fas fa-trophy" style="color: #facc15;"></i> {{ $site_settings['popup_badge'] ?? 'EVENT BULAN BAHASA' }}
                    </div>
                    <h3 class="text-2xl font-bold" style="color: #ffffff;">{{ $site_settings['popup_title'] ?? 'Lomba Literasi Antar Kelas' }}</h3>
                    @if (!empty($site_settings['popup_subtitle']))
                        <p class="text-xs sm:text-sm italic" style="color: #fef08a;">
                            &ldquo;{{ $site_settings['popup_subtitle'] }}&rdquo;
                        </p>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-2 text-xs">
                        <div class="rounded-xl p-2.5" style="background-color: rgba(255, 255, 255, 0.1);">
                            <div class="text-gray-300 font-medium">Cerdas Cermat</div>
                            <div class="font-bold text-white">3 Orang / Kelas</div>
                        </div>
                        <div class="rounded-xl p-2.5" style="background-color: rgba(255, 255, 255, 0.1);">
                            <div class="text-gray-300 font-medium">Pidato</div>
                            <div class="font-bold text-white">1 Orang / Kelas</div>
                        </div>
                        <div class="rounded-xl p-2.5" style="background-color: rgba(255, 255, 255, 0.1);">
                            <div class="text-gray-300 font-medium">Puisi</div>
                            <div class="font-bold text-white">1 Orang / Kelas</div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs" style="color: #bfdbfe;">
                        <span><i class="fas fa-calendar-alt mr-1" style="color: #facc15;"></i> 28 Oktober 2026</span>
                        <span><i class="fas fa-map-marker-alt mr-1" style="color: #facc15;"></i> Lab. Komputer SMPN 2 Tanggetada</span>
                        @if (!empty($site_settings['popup_deadline']))
                            <span class="font-medium" style="color: #fde047;"><i class="fas fa-clock mr-1"></i> {{ $site_settings['popup_deadline'] }}</span>
                        @endif
                    </div>
                    <div class="pt-2 flex flex-wrap gap-3">
                        <a href="{{ $perpustakaanBtnUrl }}" target="_blank" rel="noopener noreferrer"
                            class="px-5 py-2.5 font-bold rounded-xl text-xs sm:text-sm transition shadow flex items-center gap-2"
                            style="background-color: #facc15; color: #0f172a;">
                            <i class="fas fa-edit"></i> {{ $site_settings['popup_btn_text'] ?? 'Daftar via Google Form' }}
                        </a>
                        @if (!empty($perpustakaanWa))
                            <a href="https://wa.me/{{ $perpustakaanCleanWa }}?text=Halo%20Perpustakaan,%20saya%20ingin%20bertanya%20mengenai%20{{ urlencode($site_settings['popup_title'] ?? 'Lomba Literasi') }}" target="_blank" rel="noopener noreferrer"
                                class="px-4 py-2.5 font-semibold rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5 shadow"
                                style="background-color: #10b981; color: #ffffff;">
                                <i class="fab fa-whatsapp text-sm"></i> WA Panitia: {{ $perpustakaanWa }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Section: Pencarian & Katalog Buku Perpustakaan --}}
    <div class="mb-14">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Koleksi Terbuka</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">E-Katalog Buku & Buku Paket BOS</h3>
                <p class="text-sm text-gray-500">Cari buku pelajaran atau buku bacaan yang tersedia di rak perpustakaan sekolah.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-800 font-semibold border border-amber-200">
                    <i class="fas fa-book mr-1"></i> {{ $stats['total_titles'] ?? 0 }} Judul Buku
                </span>
                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-800 font-semibold border border-blue-200">
                    <i class="fas fa-layer-group mr-1"></i> {{ number_format($stats['total_stock'] ?? 0) }} Eksemplar
                </span>
            </div>
        </div>

        {{-- Search & Filter Form --}}
        <form method="GET" action="{{ route('public.perpustakaan') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8 grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 relative">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari judul buku, nama penulis, atau kode ISBN..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                <i class="fas fa-search absolute left-3.5 top-3.5 text-gray-400 text-sm"></i>
            </div>

            <div class="sm:col-span-3">
                <select name="category" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="all">Semua Kategori</option>
                    <option value="buku_paket_bos" {{ request('category') === 'buku_paket_bos' ? 'selected' : '' }}>Buku Paket BOS</option>
                    <option value="referensi" {{ request('category') === 'referensi' ? 'selected' : '' }}>Buku Referensi</option>
                    <option value="fiksi" {{ request('category') === 'fiksi' ? 'selected' : '' }}>Fiksi / Cerita</option>
                    <option value="non_fiksi" {{ request('category') === 'non_fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <select name="class_level" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="all">Semua Kelas</option>
                    <option value="7" {{ request('class_level') === '7' ? 'selected' : '' }}>Kelas 7</option>
                    <option value="8" {{ request('class_level') === '8' ? 'selected' : '' }}>Kelas 8</option>
                    <option value="9" {{ request('class_level') === '9' ? 'selected' : '' }}>Kelas 9</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold transition shrink-0 shadow-sm">
                    Cari
                </button>
                @if(request()->anyFilled(['q', 'category', 'class_level']))
                    <a href="{{ route('public.perpustakaan') }}" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                        <i class="fas fa-redo-alt"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- Book Grid --}}
        @if(isset($books) && $books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($books as $book)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                        <div>
                            {{-- Cover --}}
                            <div class="relative bg-amber-50/50 h-52 flex items-center justify-center p-4 overflow-hidden border-b border-gray-50">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-44 object-contain rounded shadow group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-28 h-40 bg-gradient-to-br from-amber-600 to-amber-800 text-white rounded-md shadow-md p-3 flex flex-col justify-between group-hover:scale-105 transition duration-300">
                                        <div class="text-[9px] font-mono tracking-widest text-amber-200">{{ $book->book_code }}</div>
                                        <div class="text-xs font-bold leading-tight line-clamp-3">{{ $book->title }}</div>
                                        <div class="text-[9px] text-amber-100">{{ $book->class_level !== 'semua' ? 'Kelas ' . $book->class_level : 'SMP' }}</div>
                                    </div>
                                @endif

                                <div class="absolute top-2.5 left-2.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs {{ $book->category === 'buku_paket_bos' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $book->category_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 space-y-1.5">
                                <span class="text-[10px] text-gray-400 font-mono">Kode: {{ $book->book_code }}</span>
                                <h4 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h4>
                                <p class="text-xs text-gray-500 line-clamp-1">Penulis: {{ $book->author ?: '-' }}</p>
                                <p class="text-[11px] text-gray-400">Rak: {{ $book->location_shelf ?: 'Rak Koleksi' }}</p>
                            </div>
                        </div>

                        <div class="p-4 pt-0 border-t border-gray-50 flex items-center justify-between text-xs mt-2">
                            @if($book->available_stock > 0)
                                <span class="font-semibold text-emerald-600 inline-flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Tersedia ({{ $book->available_stock }})
                                </span>
                            @else
                                <span class="font-semibold text-red-500 inline-flex items-center gap-1">
                                    <i class="fas fa-times-circle"></i> Sedang Dipinjam
                                </span>
                            @endif

                            <span class="text-[11px] text-gray-400 font-medium">
                                Tingkat: {{ $book->class_level === 'semua' ? 'Semua' : 'Kelas ' . $book->class_level }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $books->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
                <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                <h4 class="text-base font-bold text-gray-700">Buku tidak ditemukan</h4>
                <p class="text-sm mt-1">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>
        @endif
    </div>

    {{-- Info Layanan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-14 h-14 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Jam Operasional</h4>
            <p class="text-sm text-gray-500">Senin – Jumat</p>
            <p class="text-sm font-semibold text-gray-700">08.00 – 14.30 WITA</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Buku Paket BOS</h4>
            <p class="text-sm text-gray-500">Gratis dipinjam 1 semester / tahun</p>
            <p class="text-sm font-semibold text-gray-700">Distribusi per rombel kelas</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-book-reader text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Peminjaman Mandiri</h4>
            <p class="text-sm text-gray-500">Buku sastra, fiksi & referensi</p>
            <p class="text-sm font-semibold text-gray-700">Maks. 2 buku selama 14 hari</p>
        </div>
    </div>

    {{-- Tata Tertib --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2">
            <i class="fas fa-list-ol text-amber-600"></i> Tata Tertib Perpustakaan SMPN 2 Tanggetada
        </h3>
        <ul class="space-y-3 text-sm text-gray-600">
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</span>
                Siswa wajib menunjukkan kartu pelajar atau menyebutkan Nomor Induk Siswa (NIS) saat peminjaman buku.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</span>
                Buku paket BOS wajib dirawat, disampul rapi, dan tidak boleh dicoret atau dihilangkan.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</span>
                Kembalikan buku tepat waktu sebelum batas akhir untuk memberikan kesempatan bagi rekan siswa lainnya.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">4</span>
                Menjaga ketenangan, kebersihan, dan kesopanan selama berada di ruang perpustakaan sekolah.
            </li>
        </ul>
    </div>

    <div class="mt-8 text-center text-gray-400 text-sm">
        <i class="fas fa-map-marker-alt mr-2 text-amber-500"></i>
        Perpustakaan terletak di Gedung Utama Lantai 1 &bull; SMPN 2 Tanggetada, Kec. Tanggetada, Kab. Kolaka
    </div>
</div>
@endsection
