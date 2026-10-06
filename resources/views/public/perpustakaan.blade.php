@extends('layouts.public')

@section('title', 'Perpustakaan')
@section('header', 'Perpustakaan Sekolah')
@section('subheader', 'Pusat sumber belajar dan literasi siswa')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Perpustakaan</span>
    </nav>

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-amber-600 to-amber-900 text-white rounded-3xl p-10 shadow-xl mb-10 flex flex-col md:flex-row items-center gap-8">
        <div class="shrink-0 w-28 h-28 bg-white/20 rounded-2xl flex items-center justify-center">
            <i class="fas fa-book-open text-5xl"></i>
        </div>
        <div>
            <h2 class="text-3xl font-bold mb-2">Perpustakaan {{ $site_settings['app_name'] ?? 'Sekolah' }}</h2>
            <p class="text-amber-200 text-base leading-relaxed">
                Perpustakaan kami menyediakan koleksi buku yang lengkap untuk mendukung kegiatan
                belajar mengajar dan mengembangkan budaya literasi siswa.
            </p>
        </div>
    </div>

    {{-- Highlight Event: Lomba Literasi Antar Kelas --}}
    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-10 border border-yellow-500/30">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-4 flex justify-center">
                <a href="{{ asset('images/lomba-literasi-antar-kelas-2026.jpg') }}" target="_blank" class="block group relative rounded-2xl overflow-hidden shadow-md max-w-[240px]">
                    <img src="{{ asset('images/lomba-literasi-antar-kelas-2026.jpg') }}" alt="Poster Lomba Literasi" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-semibold text-white transition gap-1.5">
                        <i class="fas fa-search-plus"></i> Perbesar Poster
                    </div>
                </a>
            </div>
            <div class="md:col-span-8 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-400/20 text-yellow-300 text-xs font-semibold">
                    <i class="fas fa-trophy"></i> EVENT BULAN BAHASA 2026
                </div>
                <h3 class="text-2xl font-bold text-white">Lomba Literasi Antar Kelas</h3>
                <p class="text-xs sm:text-sm text-yellow-200/90 italic">
                    &ldquo;Utamakan Bahasa Indonesia, Lestarikan Bahasa Daerah, Kuasai Bahasa Asing&rdquo;
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-2 text-xs">
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-gray-300 font-medium">Cerdas Cermat</div>
                        <div class="font-bold text-white">3 Orang / Kelas</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-gray-300 font-medium">Pidato</div>
                        <div class="font-bold text-white">1 Orang / Kelas</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-gray-300 font-medium">Puisi</div>
                        <div class="font-bold text-white">1 Orang / Kelas</div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-blue-200">
                    <span><i class="fas fa-calendar-alt text-yellow-400 mr-1"></i> 28 Oktober 2026</span>
                    <span><i class="fas fa-map-marker-alt text-yellow-400 mr-1"></i> Lab. Komputer SMPN 2 Tanggetada</span>
                    <span class="text-yellow-300 font-medium"><i class="fas fa-clock mr-1"></i> Batas Daftar: 24 Oktober 2026</span>
                </div>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="https://forms.gle/frfZEwZ9x2xwuiTM9" target="_blank" rel="noopener noreferrer"
                        class="px-5 py-2.5 bg-yellow-400 hover:bg-yellow-300 text-blue-950 font-bold rounded-xl text-xs sm:text-sm transition shadow flex items-center gap-2">
                        <i class="fas fa-edit"></i> Daftar via Google Form
                    </a>
                    <a href="https://wa.me/62853465489992?text=Halo%20Perpustakaan%20SMPN%202%20Tanggetada,%20saya%20ingin%20bertanya%20mengenai%20Lomba%20Literasi" target="_blank" rel="noopener noreferrer"
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5">
                        <i class="fab fa-whatsapp"></i> WA Panitia: 0853465489992
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Layanan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-14 h-14 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Jam Operasional</h4>
            <p class="text-sm text-gray-500">Senin � Jumat</p>
            <p class="text-sm font-semibold text-gray-700">08.00 � 14.00 WITA</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-books text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Koleksi Buku</h4>
            <p class="text-sm text-gray-500">Buku pelajaran, fiksi,</p>
            <p class="text-sm font-semibold text-gray-700">referensi &amp; ensiklopedia</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-14 h-14 bg-green-100 text-green-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-hand-holding-heart text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Peminjaman</h4>
            <p class="text-sm text-gray-500">Maks. 2 buku</p>
            <p class="text-sm font-semibold text-gray-700">selama 7 hari</p>
        </div>
    </div>

    {{-- Tata Tertib --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2">
            <i class="fas fa-list-ol text-amber-600"></i> Tata Tertib Perpustakaan
        </h3>
        <ul class="space-y-3 text-sm text-gray-600">
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</span>
                Wajib menunjukkan kartu pelajar saat meminjam buku.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</span>
                Jaga ketenangan dan kebersihan di ruang perpustakaan.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</span>
                Kembalikan buku tepat waktu untuk menghindari denda.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">4</span>
                Dilarang merobek, mencoret, atau merusak buku perpustakaan.
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">5</span>
                Tas dan jaket harap dititipkan di loker yang tersedia.
            </li>
        </ul>
    </div>

    <div class="mt-8 text-center text-gray-400 text-sm">
        <i class="fas fa-map-marker-alt mr-2 text-amber-500"></i>
        Perpustakaan terletak di Gedung Utama Lantai 1
    </div>
</div>
@endsection
