@extends('layouts.app')

@section('title', 'Buku Tamu Digital')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-teal-900 via-emerald-900 to-slate-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-teal-200 text-xs font-medium mb-2">
                <i class="fas fa-address-book"></i> Pelayanan Tamu & Administrasi Humas
            </div>
            <h1 class="text-2xl font-bold">Register Buku Tamu Digital Sekolah</h1>
            <p class="text-teal-100 text-sm mt-1">Pencatatan kunjungan kedinasan, wali murid, alumni, dan pihak eksternal untuk arsip serta instrumen akreditasi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('tu.guest-books.export-pdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white rounded-xl font-medium text-sm transition flex items-center gap-2">
                <i class="fas fa-file-pdf text-red-400"></i> Unduh Rekap PDF
            </a>
            <a href="{{ route('public.buku-tamu') }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow transition flex items-center gap-2">
                <i class="fas fa-tablet-alt"></i> Buka Kiosk Tamu (Publik)
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

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Tamu Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['today'] }}</p>
                <p class="text-[11px] text-teal-600 font-medium">Kunjungan aktif</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Tamu Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['this_month'] }}</p>
                <p class="text-[11px] text-blue-600 font-medium">{{ date('F Y') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Menunggu Layanan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['waiting'] }}</p>
                <p class="text-[11px] text-amber-600 font-medium">Perlu dilayani</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-address-book"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Seluruh Tamu</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                <p class="text-[11px] text-purple-600 font-medium">Arsip terdaftar</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('tu.guest-books.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, instansi, atau keperluan..."
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>
            <div>
                <select name="date_filter" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">-- Semua Waktu --</option>
                    <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_month" {{ request('date_filter') === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>
            <div>
                <select name="category" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">-- Semua Kategori --</option>
                    <option value="wali_murid" {{ request('category') === 'wali_murid' ? 'selected' : '' }}>Orang Tua / Wali Siswa</option>
                    <option value="dinas_pemerintah" {{ request('category') === 'dinas_pemerintah' ? 'selected' : '' }}>Instansi / Dinas Pemerintah</option>
                    <option value="swasta_vendor" {{ request('category') === 'swasta_vendor' ? 'selected' : '' }}>Swasta / Perusahaan / Vendor</option>
                    <option value="alumni" {{ request('category') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="umum" {{ request('category') === 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">-- Semua Status --</option>
                    <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>Menunggu</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diterima / Sedang Layanan</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'date_filter', 'category', 'status']))
                    <a href="{{ route('tu.guest-books.index') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Guest Book Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="py-3.5 px-4 text-left">Waktu Kunjungan</th>
                        <th class="py-3.5 px-4 text-left">Nama Tamu & Instansi</th>
                        <th class="py-3.5 px-4 text-left">Kontak / WA</th>
                        <th class="py-3.5 px-4 text-left">Kategori</th>
                        <th class="py-3.5 px-4 text-left">Bertemu Dengan</th>
                        <th class="py-3.5 px-4 text-left">Keperluan / Pesan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap text-gray-600">
                                <div class="font-bold text-gray-900">{{ $guest->visit_date?->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <i class="far fa-clock text-[10px]"></i> {{ $guest->visit_time ?: 'Pagi' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-800">{{ $guest->name }}</div>
                                <div class="text-xs text-gray-500">{{ $guest->institution ?: 'Masyarakat Umum' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-mono text-gray-700 whitespace-nowrap">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guest->phone) }}" target="_blank" class="text-emerald-600 hover:underline flex items-center gap-1">
                                    <i class="fab fa-whatsapp"></i> {{ $guest->phone }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ ucwords(str_replace('_', ' ', $guest->category)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-gray-800 font-medium whitespace-nowrap">
                                <i class="fas fa-user-tie text-gray-400 mr-1"></i> {{ ucwords(str_replace('_', ' ', $guest->meet_with)) }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-700 text-xs max-w-xs">
                                <p class="line-clamp-2 leading-relaxed">{{ $guest->purpose }}</p>
                                @if($guest->notes)
                                    <div class="mt-1 text-[11px] text-teal-700 italic">Catatan: {{ $guest->notes }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($guest->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fas fa-check-circle mr-1"></i> Selesai
                                    </span>
                                @elseif($guest->status === 'in_progress')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        <i class="fas fa-user-check mr-1"></i> Diterima
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick='editGuestStatus(@json($guest))' class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Perbarui Status Layanan">
                                        <i class="fas fa-tasks"></i>
                                    </button>
                                    <form action="{{ route('tu.guest-books.destroy', $guest) }}" method="POST" onsubmit="return confirm('Hapus catatan tamu ini?')">
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
                                <i class="fas fa-inbox text-4xl mb-2 text-gray-300 block"></i>
                                Belum ada catatan kunjungan tamu untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $guests->links() }}
        </div>
    </div>
</div>

{{-- MODAL UPDATE STATUS KUNJUNGAN --}}
<div id="modal-guest-status" class="fixed inset-0 z-50 bg-black/50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-tasks text-teal-600"></i> Update Status Kunjungan
            </h3>
            <button onclick="closeModal('modal-guest-status')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form id="form-guest-status" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Tamu</label>
                <input type="text" id="status-guest-name" disabled class="w-full px-3.5 py-2 rounded-xl bg-gray-100 border border-gray-300 text-sm font-bold text-gray-700">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Pelayanan</label>
                <select name="status" id="status-guest-select" required class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold">
                    <option value="waiting">Menunggu</option>
                    <option value="in_progress">Diterima / Sedang Berlangsung</option>
                    <option value="completed">Selesai</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Tindak Lanjut / Resepsionis</label>
                <textarea name="notes" id="status-guest-notes" rows="3" placeholder="Misal: Sudah ditemui Kepala Sekolah, berkas sudah diserahkan..." class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-guest-status')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition">Simpan Status</button>
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

function editGuestStatus(guest) {
    const form = document.getElementById('form-guest-status');
    form.action = `/tu/guest-books/${guest.id}/status`;

    document.getElementById('status-guest-name').value = guest.name + (guest.institution ? ' (' + guest.institution + ')' : '');
    document.getElementById('status-guest-select').value = guest.status;
    document.getElementById('status-guest-notes').value = guest.notes || '';

    openModal('modal-guest-status');
}
</script>
@endsection
