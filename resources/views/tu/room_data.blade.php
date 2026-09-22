@extends('layouts.app')

@section('title', 'Data Ruangan')

@section('content')
    <div class="h-full flex flex-col min-w-0">

        {{-- @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif --}}

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Tabel Data Ruangan</h2>

                <div class="flex flex-col sm:flex-row gap-3 items-center">
                    <form action="{{ route('tu.room.index') }}" method="GET" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama Ruangan..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                        <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </form>

                    <button onclick="openModal('add')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center transition-colors w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Ruangan
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex-1 flex flex-col min-w-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Nama Ruangan</th>
                            <th class="px-6 py-3">Lokasi</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rooms as $room)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $room->room_name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $room->location }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ Str::limit($room->description, 50) ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="openModal('edit', {{ json_encode($room) }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button onclick="confirmDelete({{ $room->room_id }})"
                                            class="text-red-600 hover:text-red-800 p-1 bg-red-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">Tidak ada data ruangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $rooms->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div id="roomModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md transform transition-all scale-100">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Tambah Ruangan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="roomForm" method="POST" action="{{ route('tu.room.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan</label>
                    <input type="text" name="room_name" id="roomName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Lab Komputer A" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" id="roomLocation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Gedung B, Lantai 2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <textarea name="description" id="roomDesc" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Fasilitas: AC, Proyektor..."></textarea>
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
        class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
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
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Ruangan?</h3>
            <p class="text-gray-500 text-sm mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex space-x-3 justify-center">
                <button type="button" onclick="document.getElementById('deleteForm').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-md">Ya,
                    Hapus</button>
            </div>
        </div>
    </form>
@endsection
@push('js')
    <script src="{{ asset('assets/js/tu/room_data.js') }}"></script>
@endpush
