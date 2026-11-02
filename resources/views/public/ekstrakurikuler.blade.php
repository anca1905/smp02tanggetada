@extends('layouts.public')

@section('title', 'Ekstrakurikuler & OSIS')
@section('header', 'Pengembangan Minat & Bakat')
@section('subheader', 'Wadah pembentukan karakter, kepemimpinan, dan prestasi siswa di luar jam akademik')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-emerald-900 font-semibold">Ekstrakurikuler</span>
    </nav>

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-xl mb-10 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="space-y-3 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-semibold">
                <i class="fas fa-award"></i> Kegiatan Minat Bakat Siswa
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold">Ekstrakurikuler & OSIS</h2>
            <p class="text-emerald-100 text-sm sm:text-base leading-relaxed max-w-2xl">
                Temukan wadah untuk mengasah talenta, memperluas jejaring pertemanan, dan mencetak prestasi
                di bidang olahraga, kepemimpinan, seni budaya, serta keagamaan di {{ $site_settings['app_name'] ?? 'SMPN 2 Tanggetada' }}.
            </p>
        </div>
        <div class="shrink-0">
            <button onclick="openRegisterModal()" class="px-6 py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold rounded-2xl text-sm shadow-lg hover:shadow-xl transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Daftar Ekskul Online
            </button>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 mb-8 shadow-xs">
            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
            <div>
                <p class="font-bold text-sm">Pendaftaran Berhasil Dikirim!</p>
                <p class="text-xs">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl mb-8 shadow-xs">
            <div class="font-semibold text-sm mb-1 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-600"></i> Mohon lengkapi formulir pendaftaran:
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Category Pills Filter --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="{{ route('public.ekstrakurikuler') }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('category') || request('category') === 'all' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Semua Cabang
        </a>
        <a href="{{ route('public.ekstrakurikuler', ['category' => 'wajib']) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === 'wajib' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Wajib (Kepanduan)
        </a>
        <a href="{{ route('public.ekstrakurikuler', ['category' => 'olahraga']) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === 'olahraga' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Olahraga & O2SN
        </a>
        <a href="{{ route('public.ekstrakurikuler', ['category' => 'seni_budaya']) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === 'seni_budaya' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Seni, Musik & FLS2N
        </a>
        <a href="{{ route('public.ekstrakurikuler', ['category' => 'keagamaan']) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === 'keagamaan' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Keagamaan / Rohis
        </a>
        <a href="{{ route('public.ekstrakurikuler', ['category' => 'akademik']) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === 'akademik' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Klub Akademik & OSN
        </a>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
        @forelse($extracurriculars as $ekskul)
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition overflow-hidden flex flex-col justify-between group">
                <div>
                    {{-- Photo / Banner --}}
                    <div class="relative h-48 overflow-hidden bg-emerald-900">
                        @if($ekskul->photo_path)
                            <img src="{{ asset('storage/' . $ekskul->photo_path) }}" alt="{{ $ekskul->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-emerald-700 to-teal-900 flex items-center justify-center text-white">
                                <i class="fas fa-campground text-5xl opacity-40"></i>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md {{ $ekskul->category === 'wajib' ? 'bg-red-500 text-white' : 'bg-white/95 text-emerald-900' }}">
                                {{ $ekskul->category_label }}
                            </span>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-xs text-white text-xs px-2.5 py-1 rounded-full font-semibold">
                            <i class="fas fa-user-friends mr-1"></i> {{ $ekskul->accepted_registrations_count }} Anggota
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 space-y-3">
                        <h3 class="font-bold text-gray-900 text-lg leading-snug">{{ $ekskul->name }}</h3>

                        <div class="space-y-1.5 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-tie text-emerald-600 w-4"></i>
                                <span>Pembina: <strong>{{ $ekskul->instructor_name ?: 'Tim Guru Kesiswaan' }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-emerald-600 w-4"></i>
                                <span>Waktu: {{ $ekskul->schedule_day ?: 'Disesuaikan' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-emerald-600 w-4"></i>
                                <span>Lokasi: {{ $ekskul->location ?: 'Lingkungan Sekolah' }}</span>
                            </div>
                        </div>

                        @if($ekskul->description)
                            <p class="text-xs text-gray-500 leading-relaxed pt-2 line-clamp-3">
                                {{ $ekskul->description }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Action Footer --}}
                <div class="p-6 pt-0">
                    <button onclick="openRegisterModal({{ $ekskul->id }}, '{{ addslashes($ekskul->name) }}')"
                            class="w-full py-2.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border border-emerald-200 hover:border-transparent">
                        <i class="fas fa-pencil-alt"></i> Bergabung dengan Ekskul Ini
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-gray-400">
                <i class="fas fa-campground text-5xl mb-3 text-gray-300"></i>
                <h4 class="text-lg font-bold text-gray-700">Belum ada ekstrakurikuler pada kategori ini</h4>
                <p class="text-sm mt-1">Silakan pilih kategori lain atau periksa kembali nanti.</p>
            </div>
        @endforelse
    </div>

    {{-- Value Props / Kenapa Ikut Ekskul --}}
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-8 border border-emerald-100">
        <h3 class="text-xl font-bold text-emerald-950 mb-6 text-center">Manfaat Mengikuti Kegiatan Ekstrakurikuler di SMP</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-emerald-100/60">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold mb-3">
                    <i class="fas fa-medal"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-sm mb-1">Peluang Prestasi (O2SN, FLS2N, OSN)</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Siswa terpilih akan dibimbing secara intensif untuk mewakili sekolah di ajang kompetisi resmi tingkat kabupaten hingga nasional.</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-xs border border-emerald-100/60">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-sm mb-1">Kepemimpinan & Kerja Sama Tim</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Melatih keberanian berbicara di depan umum, empati, manajemen waktu, dan kepemimpinan organisasi sejak usia remaja.</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-xs border border-emerald-100/60">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold mb-3">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-sm mb-1">Sertifikat Jalur Prestasi SMA/SMK</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Sertifikat dan piagam keikutsertaan ekskul/prestasi dapat digunakan sebagai poin afirmasi dan jalur prestasi masuk ke SMA/SMK negeri impian.</p>
            </div>
        </div>
    </div>

</div>

{{-- MODAL PENDAFTARAN EKSTRAKURIKULER --}}
<div id="modal-public-register" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative my-8 animate-in fade-in">
        <button onclick="closeRegisterModal()" class="absolute top-5 right-5 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Formulir Pendaftaran Ekskul</h3>
                <p class="text-xs text-gray-500">Kirimkan minat Anda langsung ke pembina kegiatan</p>
            </div>
        </div>

        <form method="POST" action="{{ route('public.ekstrakurikuler.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilihan Ekstrakurikuler <span class="text-red-500">*</span></label>
                <select id="modal-select-ekskul" name="extracurricular_id" required
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Pilih Cabang Ekstrakurikuler --</option>
                    @foreach($extracurriculars as $e)
                        <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->category_label }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                <input type="text" name="student_name" placeholder="Sesuai nama di raport..." required
                       class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kelas / Rombel Siswa</label>
                    <input type="text" name="classroom_name" placeholder="Misal: VII-A, VIII-B"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor WhatsApp Siswa / Ortu <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" placeholder="08..." required
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alasan atau Pengalaman Sebelumnya</label>
                <textarea name="reason" rows="3" placeholder="Ceritakan motivasi Anda atau posisi/bakat yang sudah pernah dimiliki..."
                          class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2.5">
                <button type="button" onclick="closeRegisterModal()" class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRegisterModal(ekskulId = null, ekskulName = '') {
        if (ekskulId) {
            document.getElementById('modal-select-ekskul').value = ekskulId;
        }
        document.getElementById('modal-public-register')?.classList.remove('hidden');
    }

    function closeRegisterModal() {
        document.getElementById('modal-public-register')?.classList.add('hidden');
    }
</script>
@endsection
