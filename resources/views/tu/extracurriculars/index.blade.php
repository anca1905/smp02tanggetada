@extends('layouts.app')

@section('title', 'Ekstrakurikuler & OSIS')

@section('content')
<div class="space-y-6">

    {{-- Top Header / Hero --}}
    <div class="rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4" style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #064e3b 100%); background-color: #065f46;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-medium mb-2">
                <i class="fas fa-campground"></i> Kesiswaan & Pengembangan Bakat Siswa
            </div>
            <h1 class="text-2xl font-bold">Ekstrakurikuler & Organisasi Siswa (OSIS)</h1>
            <p class="text-sm mt-1" style="color: #d1fae5;">Kelola cabang kegiatan minat bakat, jadwal latihan pembina, serta verifikasi berkas pendaftaran siswa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openModal('modal-add-reg')" class="px-4 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center gap-2" style="background-color: #2563eb; color: #ffffff;">
                <i class="fas fa-user-plus"></i> Daftarkan Siswa
            </button>
            <button onclick="openModal('modal-add-ekskul')" class="px-4 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center gap-2" style="background-color: #16a34a; color: #ffffff;">
                <i class="fas fa-plus"></i> Tambah Kegiatan Ekskul
            </button>
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
                <i class="fas fa-exclamation-triangle text-red-600"></i> Mohon periksa kembali formulir:
            </div>
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-cubes"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Cabang Ekskul</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_ekskul'] }}</p>
                <p class="text-[11px] text-emerald-600 font-medium">Aktif Dibuka</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Pendaftaran</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_registrations'] }}</p>
                <p class="text-[11px] text-blue-600 font-medium">Siswa Terdaftar</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_registrations'] }}</p>
                <p class="text-[11px] text-amber-600 font-medium">Perlu Disetujui</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Anggota Diterima</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['accepted_registrations'] }}</p>
                <p class="text-[11px] text-teal-600 font-medium">Telah Bergabung</p>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex border-b border-gray-200 gap-4">
        <a href="{{ route('tu.extracurriculars.index', ['tab' => 'list']) }}"
           class="pb-3 px-2 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'list' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fas fa-list-alt"></i> Daftar Cabang Ekstrakurikuler
            <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800 font-bold">{{ $stats['total_ekskul'] }}</span>
        </a>
        <a href="{{ route('tu.extracurriculars.index', ['tab' => 'registrations']) }}"
           class="pb-3 px-2 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'registrations' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fas fa-user-check"></i> Verifikasi Data Pendaftar Siswa
            <span class="px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800 font-bold">{{ $stats['pending_registrations'] }}</span>
        </a>
    </div>

    {{-- TAB 1: DAFTAR EKSKUL --}}
    @if($activeTab === 'list')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('tu.extracurriculars.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="hidden" name="tab" value="list">
                
                <div class="sm:col-span-2 relative">
                    <input type="text" name="ekskul_search" value="{{ request('ekskul_search') }}"
                           placeholder="Cari cabang ekskul, nama pembina, atau lokasi latihan..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                </div>

                <div class="flex gap-2">
                    <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Kategori</option>
                        <option value="wajib" {{ request('category') === 'wajib' ? 'selected' : '' }}>Wajib (Kepanduan)</option>
                        <option value="olahraga" {{ request('category') === 'olahraga' ? 'selected' : '' }}>Olahraga & Prestasi</option>
                        <option value="seni_budaya" {{ request('category') === 'seni_budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                        <option value="keagamaan" {{ request('category') === 'keagamaan' ? 'selected' : '' }}>Keagamaan / Rohis</option>
                        <option value="akademik" {{ request('category') === 'akademik' ? 'selected' : '' }}>Klub Akademik / OSN</option>
                        <option value="pilihan" {{ request('category') === 'pilihan' ? 'selected' : '' }}>Pilihan Umum</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-medium transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['ekskul_search', 'category']))
                        <a href="{{ route('tu.extracurriculars.index', ['tab' => 'list']) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Grid of Extracurriculars --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($extracurriculars as $ekskul)
                    <div class="border border-gray-100 rounded-2xl bg-white shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between">
                        <div>
                            @if($ekskul->photo_path)
                                <img src="{{ asset('storage/' . $ekskul->photo_path) }}" alt="{{ $ekskul->name }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-36 bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white">
                                    <i class="fas fa-campground text-4xl opacity-80"></i>
                                </div>
                            @endif

                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $ekskul->category === 'wajib' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $ekskul->category_label }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-500">
                                        <i class="fas fa-users text-emerald-600"></i> {{ $ekskul->accepted_registrations_count }} Anggota
                                    </span>
                                </div>

                                <h3 class="font-bold text-gray-900 text-base leading-snug">{{ $ekskul->name }}</h3>

                                <div class="text-xs text-gray-600 space-y-1 pt-1">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-user-tie text-emerald-600 w-4"></i>
                                        <span>Pembina: <strong>{{ $ekskul->instructor_name ?: '-' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-calendar-day text-emerald-600 w-4"></i>
                                        <span>Jadwal: {{ $ekskul->schedule_day ?: '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-emerald-600 w-4"></i>
                                        <span>Tempat: {{ $ekskul->location ?: '-' }}</span>
                                    </div>
                                </div>

                                @if($ekskul->description)
                                    <p class="text-xs text-gray-500 line-clamp-2 pt-1">{{ $ekskul->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 pt-0 border-t border-gray-100 flex items-center justify-between mt-3">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $ekskul->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                                <span class="w-2 h-2 rounded-full {{ $ekskul->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                {{ $ekskul->is_active ? 'Pendaftaran Buka' : 'Nonaktif' }}
                            </span>

                            <div class="flex items-center gap-2">
                                <button onclick="openEditEkskulModal({{ json_encode($ekskul) }})" class="p-1.5 text-blue-600 hover:text-blue-800 transition" title="Edit Data Ekskul">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('tu.extracurriculars.destroy', $ekskul) }}" onsubmit="return confirm('Hapus cabang ekskul ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition" title="Hapus Ekskul">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-gray-400">
                        <i class="fas fa-campground text-4xl mb-2 text-gray-300"></i>
                        <p>Belum ada kegiatan ekstrakurikuler yang terdaftar.</p>
                    </div>
                @endforelse
            </div>

            <div class="pt-2">
                {{ $extracurriculars->links() }}
            </div>
        </div>
    @endif

    {{-- TAB 2: DATA PENDAFTAR SISWA --}}
    @if($activeTab === 'registrations')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            {{-- Filter & Search --}}
            <form method="GET" action="{{ route('tu.extracurriculars.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="hidden" name="tab" value="registrations">
                
                <div class="sm:col-span-2 relative">
                    <input type="text" name="reg_search" value="{{ request('reg_search') }}"
                           placeholder="Cari nama siswa, nomor HP/WA, atau kelas..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                </div>

                <div>
                    <select name="ekskul_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Cabang Ekskul</option>
                        @foreach($allEkskuls as $ak)
                            <option value="{{ $ak->id }}" {{ request('ekskul_id') == $ak->id ? 'selected' : '' }}>{{ $ak->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <select name="reg_status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('reg_status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="accepted" {{ request('reg_status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ request('reg_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-medium transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['reg_search', 'ekskul_id', 'reg_status']))
                        <a href="{{ route('tu.extracurriculars.index', ['tab' => 'registrations']) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Registrations Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-y border-gray-200">
                        <tr>
                            <th class="py-3.5 px-4">Nama Siswa & Kelas</th>
                            <th class="py-3.5 px-4">Ekskul Dipilih</th>
                            <th class="py-3.5 px-4">Alasan Bergabung</th>
                            <th class="py-3.5 px-4 text-center">Kontak WhatsApp</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($registrations as $reg)
                            <tr class="hover:bg-emerald-50/40 transition">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-900">{{ $reg->student_name }}</div>
                                    <div class="text-xs text-gray-500 font-medium">{{ $reg->classroom_name ?: ($reg->student?->classroom?->name ?? 'Kelas Siswa') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-800">{{ $reg->extracurricular?->name ?? '-' }}</div>
                                    <span class="text-[11px] text-emerald-700 font-medium">{{ $reg->extracurricular?->category_label }}</span>
                                </td>
                                <td class="py-3 px-4 max-w-xs">
                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $reg->reason ?: 'Tidak mencantumkan alasan khusus.' }}</p>
                                    @if($reg->notes)
                                        <div class="text-[10px] text-amber-700 mt-0.5">Catatan: {{ $reg->notes }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @php
                                        $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $reg->phone));
                                        $waText = "Halo {$reg->student_name},\nKami dari Kesiswaan/Pembina Ekstrakurikuler {$reg->extracurricular?->name} SMPN 2 Tanggetada mengonfirmasi pendaftaran Anda.";
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200 transition"
                                       title="Hubungi via WhatsApp">
                                        <i class="fab fa-whatsapp text-emerald-600"></i> {{ $reg->phone }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    @if($reg->status === 'pending')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-amber-100 text-amber-800 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-hourglass-half"></i> Menunggu
                                        </span>
                                    @elseif($reg->status === 'accepted')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-100 text-emerald-800 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-check-circle"></i> Diterima
                                        </span>
                                    @elseif($reg->status === 'rejected')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick="openStatusModal({{ $reg->id }}, '{{ addslashes($reg->student_name) }}', '{{ $reg->status }}', '{{ addslashes($reg->notes ?? '') }}')"
                                                class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition shadow-xs"
                                                title="Ubah Status / Konfirmasi">
                                            <i class="fas fa-check"></i> Verifikasi
                                        </button>
                                        <form method="POST" action="{{ route('tu.extracurriculars.registrations.destroy', $reg) }}" onsubmit="return confirm('Hapus data pendaftaran siswa ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition" title="Hapus Data">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    <i class="fas fa-user-friends text-3xl mb-2 text-gray-300"></i>
                                    <p>Belum ada siswa yang mendaftar ke ekstrakurikuler.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $registrations->links() }}
            </div>
        </div>
    @endif

</div>

{{-- MODAL 1: TAMBAH EKSKUL --}}
<div id="modal-add-ekskul" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative my-8 animate-in fade-in">
        <button onclick="closeModal('modal-add-ekskul')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Tambah Cabang Ekstrakurikuler</h3>
                <p class="text-xs text-gray-500">Daftarkan ekstrakurikuler atau kegiatan minat bakat baru</p>
            </div>
        </div>

        <form method="POST" action="{{ route('tu.extracurriculars.store') }}" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="Misal: Pramuka, PMR Madya, Futsal, Tari Tradisional..." required
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="pilihan">Pilihan Umum</option>
                        <option value="wajib">Wajib (Kepanduan)</option>
                        <option value="olahraga">Olahraga & Prestasi</option>
                        <option value="seni_budaya">Seni & Budaya</option>
                        <option value="keagamaan">Keagamaan / Rohis</option>
                        <option value="akademik">Klub Akademik / OSN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pembina / Pelatih</label>
                    <input type="text" name="instructor_name" placeholder="Nama guru pembina..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Hari & Waktu Latihan</label>
                    <input type="text" name="schedule_day" placeholder="Jumat, 15:30 - 17:30 WITA"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Latihan</label>
                    <input type="text" name="location" placeholder="Lapangan / Lab / Aula..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Foto Poster / Dokumentasi Kegiatan</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi & Syarat Mengikuti</label>
                <textarea name="description" rows="2" placeholder="Uraian manfaat, target lomba (FLS2N, O2SN, dll)..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded text-emerald-600 focus:ring-emerald-500">
                <label for="is_active" class="text-xs font-medium text-gray-700">Buka Pendaftaran Online untuk Siswa</label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-add-ekskul')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow">Simpan Ekskul</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: EDIT EKSKUL --}}
<div id="modal-edit-ekskul" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative my-8 animate-in fade-in">
        <button onclick="closeModal('modal-edit-ekskul')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Perbarui Ekstrakurikuler</h3>
                <p class="text-xs text-gray-500" id="edit-ekskul-name-label"></p>
            </div>
        </div>

        <form id="form-edit-ekskul" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                <input type="text" id="edit-ekskul-name" name="name" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="edit-ekskul-category" name="category" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="pilihan">Pilihan Umum</option>
                        <option value="wajib">Wajib (Kepanduan)</option>
                        <option value="olahraga">Olahraga & Prestasi</option>
                        <option value="seni_budaya">Seni & Budaya</option>
                        <option value="keagamaan">Keagamaan / Rohis</option>
                        <option value="akademik">Klub Akademik / OSN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pembina / Pelatih</label>
                    <input type="text" id="edit-ekskul-instructor" name="instructor_name"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Hari & Waktu Latihan</label>
                    <input type="text" id="edit-ekskul-schedule" name="schedule_day"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Latihan</label>
                    <input type="text" id="edit-ekskul-location" name="location"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Foto Poster (Opsional)</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea id="edit-ekskul-description" name="description" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="edit-ekskul-active" name="is_active" value="1" class="rounded text-emerald-600 focus:ring-emerald-500">
                <label for="edit-ekskul-active" class="text-xs font-medium text-gray-700">Buka Pendaftaran Online</label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-edit-ekskul')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 3: DAFTARKAN SISWA SECARA MANUAL (ADMIN TU) --}}
<div id="modal-add-reg" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative animate-in fade-in">
        <button onclick="closeModal('modal-add-reg')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Pendaftaran Siswa Baru ke Ekskul</h3>
                <p class="text-xs text-gray-500">Didaftarkan langsung oleh staf kesiswaan/TU</p>
            </div>
        </div>

        <form method="POST" action="{{ route('tu.extracurriculars.registrations.store') }}" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Cabang Ekskul <span class="text-red-500">*</span></label>
                <select name="extracurricular_id" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Cabang Ekskul --</option>
                    @foreach($allEkskuls as $ak)
                        <option value="{{ $ak->id }}">{{ $ak->name }} ({{ $ak->category_label }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Siswa (Database)</label>
                <select id="select-student-id" name="student_id" onchange="autoFillStudentData(this)" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Siswa atau Isi Manual --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" data-name="{{ $st->student_name }}" data-class="{{ $st->classroom?->name ?? '' }}" data-phone="{{ $st->phone_number ?? '' }}">
                            {{ $st->nis }} - {{ $st->student_name }} ({{ $st->classroom?->name ?? 'Kelas -' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Siswa <span class="text-red-500">*</span></label>
                    <input type="text" id="reg-student-name" name="student_name" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kelas</label>
                    <input type="text" id="reg-classroom-name" name="classroom_name" placeholder="Kelas VII-A"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" id="reg-phone" name="phone" placeholder="08..." required
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan / Minat Bergabung</label>
                <textarea name="reason" rows="2" placeholder="Motivasi ikut kegiatan ekskul..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-add-reg')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow">Daftarkan & Terima</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 4: VERIFIKASI STATUS PENDAFTARAN --}}
<div id="modal-status-reg" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative animate-in fade-in">
        <button onclick="closeModal('modal-status-reg')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Verifikasi Pendaftaran Siswa</h3>
                <p class="text-xs text-gray-500" id="reg-info-label"></p>
            </div>
        </div>

        <form id="form-status-reg" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ubah Status <span class="text-red-500">*</span></label>
                <select id="modal-reg-status-select" name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="accepted">Diterima (Resmi Anggota)</option>
                    <option value="rejected">Ditolak / Belum Memenuhi Syarat</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Verifikasi</label>
                <textarea id="modal-reg-notes" name="notes" rows="3" placeholder="Alasan penolakan / instruksi pertemuan pertama..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-status-reg')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow">Simpan Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
    }

    function openStatusModal(regId, studentName, currentStatus, currentNotes) {
        document.getElementById('reg-info-label').innerText = 'Siswa: ' + studentName;
        document.getElementById('form-status-reg').action = '/tu/extracurriculars/registrations/' + regId + '/status';
        document.getElementById('modal-reg-status-select').value = currentStatus;
        document.getElementById('modal-reg-notes').value = currentNotes || '';
        openModal('modal-status-reg');
    }

    function openEditEkskulModal(ekskul) {
        document.getElementById('edit-ekskul-name-label').innerText = ekskul.name;
        document.getElementById('form-edit-ekskul').action = '/tu/extracurriculars/' + ekskul.id;
        document.getElementById('edit-ekskul-name').value = ekskul.name || '';
        document.getElementById('edit-ekskul-category').value = ekskul.category || 'pilihan';
        document.getElementById('edit-ekskul-instructor').value = ekskul.instructor_name || '';
        document.getElementById('edit-ekskul-schedule').value = ekskul.schedule_day || '';
        document.getElementById('edit-ekskul-location').value = ekskul.location || '';
        document.getElementById('edit-ekskul-description').value = ekskul.description || '';
        document.getElementById('edit-ekskul-active').checked = !!ekskul.is_active;
        openModal('modal-edit-ekskul');
    }

    function autoFillStudentData(selectElem) {
        const option = selectElem.options[selectElem.selectedIndex];
        if (option && option.value) {
            document.getElementById('reg-student-name').value = option.getAttribute('data-name') || '';
            document.getElementById('reg-classroom-name').value = option.getAttribute('data-class') || '';
            document.getElementById('reg-phone').value = option.getAttribute('data-phone') || '';
        }
    }
</script>
@endsection
