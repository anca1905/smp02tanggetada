@extends('layouts.public')

@section('title', 'Galeri Prestasi Siswa - ' . ($site_settings['app_name'] ?? 'SIMS'))
@section('header', 'Galeri Prestasi & Rekam Jejak Siswa')
@section('subheader', 'Mengukir karya, dedikasi, dan kejuaraan membanggakan siswa-siswi di berbagai kancah akademik maupun non-akademik')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    {{-- Sorotan Utama / Featured Achievements --}}
    @if($featuredAchievements->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fas fa-star text-amber-500"></i> Sorotan Prestasi Terpilih
                    </span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-1">Hall of Fame Prestasi Sekolah</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredAchievements as $feat)
                    <div class="group relative bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-amber-100 flex flex-col">
                        <div class="relative h-56 bg-slate-900 overflow-hidden">
                            @if($feat->photo_path)
                                <img src="{{ asset('storage/' . $feat->photo_path) }}" alt="{{ $feat->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-600 via-orange-600 to-yellow-600 text-white p-6 text-center">
                                    <i class="fas fa-trophy text-5xl mb-2 drop-shadow"></i>
                                    <p class="font-bold text-lg leading-tight">{{ $feat->rank ?: 'Juara' }}</p>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-amber-950 shadow">
                                    {{ $feat->rank ?: 'Juara' }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-md text-white">
                                    Tingkat {{ ucfirst($feat->level) }}
                                </span>
                            </div>

                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <p class="text-xs text-amber-300 font-semibold uppercase tracking-wider mb-0.5">
                                    {{ str_replace('_', ' ', $feat->category) }}
                                </p>
                                <h3 class="font-bold text-base line-clamp-1 leading-snug drop-shadow">
                                    {{ $feat->title }}
                                </h3>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                                    <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-xs">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $feat->student_name }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-calendar-alt w-4 text-gray-400 text-center"></i>
                                    <span>{{ $feat->event_name }} ({{ $feat->event_date?->format('d M Y') ?? 'Tahun Ini' }})</span>
                                </div>
                                @if($feat->description)
                                    <p class="text-xs text-gray-600 line-clamp-2 mt-2 leading-relaxed italic">
                                        {{ $feat->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Semua Arsip Prestasi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Daftar lengkap peraihan penghargaan dan kejuaraan siswa SMP 02.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('public.prestasi') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lomba, siswa, juara..."
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <select name="level" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Semua Tingkat --</option>
                    <option value="internasional" {{ request('level') === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    <option value="nasional" {{ request('level') === 'nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="provinsi" {{ request('level') === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="kabupaten" {{ request('level') === 'kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                    <option value="kecamatan" {{ request('level') === 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    <option value="sekolah" {{ request('level') === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                </select>
            </div>
            <div>
                <select name="category" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Semua Bidang --</option>
                    <option value="akademik" {{ request('category') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="olahraga" {{ request('category') === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="seni_budaya" {{ request('category') === 'seni_budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                    <option value="keagamaan" {{ request('category') === 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                    <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-search mr-1.5"></i> Temukan
                </button>
                @if(request()->anyFilled(['search', 'level', 'category']))
                    <a href="{{ route('public.prestasi') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm transition flex items-center">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Grid Achievements --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($achievements as $ach)
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group">
                <div class="relative h-52 bg-slate-100 overflow-hidden">
                    @if($ach->photo_path)
                        <img src="{{ asset('storage/' . $ach->photo_path) }}" alt="{{ $ach->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600">
                            <i class="fas fa-award text-5xl mb-2 opacity-80"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider text-blue-800">Dokumentasi Prestasi</span>
                        </div>
                    @endif

                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow uppercase">
                            {{ $ach->rank ?: 'Juara' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-black/60 backdrop-blur-sm text-white">
                            {{ ucfirst($ach->level) }}
                        </span>
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1.5">
                            {{ str_replace('_', ' ', $ach->category) }}
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg leading-snug line-clamp-2 group-hover:text-blue-900 transition">
                            {{ $ach->title }}
                        </h3>

                        <div class="mt-3 pt-3 border-t border-gray-100 space-y-1.5 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-graduate text-blue-600 w-4 text-center"></i>
                                <span class="font-bold text-gray-800">{{ $ach->student_name }}</span>
                                @if($ach->student && $ach->student->classroom)
                                    <span class="text-gray-400">({{ $ach->student->classroom->name }})</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-flag text-amber-500 w-4 text-center"></i>
                                <span>{{ $ach->event_name }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-500">
                                <i class="fas fa-calendar-alt text-gray-400 w-4 text-center"></i>
                                <span>{{ $ach->event_date?->format('d F Y') ?? 'Tahun Ini' }}</span>
                            </div>
                        </div>

                        @if($ach->description)
                            <p class="text-xs text-gray-600 mt-3 line-clamp-3 leading-relaxed">
                                {{ $ach->description }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-16 text-center border border-gray-200">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Belum Ada Data Prestasi Ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                    Data kejuaraan atau prestasi yang Anda cari belum tersedia untuk filter saat ini.
                </p>
                @if(request()->anyFilled(['search', 'level', 'category']))
                    <a href="{{ route('public.prestasi') }}" class="inline-block mt-4 px-4 py-2 bg-blue-900 text-white rounded-xl text-xs font-semibold">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $achievements->links() }}
    </div>

</div>
@endsection
