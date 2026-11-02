@extends('layouts.app')

@section('title', 'Bimbingan Konseling & Kedisiplinan')

@section('content')
<div class="space-y-6">

    {{-- Top Header / Hero --}}
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-blue-200 text-xs font-medium mb-2">
                <i class="fas fa-user-shield"></i> Layanan BK & Tata Tertib Siswa
            </div>
            <h1 class="text-2xl font-bold">Modul Bimbingan Konseling & Poin Kedisiplinan</h1>
            <p class="text-blue-200 text-sm mt-1">Pencatatan pelanggaran, apresiasi prestasi, rekap skor kedisiplinan, dan pembinaan berkala siswa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openModal('modal-add-record')" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Catat Kasus / Konseling
            </button>
            <button onclick="openModal('modal-add-category')" class="px-4 py-2.5 bg-blue-700/80 hover:bg-blue-600 text-white rounded-xl font-medium text-sm transition flex items-center gap-2">
                <i class="fas fa-tags"></i> Tambah Kategori
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
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Kasus Pelanggaran</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_violations'] }}</p>
                <p class="text-[11px] text-red-500 font-medium">Tercatat di sistem</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-award"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Catatan Prestasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_achievements'] }}</p>
                <p class="text-[11px] text-amber-600 font-medium">Poin penghargaan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Dalam Pembinaan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_cases'] }}</p>
                <p class="text-[11px] text-blue-500 font-medium">Pending & Proses</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-users-slash"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Siswa Terbina</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['students_with_violations'] }}</p>
                <p class="text-[11px] text-purple-600 font-medium">Mempunyai poin pelanggaran</p>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-200 px-6 pt-4 flex gap-8">
            <a href="{{ route('tu.counseling.index', ['tab' => 'records']) }}"
               class="pb-4 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'records' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-clipboard-list"></i> Riwayat Kasus & Konseling
            </a>
            <a href="{{ route('tu.counseling.index', ['tab' => 'students']) }}"
               class="pb-4 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'students' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-tachometer-alt"></i> Rekap Skor Kedisiplinan Siswa
            </a>
            <a href="{{ route('tu.counseling.index', ['tab' => 'categories']) }}"
               class="pb-4 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'categories' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-sliders-h"></i> Master Kategori & Tata Tertib
            </a>
        </div>

        <div class="p-6">
            {{-- TAB 1: RIWAYAT KASUS & KONSELING --}}
            @if($activeTab === 'records')
                {{-- Filter Bar --}}
                <form method="GET" action="{{ route('tu.counseling.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <input type="hidden" name="tab" value="records">

                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa / kasus..."
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <select name="type" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Semua Tipe --</option>
                            <option value="violation" {{ request('type') === 'violation' ? 'selected' : '' }}>Pelanggaran (Poin Negatif)</option>
                            <option value="achievement" {{ request('type') === 'achievement' ? 'selected' : '' }}>Prestasi / Apresiasi</option>
                        </select>
                    </div>
                    <div>
                        <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Semua Status --</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress (Pembinaan)</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved (Selesai)</option>
                        </select>
                    </div>
                    <div>
                        <select name="classroom_id" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classrooms as $cls)
                                <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search', 'type', 'status', 'classroom_id']))
                            <a href="{{ route('tu.counseling.index', ['tab' => 'records']) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Records Table --}}
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                            <tr>
                                <th class="py-3 px-4 text-left">Tanggal</th>
                                <th class="py-3 px-4 text-left">Siswa & Kelas</th>
                                <th class="py-3 px-4 text-left">Judul & Kategori</th>
                                <th class="py-3 px-4 text-center">Tipe / Poin</th>
                                <th class="py-3 px-4 text-left">Tindakan / Solusi</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-left">Pelapor</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($records as $rec)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="py-3 px-4 whitespace-nowrap text-gray-600">
                                        <div class="font-medium text-gray-900">{{ $rec->incident_date?->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $rec->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-800">{{ $rec->student->student_name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">NIS: {{ $rec->student->nis ?? '-' }} • Kelas: {{ $rec->student->classroom->name ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $rec->title }}</div>
                                        <div class="text-xs text-blue-600">{{ $rec->category->name ?? 'Kategori Umum' }}</div>
                                        @if($rec->description)
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-1 italic">{{ $rec->description }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if($rec->type === 'violation')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                <i class="fas fa-minus-circle mr-1 text-[10px]"></i> +{{ $rec->point }} Poin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <i class="fas fa-star mr-1 text-[10px]"></i> +{{ $rec->point }} Prestasi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 max-w-xs">
                                        {{ $rec->action_taken ?: '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if($rec->status === 'resolved')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                            </span>
                                        @elseif($rec->status === 'in_progress')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fas fa-spinner mr-1 animate-spin"></i> Pembinaan
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-300">
                                                <i class="fas fa-clock mr-1"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 text-xs">
                                        {{ $rec->reported_by ?: '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            @php
                                                $parentPhone = $rec->student?->parent_phone ?: $rec->student?->phone_number;
                                                $cleanParentPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $parentPhone ?? ''));
                                                $waText = "Yth. Orang Tua dari siswa {$rec->student?->student_name} ({$rec->student?->classroom?->name}),\nKami dari Guru Bimbingan Konseling (BK) SMPN 2 Tanggetada menginformasikan catatan kedisiplinan siswa:\n- Kasus: {$rec->title}\n- Poin: +{$rec->point}\n- Tindakan/Pembinaan: " . ($rec->action_taken ?: 'Konseling Tatap Muka') . "\nMohon kerja sama Bapak/Ibu dalam membimbing ananda di rumah. Terima kasih.";
                                            @endphp
                                            @if($cleanParentPhone)
                                                <a href="https://wa.me/{{ $cleanParentPhone }}?text={{ urlencode($waText) }}" target="_blank"
                                                   class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Kirim Notifikasi WA ke Orang Tua">
                                                    <i class="fab fa-whatsapp text-base"></i>
                                                </a>
                                            @endif
                                            <button onclick='editRecord(@json($rec))' class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('tu.counseling.record.destroy', $rec) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan konseling ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-gray-400">
                                        <i class="fas fa-folder-open text-3xl mb-2 block"></i>
                                        Belum ada riwayat catatan konseling atau pelanggaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $records->links() }}
                </div>
            @endif

            {{-- TAB 2: REKAP POIN KEDISIPLINAN SISWA --}}
            @if($activeTab === 'students')
                {{-- Info Box / Standard Rules --}}
                <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm">
                    <p class="font-bold flex items-center gap-2 mb-1.5">
                        <i class="fas fa-info-circle text-blue-600 text-base"></i> Pedoman Akumulasi Poin Pelanggaran & Tindak Lanjut Sekolah:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-xs">
                        <div class="bg-white/70 p-2.5 rounded-lg border border-blue-100">
                            <span class="font-bold text-emerald-700">0 - 15 Poin:</span> Peringatan Lisan & Pembinaan Wali Kelas
                        </div>
                        <div class="bg-white/70 p-2.5 rounded-lg border border-blue-100">
                            <span class="font-bold text-amber-700">16 - 30 Poin:</span> Surat Peringatan 1 (SP 1) dari BK
                        </div>
                        <div class="bg-white/70 p-2.5 rounded-lg border border-blue-100">
                            <span class="font-bold text-orange-700">31 - 50 Poin:</span> SP 2 & Panggilan Orang Tua ke Sekolah
                        </div>
                        <div class="bg-white/70 p-2.5 rounded-lg border border-blue-100">
                            <span class="font-bold text-red-700">> 50 Poin:</span> SP 3, Skorsing, atau Konferensi Kasus
                        </div>
                    </div>
                </div>

                {{-- Filter Bar --}}
                <form method="GET" action="{{ route('tu.counseling.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input type="hidden" name="tab" value="students">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau nama siswa..."
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <select name="classroom_id" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classrooms as $cls)
                                <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">
                            <i class="fas fa-search mr-1"></i> Tampilkan
                        </button>
                        @if(request()->anyFilled(['search', 'classroom_id']))
                            <a href="{{ route('tu.counseling.index', ['tab' => 'students']) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Students Table --}}
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                            <tr>
                                <th class="py-3 px-4 text-left">Siswa & NIS</th>
                                <th class="py-3 px-4 text-center">Kelas</th>
                                <th class="py-3 px-4 text-center">Kasus Pelanggaran</th>
                                <th class="py-3 px-4 text-center">Total Poin Pelanggaran</th>
                                <th class="py-3 px-4 text-center">Poin Prestasi</th>
                                <th class="py-3 px-4 text-left">Status Kedisiplinan & Rekomendasi</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($studentsWithPoints as $st)
                                @php
                                    $viol = $st->total_violation_points ?? 0;
                                    $ach = $st->total_achievement_points ?? 0;
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-800">{{ $st->student_name }}</div>
                                        <div class="text-xs text-gray-500">NIS: {{ $st->nis }} • NISN: {{ $st->nisn ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center font-medium text-gray-700">
                                        {{ $st->classroom->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center font-medium">
                                        <span class="text-gray-700">{{ $st->violation_count }}x pelanggaran</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($viol > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $viol >= 50 ? 'bg-red-600 text-white' : ($viol >= 30 ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $viol }} Poin
                                            </span>
                                        @else
                                            <span class="text-emerald-600 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded-full">0 (Bersih)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($ach > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                +{{ $ach }} Poin
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($viol === 0)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-medium">
                                                <i class="fas fa-shield-alt text-emerald-500"></i> Disiplin Sangat Baik
                                            </span>
                                        @elseif($viol <= 15)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-blue-700 font-medium">
                                                <i class="fas fa-exclamation-circle text-blue-500"></i> Peringatan Lisan / Wali Kelas
                                            </span>
                                        @elseif($viol <= 30)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-amber-700 font-bold">
                                                <i class="fas fa-envelope-open-text text-amber-500"></i> Tahap SP 1 (Surat Peringatan 1)
                                            </span>
                                        @elseif($viol <= 50)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-orange-700 font-bold">
                                                <i class="fas fa-user-friends text-orange-500"></i> Tahap SP 2 & Panggilan Orang Tua
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs text-red-700 font-bold animate-pulse">
                                                <i class="fas fa-radiation text-red-600"></i> Tahap SP 3 & Sidang Disiplin
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <button onclick="openRecordForStudent('{{ $st->id }}', '{{ addslashes($st->student_name) }}')"
                                                class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition">
                                            + Catat BK
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-gray-400">
                                        Tidak ada data siswa ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $studentsWithPoints->links() }}
                </div>
            @endif

            {{-- TAB 3: MASTER KATEGORI --}}
            @if($activeTab === 'categories')
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Master Kategori Pelanggaran & Tata Tertib</h3>
                        <p class="text-xs text-gray-500">Konfigurasi jenis pelanggaran dan poin default yang berlaku di SMP 02.</p>
                    </div>
                    <button onclick="openModal('modal-add-category')" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                            <tr>
                                <th class="py-3 px-4 text-left">Nama Kategori</th>
                                <th class="py-3 px-4 text-center">Tipe</th>
                                <th class="py-3 px-4 text-center">Poin Default</th>
                                <th class="py-3 px-4 text-left">Deskripsi / Keterangan</th>
                                <th class="py-3 px-4 text-center">Jumlah Kasus</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($categories as $cat)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="py-3 px-4 font-semibold text-gray-800">
                                        {{ $cat->name }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($cat->type === 'violation')
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Pelanggaran</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Prestasi</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold {{ $cat->type === 'violation' ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $cat->point }} Poin
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 text-xs">
                                        {{ $cat->description ?: '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center text-xs font-medium text-gray-600">
                                        {{ $cat->records_count }} catatan
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <form action="{{ route('tu.counseling.category.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Catatan yang menggunakan kategori ini akan tetap tersimpan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-400">Belum ada kategori yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL TAMBAH CATATAN KASUS / KONSELING --}}
<div id="modal-add-record" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i> Tambah Catatan Konseling / Kasus
            </h3>
            <button onclick="closeModal('modal-add-record')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('tu.counseling.record.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih Siswa <span class="text-red-500">*</span></label>
                <select name="student_id" id="record-student-select" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->student_name }} (NIS: {{ $st->nis }}) - Kelas: {{ $st->classroom->name ?? '-' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tipe Catatan <span class="text-red-500">*</span></label>
                    <select name="type" id="record-type-select" required onchange="filterCategoryOptions()" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="violation">Pelanggaran (Poin Negatif)</option>
                        <option value="achievement">Prestasi / Apresiasi (Poin Positif)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori Standar</label>
                    <select name="counseling_category_id" id="record-category-select" onchange="applyCategoryPoint()" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Kategori / Manual --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" data-point="{{ $cat->point }}">{{ $cat->name }} ({{ $cat->point }} Poin)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="point" id="record-point-input" value="10" min="0" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Kejadian <span class="text-red-500">*</span></label>
                    <input type="date" name="incident_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul / Uraian Singkat <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="record-title-input" placeholder="Contoh: Merokok di kantin belakang sekolah / Juara 1 Catur" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi & Kronologi Kasus</label>
                <textarea name="description" rows="2.5" placeholder="Keterangan lengkap kejadian, saksi, waktu kejadian..." class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tindakan / Bimbingan yang Diberikan</label>
                <textarea name="action_taken" rows="2" placeholder="Solusi, pembinaan guru BK, teguran, sanksi edukatif..." class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Penanganan</label>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="resolved">Resolved (Selesai)</option>
                        <option value="in_progress">In Progress (Sedang Dibina)</option>
                        <option value="pending">Pending (Menunggu)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Guru Pelapor / BK</label>
                    <input type="text" name="reported_by" placeholder="Nama Guru / Petugas Piket" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-add-record')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT CATATAN --}}
<div id="modal-edit-record" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i> Edit Catatan Konseling
            </h3>
            <button onclick="closeModal('modal-edit-record')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form id="form-edit-record" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Siswa <span class="text-red-500">*</span></label>
                <select name="student_id" id="edit-student-id" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->student_name }} (NIS: {{ $st->nis }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tipe Catatan</label>
                    <select name="type" id="edit-type" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="violation">Pelanggaran</option>
                        <option value="achievement">Prestasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Poin</label>
                    <input type="number" name="point" id="edit-point" min="0" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal</label>
                    <input type="date" name="incident_date" id="edit-incident-date" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status</label>
                    <select name="status" id="edit-status" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="resolved">Resolved (Selesai)</option>
                        <option value="in_progress">In Progress (Pembinaan)</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul / Kasus</label>
                <input type="text" name="title" id="edit-title" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi / Kronologi</label>
                <textarea name="description" id="edit-description" rows="2.5" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tindakan / Bimbingan</label>
                <textarea name="action_taken" id="edit-action-taken" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Guru Pelapor</label>
                <input type="text" name="reported_by" id="edit-reported-by" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-edit-record')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div id="modal-add-category" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-tag text-blue-600"></i> Tambah Kategori Tata Tertib
            </h3>
            <button onclick="closeModal('modal-add-category')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('tu.counseling.category.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Kategori / Pelanggaran <span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="Misal: Membawa HP Tanpa Izin" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tipe</label>
                    <select name="type" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="violation">Pelanggaran</option>
                        <option value="achievement">Prestasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Poin Default</label>
                    <input type="number" name="point" value="10" min="1" max="100" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Ketentuan</label>
                <textarea name="description" rows="2.5" placeholder="Penjelasan aturan atau kriteria pelanggaran..." class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-add-category')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Kategori</button>
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

function openRecordForStudent(studentId, studentName) {
    document.getElementById('record-student-select').value = studentId;
    openModal('modal-add-record');
}

function applyCategoryPoint() {
    const catSelect = document.getElementById('record-category-select');
    const selected = catSelect.options[catSelect.selectedIndex];
    if (selected && selected.dataset.point) {
        document.getElementById('record-point-input').value = selected.dataset.point;
        if (!document.getElementById('record-title-input').value) {
            document.getElementById('record-title-input').value = selected.text.split('(')[0].trim();
        }
    }
}

function filterCategoryOptions() {
    const type = document.getElementById('record-type-select').value;
    const catSelect = document.getElementById('record-category-select');
    const options = catSelect.querySelectorAll('option');
    options.forEach(opt => {
        if (!opt.value) return;
        if (opt.dataset.type === type) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
}

function editRecord(record) {
    const form = document.getElementById('form-edit-record');
    form.action = `/tu/counseling/record/${record.id}`;

    document.getElementById('edit-student-id').value = record.student_id;
    document.getElementById('edit-type').value = record.type;
    document.getElementById('edit-point').value = record.point;
    document.getElementById('edit-incident-date').value = record.incident_date ? record.incident_date.split('T')[0] : '';
    document.getElementById('edit-status').value = record.status;
    document.getElementById('edit-title').value = record.title || '';
    document.getElementById('edit-description').value = record.description || '';
    document.getElementById('edit-action-taken').value = record.action_taken || '';
    document.getElementById('edit-reported-by').value = record.reported_by || '';

    openModal('modal-edit-record');
}
</script>
@endsection
