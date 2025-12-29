@extends('layouts.app')

@section('title', 'Peminjaman Ruangan')

@section('content')
    <div class="h-full flex flex-col">

        @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Jadwal & Riwayat Peminjaman</h2>

                <div class="flex flex-col sm:flex-row gap-3 items-center">
                    <form action="{{ route('tu.borrowing.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                        <select name="status" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang
                            </option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>

                        <div class="relative w-full sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari Nama / Kegiatan..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </form>

                    <button onclick="openModal('add')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center transition-colors w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajukan Peminjaman
                    </button>
                </div>
            </div>
        </div>

        <div id="table-container"
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex-1 flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Peminjam</th>
                            <th class="px-6 py-3">Ruangan</th>
                            <th class="px-6 py-3">Tanggal & Waktu</th>
                            <th class="px-6 py-3">Kegiatan</th>
                            <th class="px-6 py-3">Penanggung Jawab</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($bookings as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $item->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->class ?? 'Guru/Staff' }}</p>
                                </td>
                                <td class="px-6 py-4 text-blue-600 font-medium">{{ $item->room_type }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ Str::limit($item->activity_description, 30) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->responsible_person }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match ($item->status) {
                                            'upcoming' => 'bg-yellow-100 text-yellow-800',
                                            'ongoing' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                        $statusLabel = match ($item->status) {
                                            'upcoming' => 'Akan Datang',
                                            'ongoing' => 'Berlangsung',
                                            'completed' => 'Selesai',
                                            default => '-',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="openModal('edit', {{ json_encode($item) }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 rounded">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $item->borrowing_id }})"
                                            class="text-red-600 hover:text-red-800 p-1 bg-red-50 rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bookings->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-lg transform transition-all scale-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Ajukan Peminjaman</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="bookingForm" method="POST" data-action="{{ route('tu.borrowing.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam</label>
                        <input type="text" name="full_name" id="namaPeminjam"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS (Opsional)</label>
                        <input type="text" name="nis" id="nisPeminjam"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas (Opsional)</label>
                        <input type="text" name="class" id="kelasPeminjam"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select name="room_type" id="ruanganSelect"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Pilih Ruangan</option>
                        @foreach ($ruanganList as $ruang)
                            <option value="{{ $ruang->room_name }}">{{ $ruang->room_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="borrow_date" id="tglPeminjaman"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" id="jamMulai"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" id="jamSelesai"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                    <textarea name="activity_description" id="descKegiatan" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                    <input type="text" name="responsible_person" id="penanggungJawab"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div id="statusContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Peminjaman</label>
                    <select name="status" id="statusSelect"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="upcoming">Akan Datang</option>
                        <option value="ongoing">Berlangsung</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        @csrf
        @method('DELETE')
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Peminjaman?</h3>
            <p class="text-gray-500 text-sm mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex space-x-3 justify-center">
                <button type="button" onclick="document.getElementById('deleteForm').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-md">Ya,
                    Hapus</button>
            </div>
        </div>
    </form>
@endsection
@push('js')
    <script src="{{ asset('assets/js/tu/borrowing.js') }}"></script>
@endpush
