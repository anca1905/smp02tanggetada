@extends('layouts.app')

@section('title', 'Galeri Prestasi Siswa')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-amber-700 via-orange-800 to-yellow-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-amber-200 text-xs font-medium mb-2">
                <i class="fas fa-trophy"></i> Hall of Fame & Prestasi Siswa
            </div>
            <h1 class="text-2xl font-bold">Galeri Prestasi & Rekam Jejak Siswa</h1>
            <p class="text-amber-100 text-sm mt-1">Dokumentasi kejuaraan, penghargaan akademik & non-akademik siswa yang tampil pada portal publik.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openModal('modal-add-achievement')" class="px-4 py-2.5 bg-white text-amber-900 hover:bg-amber-50 rounded-xl font-bold text-sm shadow transition flex items-center gap-2">
                <i class="fas fa-plus-circle text-amber-600"></i> Tambah Prestasi Baru
            </button>
            <a href="{{ route('public.prestasi') }}" target="_blank" class="px-4 py-2.5 bg-black/20 hover:bg-black/30 text-white rounded-xl font-medium text-sm transition flex items-center gap-2">
                <i class="fas fa-external-link-alt"></i> Lihat Halaman Publik
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
            <div class="font-semibold mb-1 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-600"></i> Terjadi kesalahan:
            </div>
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-trophy"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Prestasi</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Tingkat Kab.</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['kabupaten'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-medal"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Tingkat Prov.</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['provinsi'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-flag"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Nasional / Intl</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['nasional'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Sorotan Utama</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['featured'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('tu.achievements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama juara, siswa, ajang..."
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <select name="level" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Semua Tingkat --</option>
                    <option value="sekolah" {{ request('level') === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                    <option value="kecamatan" {{ request('level') === 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    <option value="kabupaten" {{ request('level') === 'kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                    <option value="provinsi" {{ request('level') === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="nasional" {{ request('level') === 'nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="internasional" {{ request('level') === 'internasional' ? 'selected' : '' }}>Internasional</option>
                </select>
            </div>
            <div>
                <select name="category" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Semua Bidang --</option>
                    <option value="akademik" {{ request('category') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="olahraga" {{ request('category') === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="seni_budaya" {{ request('category') === 'seni_budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                    <option value="keagamaan" {{ request('category') === 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                    <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'level', 'category']))
                    <a href="{{ route('tu.achievements.index') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Grid of Achievements --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($achievements as $ach)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    @if($ach->photo_path)
                        <img src="{{ asset('storage/' . $ach->photo_path) }}" alt="{{ $ach->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-50 to-orange-100 text-amber-500">
                            <i class="fas fa-trophy text-4xl mb-2"></i>
                            <span class="text-xs font-medium text-amber-700">SMP 02 Tanggetada</span>
                        </div>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-sm uppercase tracking-wider">
                            {{ $ach->rank ?: 'Juara' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-black/60 backdrop-blur-sm text-white">
                            {{ ucfirst($ach->level) }}
                        </span>
                    </div>

                    {{-- Featured toggle --}}
                    <form action="{{ route('tu.achievements.toggle-featured', $ach) }}" method="POST" class="absolute top-3 right-3">
                        @csrf
                        <button type="submit" class="p-2 rounded-full {{ $ach->is_featured ? 'bg-amber-400 text-amber-900 shadow-lg' : 'bg-black/40 text-white hover:bg-black/60' }} transition" title="{{ $ach->is_featured ? 'Hapus dari sorotan utama' : 'Jadikan sorotan utama' }}">
                            <i class="fas fa-star text-xs"></i>
                        </button>
                    </form>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">
                            {{ str_replace('_', ' ', $ach->category) }}
                        </div>
                        <h3 class="font-bold text-gray-800 text-base leading-snug line-clamp-2 mb-2">
                            {{ $ach->title }}
                        </h3>
                        <div class="space-y-1.5 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-graduate w-4 text-gray-400"></i>
                                <span class="font-semibold text-gray-800">{{ $ach->student_name }}</span>
                                @if($ach->student && $ach->student->classroom)
                                    <span class="text-gray-500">({{ $ach->student->classroom->name }})</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-alt w-4 text-gray-400"></i>
                                <span>{{ $ach->event_name }} • {{ $ach->event_date?->format('d M Y') ?? 'Tahun Ini' }}</span>
                            </div>
                        </div>
                        @if($ach->description)
                            <p class="text-xs text-gray-500 mt-3 line-clamp-2 italic">
                                {{ $ach->description }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400">
                            {{ $ach->created_at->diffForHumans() }}
                        </span>
                        <div class="flex items-center gap-2">
                            <button onclick='editAchievement(@json($ach))' class="px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('tu.achievements.destroy', $ach) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan prestasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-100">
                <i class="fas fa-trophy text-5xl mb-3 text-gray-300"></i>
                <h3 class="text-lg font-bold text-gray-700">Belum Ada Prestasi Terdata</h3>
                <p class="text-sm mt-1">Mulai catat prestasi dan kejuaraan membanggakan siswa-siswi Anda sekarang.</p>
                <button onclick="openModal('modal-add-achievement')" class="mt-4 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold transition">
                    + Tambah Prestasi Baru
                </button>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $achievements->links() }}
    </div>
</div>

{{-- MODAL TAMBAH PRESTASI --}}
<div id="modal-add-achievement" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i> Tambah Prestasi Siswa
            </h3>
            <button onclick="closeModal('modal-add-achievement')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('tu.achievements.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul Kejuaraan / Prestasi <span class="text-red-500">*</span></label>
                <input type="text" name="title" placeholder="Contoh: Juara 1 Olimpiade Sains Nasional (OSN) IPA" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih Siswa (Opsional)</label>
                    <select name="student_id" id="add-ach-student-select" onchange="syncStudentName()" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Siswa Terdaftar / Manual --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}" data-name="{{ $st->student_name }}">{{ $st->student_name }} ({{ $st->classroom->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Siswa / Tim <span class="text-red-500">*</span></label>
                    <input type="text" name="student_name" id="add-ach-student-name" placeholder="Nama peraih / Tim" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Peringkat / Juara</label>
                    <input type="text" name="rank" placeholder="Juara 1 / Medali Emas" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tingkat <span class="text-red-500">*</span></label>
                    <select name="level" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="kabupaten">Kabupaten</option>
                        <option value="provinsi">Provinsi</option>
                        <option value="nasional">Nasional</option>
                        <option value="kecamatan">Kecamatan</option>
                        <option value="sekolah">Sekolah</option>
                        <option value="internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Bidang <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="akademik">Akademik</option>
                        <option value="olahraga">Olahraga</option>
                        <option value="seni_budaya">Seni Budaya</option>
                        <option value="keagamaan">Keagamaan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Ajang / Penyelenggara <span class="text-red-500">*</span></label>
                    <input type="text" name="event_name" placeholder="Misal: FLS2N Disdik 2026" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="event_date" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Foto Dokumentasi / Sertifikat</label>
                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-[11px] text-gray-400 mt-1">Maks. 4MB (Format: JPG, PNG, WebP)</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Tambahan</label>
                <textarea name="description" rows="2.5" placeholder="Keterangan singkat seputar kejuaraan atau prestasi yang diraih..." class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="add-featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="add-featured" class="text-xs font-medium text-gray-700">Tampilkan di Sorotan Utama (*Featured* / Banner Publik)</label>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-add-achievement')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold transition">Simpan Prestasi</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PRESTASI --}}
<div id="modal-edit-achievement" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i> Edit Data Prestasi
            </h3>
            <button onclick="closeModal('modal-edit-achievement')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form id="form-edit-achievement" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul Kejuaraan / Prestasi <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="edit-ach-title" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Siswa Terdaftar</label>
                    <select name="student_id" id="edit-ach-student-id" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Siswa Terdaftar / Manual --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->student_name }} ({{ $st->classroom->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Siswa / Tim <span class="text-red-500">*</span></label>
                    <input type="text" name="student_name" id="edit-ach-student-name" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Peringkat</label>
                    <input type="text" name="rank" id="edit-ach-rank" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tingkat</label>
                    <select name="level" id="edit-ach-level" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="kabupaten">Kabupaten</option>
                        <option value="provinsi">Provinsi</option>
                        <option value="nasional">Nasional</option>
                        <option value="kecamatan">Kecamatan</option>
                        <option value="sekolah">Sekolah</option>
                        <option value="internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Bidang</label>
                    <select name="category" id="edit-ach-category" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="akademik">Akademik</option>
                        <option value="olahraga">Olahraga</option>
                        <option value="seni_budaya">Seni Budaya</option>
                        <option value="keagamaan">Keagamaan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Ajang / Kegiatan</label>
                    <input type="text" name="event_name" id="edit-ach-event-name" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal</label>
                    <input type="date" name="event_date" id="edit-ach-event-date" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Ganti Foto Dokumentasi (Opsional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Tambahan</label>
                <textarea name="description" id="edit-ach-description" rows="2.5" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="edit-ach-featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="edit-ach-featured" class="text-xs font-medium text-gray-700">Tampilkan di Sorotan Utama (*Featured*)</label>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-edit-achievement')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function syncStudentName() {
    const select = document.getElementById('add-ach-student-select');
    const opt = select.options[select.selectedIndex];
    if (opt && opt.dataset.name) {
        document.getElementById('add-ach-student-name').value = opt.dataset.name;
    }
}

function editAchievement(ach) {
    const form = document.getElementById('form-edit-achievement');
    form.action = `/tu/achievements/${ach.id}`;

    document.getElementById('edit-ach-title').value = ach.title;
    document.getElementById('edit-ach-student-id').value = ach.student_id || '';
    document.getElementById('edit-ach-student-name').value = ach.student_name;
    document.getElementById('edit-ach-rank').value = ach.rank || '';
    document.getElementById('edit-ach-level').value = ach.level;
    document.getElementById('edit-ach-category').value = ach.category;
    document.getElementById('edit-ach-event-name').value = ach.event_name;
    document.getElementById('edit-ach-event-date').value = ach.event_date ? ach.event_date.split('T')[0] : '';
    document.getElementById('edit-ach-description').value = ach.description || '';
    document.getElementById('edit-ach-featured').checked = !!ach.is_featured;

    openModal('modal-edit-achievement');
}
</script>
@endsection
