@extends('layouts.app')

@section('title', 'PPDB Online')

@section('content')

    {{-- Header & Toggle --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen PPDB Online</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data calon siswa baru tahun ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>
        <form action="{{ route('tu.ppdb.toggleStatus') }}" method="POST">
            @csrf
            @if ($bukaPpdb === '1')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 border border-red-300 rounded-lg font-semibold text-sm hover:bg-red-200 transition shadow-sm">
                    <i class="fas fa-lock"></i> Tutup Pendaftaran PPDB
                </button>
            @else
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 border border-green-300 rounded-lg font-semibold text-sm hover:bg-green-200 transition shadow-sm">
                    <i class="fas fa-lock-open"></i> Buka Pendaftaran PPDB
                </button>
            @endif
        </form>
    </div>

    {{-- Status Banner --}}
    @if ($bukaPpdb === '1')
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-800">PPDB Sedang Dibuka</p>
                <p class="text-xs text-green-600">Calon siswa dapat mendaftar melalui halaman <a href="{{ route('public.ppdb') }}" target="_blank" class="underline font-bold">{{ route('public.ppdb') }}</a></p>
            </div>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-red-800">PPDB Sedang Ditutup</p>
                <p class="text-xs text-red-600">Halaman pendaftaran publik tidak dapat diakses saat ini.</p>
            </div>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Total Pendaftar</p>
                <h4 class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</h4>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-yellow-500 flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Menunggu Verifikasi</p>
                <h4 class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</h4>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-xl">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Diterima</p>
                <h4 class="text-3xl font-bold text-green-600">{{ $stats['accepted'] }}</h4>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xl">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-red-500 flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Ditolak</p>
                <h4 class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</h4>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xl">
                <i class="fas fa-user-times"></i>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
        <form method="GET" action="{{ route('tu.ppdb.index') }}" class="flex flex-wrap gap-3 items-center">
            <select name="jurusan" onchange="this.form.submit()"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 bg-white">
                <option value="">Semua Jurusan</option>
                <option value="TKJ" {{ request('jurusan') === 'TKJ' ? 'selected' : '' }}>Teknik Komputer & Jaringan</option>
                <option value="RPL" {{ request('jurusan') === 'RPL' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                <option value="TBSM" {{ request('jurusan') === 'TBSM' ? 'selected' : '' }}>Teknik Bisnis Sepeda Motor</option>
                <option value="AKL" {{ request('jurusan') === 'AKL' ? 'selected' : '' }}>Akuntansi Keuangan Lembaga</option>
            </select>
            <select name="status" onchange="this.form.submit()"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 bg-white">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama / no. reg / NISN..."
                    class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                Cari
            </button>
            @if (request()->hasAny(['jurusan', 'status', 'search']))
                <a href="{{ route('tu.ppdb.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel Data Pendaftar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-gray-500 text-xs uppercase font-bold tracking-wider">
                        <th class="px-5 py-4">No. Registrasi</th>
                        <th class="px-5 py-4">Nama Calon Siswa</th>
                        <th class="px-5 py-4">Jurusan</th>
                        <th class="px-5 py-4">Asal Sekolah</th>
                        <th class="px-5 py-4">No. HP</th>
                        <th class="px-5 py-4">Tgl. Daftar</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    @forelse ($pendaftars as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs font-semibold text-blue-700">{{ $p->no_registrasi }}</td>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-800">{{ $p->nama_lengkap }}</div>
                                <div class="text-xs text-gray-400">NISN: {{ $p->nisn ?? '-' }} &bull; {{ $p->jenis_kelamin_label }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded">
                                    {{ $p->jurusan_pilihan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $p->asal_sekolah ?? '-' }}</td>
                            <td class="px-5 py-3 text-xs">{{ $p->no_hp }}</td>
                            <td class="px-5 py-3 text-xs text-gray-400">
                                {{ $p->tanggal_daftar ? $p->tanggal_daftar->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $badgeClass = match($p->status_pendaftaran) {
                                        'Accepted' => 'bg-green-100 text-green-700',
                                        'Rejected' => 'bg-red-100 text-red-700',
                                        default    => 'bg-yellow-100 text-yellow-700',
                                    };
                                    $badgeLabel = match($p->status_pendaftaran) {
                                        'Accepted' => 'Diterima',
                                        'Rejected' => 'Ditolak',
                                        default    => 'Pending',
                                    };
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Detail --}}
                                    <a href="{{ route('tu.ppdb.show', $p->id) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                        title="Lihat Detail & Berkas">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    {{-- Terima --}}
                                    @if ($p->status_pendaftaran !== 'Accepted')
                                        <form action="{{ route('tu.ppdb.updateStatus', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status_pendaftaran" value="Accepted">
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition"
                                                title="Terima">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Tolak --}}
                                    @if ($p->status_pendaftaran !== 'Rejected')
                                        <form action="{{ route('tu.ppdb.updateStatus', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status_pendaftaran" value="Rejected">
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition"
                                                title="Tolak">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Reset ke Pending --}}
                                    @if ($p->status_pendaftaran !== 'Pending')
                                        <form action="{{ route('tu.ppdb.updateStatus', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status_pendaftaran" value="Pending">
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white transition"
                                                title="Reset ke Pending">
                                                <i class="fas fa-undo text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Hapus --}}
                                    <form action="{{ route('tu.ppdb.destroy', $p->id) }}" method="POST" onsubmit="confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-red-600 hover:text-white transition"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <i class="fas fa-inbox text-4xl"></i>
                                    <p class="font-semibold text-gray-500">Tidak ada data pendaftar</p>
                                    <p class="text-xs">Coba ubah filter atau tunggu pendaftar baru masuk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination & Info --}}
        @if ($pendaftars->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $pendaftars->firstItem() }}&ndash;{{ $pendaftars->lastItem() }}
                    dari {{ $pendaftars->total() }} pendaftar
                </p>
                {{ $pendaftars->links() }}
            </div>
        @else
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                <p class="text-xs text-gray-400">Total {{ $pendaftars->total() }} pendaftar</p>
            </div>
        @endif
    </div>

@endsection
