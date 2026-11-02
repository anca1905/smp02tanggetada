@extends('layouts.app')

@section('title', 'Manajemen Kelulusan & SKL Siswa')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-cyan-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-cyan-200 text-xs font-medium mb-2">
                <i class="fas fa-graduation-cap"></i> Portal Akademik & Kelulusan Kelas IX
            </div>
            <h1 class="text-2xl font-bold">Manajemen Kelulusan & SKL Mandiri</h1>
            <p class="text-blue-100 text-sm mt-1">Pengelolaan data kelulusan, penomoran SKL ber-barcode, dan kontrol jadwal rilis pengumuman online.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($classNineUnimportedCount > 0)
                <form action="{{ route('tu.graduations.import') }}" method="POST" onsubmit="return confirm('Tarik {{ $classNineUnimportedCount }} siswa aktif kelas 9 ke daftar kelulusan?')">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow transition flex items-center gap-2">
                        <i class="fas fa-file-import"></i> Tarik Siswa Kelas 9 ({{ $classNineUnimportedCount }})
                    </button>
                </form>
            @endif
            <button onclick="openModal('modal-add-graduation')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Siswa
            </button>
            <button onclick="openModal('modal-settings')" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white rounded-xl font-medium text-sm transition flex items-center gap-2">
                <i class="fas fa-sliders-h"></i> Pengaturan SKL
            </button>
            <a href="{{ route('public.kelulusan') }}" target="_blank" class="px-4 py-2.5 bg-black/20 hover:bg-black/30 text-white rounded-xl font-medium text-sm transition flex items-center gap-2">
                <i class="fas fa-external-link-alt"></i> Cek Publik
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
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Status Pengumuman Banner --}}
    <div class="p-4 rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $announcementStatus === '1' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-amber-50 border-amber-200 text-amber-900' }}">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $announcementStatus === '1' ? 'bg-emerald-200 text-emerald-800' : 'bg-amber-200 text-amber-800' }}">
                <i class="fas {{ $announcementStatus === '1' ? 'fa-bullhorn' : 'fa-lock' }}"></i>
            </div>
            <div>
                <p class="font-bold text-sm">
                    Status Portal Kelulusan Publik:
                    <span class="uppercase tracking-wider font-extrabold {{ $announcementStatus === '1' ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $announcementStatus === '1' ? 'Terbuka (Siswa Dapat Cek & Cetak SKL)' : 'Ditutup / Menunggu Jadwal Resmi' }}
                    </span>
                </p>
                <p class="text-xs opacity-80 mt-0.5">
                    Format No. Surat: <code class="px-1.5 py-0.5 rounded bg-black/5 font-mono">{{ $letterPrefix }}</code> • Kepala Sekolah: {{ $headmasterName }}
                </p>
            </div>
        </div>
        <button onclick="openModal('modal-settings')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm {{ $announcementStatus === '1' ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-amber-700 hover:bg-amber-800 text-white' }}">
            Ubah Status / Jadwal
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Terdata</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Siswa Lulus</p>
                <p class="text-xl font-bold text-emerald-600">{{ $stats['lulus'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Ditunda</p>
                <p class="text-xl font-bold text-amber-600">{{ $stats['ditunda'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Tidak Lulus</p>
                <p class="text-xl font-bold text-red-600">{{ $stats['tidak_lulus'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Rata-rata Nilai</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['average'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('tu.graduations.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NISN, NIS, atau No. Surat..."
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Semua Status Kelulusan --</option>
                    <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="ditunda" {{ request('status') === 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                    <option value="tidak_lulus" {{ request('status') === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('tu.graduations.index') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Graduations Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="py-3.5 px-4 text-left">Siswa & Identitas</th>
                        <th class="py-3.5 px-4 text-center">Kelas</th>
                        <th class="py-3.5 px-4 text-left">No. Surat SKL</th>
                        <th class="py-3.5 px-4 text-center">Rata-rata Nilai</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Kode Verifikasi</th>
                        <th class="py-3.5 px-4 text-center">Dokumen SKL</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($graduations as $grad)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-800">{{ $grad->student_name }}</div>
                                <div class="text-xs text-gray-500">
                                    NIS: <strong>{{ $grad->nis }}</strong> • NISN: {{ $grad->nisn ?: '-' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-700 font-medium">
                                {{ $grad->classroom_name ?: ($grad->student->classroom->name ?? '-') }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-mono text-gray-700">
                                {{ $grad->letter_number ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                    {{ number_format((float)$grad->average_score, 2) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($grad->status === 'lulus')
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                        Lulus
                                    </span>
                                @elseif($grad->status === 'ditunda')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                                        Ditunda
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 uppercase tracking-wider">
                                        Tidak Lulus
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('public.kelulusan.verify', $grad->verification_code) }}" target="_blank" class="text-xs font-mono font-bold text-blue-600 hover:underline">
                                    {{ $grad->verification_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($grad->status === 'lulus')
                                    <a href="{{ route('public.kelulusan.skl.download', $grad) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-semibold transition" title="Unduh PDF SKL">
                                        <i class="fas fa-file-pdf text-red-500"></i> Unduh SKL
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick='editGraduation(@json($grad))' class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('tu.graduations.destroy', $grad) }}" method="POST" onsubmit="return confirm('Hapus data kelulusan siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-400">
                                <i class="fas fa-graduation-cap text-4xl mb-2 text-gray-300 block"></i>
                                Belum ada data kelulusan yang diinput. Gunakan tombol "Tarik Siswa Kelas 9" untuk import otomatis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $graduations->links() }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH DATA KELULUSAN --}}
<div id="modal-add-graduation" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-graduation-cap text-blue-600"></i> Tambah Data Kelulusan Siswa
            </h3>
            <button onclick="closeModal('modal-add-graduation')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('tu.graduations.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih Siswa <span class="text-red-500">*</span></label>
                <select name="student_id" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($availableStudents as $st)
                        <option value="{{ $st->id }}">{{ $st->student_name }} (NIS: {{ $st->nis }}) - {{ $st->classroom->name ?? '-' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Kelulusan <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="lulus">LULUS</option>
                        <option value="ditunda">DITUNDA</option>
                        <option value="tidak_lulus">TIDAK LULUS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nilai Rata-rata Ujian <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="average_score" value="82.50" min="0" max="100" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Surat SKL (Opsional)</label>
                    <input type="text" name="letter_number" placeholder="Otomatis jika dikosongkan" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Kelulusan</label>
                    <input type="date" name="graduation_date" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan perihal penyerahan ijazah / berkas kelulusan..." class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-add-graduation')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT DATA KELULUSAN --}}
<div id="modal-edit-graduation" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i> Edit Data Kelulusan Siswa
            </h3>
            <button onclick="closeModal('modal-edit-graduation')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form id="form-edit-graduation" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Siswa</label>
                <input type="text" id="edit-grad-name" disabled class="w-full px-3.5 py-2 rounded-xl bg-gray-100 border border-gray-300 text-sm font-bold text-gray-700">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Kelulusan</label>
                    <select name="status" id="edit-grad-status" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="lulus">LULUS</option>
                        <option value="ditunda">DITUNDA</option>
                        <option value="tidak_lulus">TIDAK LULUS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rata-rata Nilai Ujian</label>
                    <input type="number" step="0.01" name="average_score" id="edit-grad-score" min="0" max="100" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Surat SKL</label>
                    <input type="text" name="letter_number" id="edit-grad-letter" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Kelulusan</label>
                    <input type="date" name="graduation_date" id="edit-grad-date" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Tambahan</label>
                <textarea name="notes" id="edit-grad-notes" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-edit-graduation')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PENGATURAN SKL & PENGUMUMAN --}}
<div id="modal-settings" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-sliders-h text-blue-600"></i> Pengaturan Portal Kelulusan & SKL
            </h3>
            <button onclick="closeModal('modal-settings')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('tu.graduations.settings') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Pengumuman Kelulusan Publik</label>
                <select name="graduation_status" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                    <option value="1" {{ $announcementStatus === '1' ? 'selected' : '' }}>DIBUKA (Siswa Dapat Memeriksa & Unduh SKL)</option>
                    <option value="0" {{ $announcementStatus === '0' ? 'selected' : '' }}>DITUTUP (Menunggu Jadwal Countdown)</option>
                </select>
                <p class="text-[11px] text-gray-500 mt-1">Jika ditutup, halaman publik akan menampilkan waktu hitung mundur (*countdown*) pengumuman.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jadwal Jam Rilis Pengumuman</label>
                <input type="datetime-local" name="graduation_announcement_date" value="{{ $announcementDate }}" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Format Awalan Nomor Surat SKL</label>
                <input type="text" name="graduation_letter_number_prefix" value="{{ $letterPrefix }}" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
                <p class="text-[11px] text-gray-400 mt-1">Gunakan tag <code>{NO}</code> sebagai penomoran urut otomatis (contoh: 421.3/SKL/{NO}/SMP-02/2026).</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Kepala Sekolah</label>
                    <input type="text" name="graduation_headmaster_name" value="{{ $headmasterName }}" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIP Kepala Sekolah</label>
                    <input type="text" name="graduation_headmaster_nip" value="{{ $headmasterNip }}" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-settings')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan Pengaturan</button>
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

function editGraduation(grad) {
    const form = document.getElementById('form-edit-graduation');
    form.action = `/tu/graduations/${grad.id}`;

    document.getElementById('edit-grad-name').value = grad.student_name + ' (NIS: ' + grad.nis + ')';
    document.getElementById('edit-grad-status').value = grad.status;
    document.getElementById('edit-grad-score').value = grad.average_score;
    document.getElementById('edit-grad-letter').value = grad.letter_number || '';
    document.getElementById('edit-grad-date').value = grad.graduation_date ? grad.graduation_date.split('T')[0] : '';
    document.getElementById('edit-grad-notes').value = grad.notes || '';

    openModal('modal-edit-graduation');
}
</script>
@endsection
