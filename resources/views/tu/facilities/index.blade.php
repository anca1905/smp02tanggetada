@extends('layouts.app')

@section('title', 'Fasilitas Sekolah')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Fasilitas Sekolah</h2>
            <button onclick="openModal('add')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Tambahkan Fasilitas
            </button>
        </div>


        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Fasilitas</th>
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3 w-[10%] whitespace-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($fasilitasList as $fasilitas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $fasilitas->title }}</td>
                            <td class="px-6 py-3">
                                @if ($fasilitas->image_path)
                                    <img src="{{ asset($fasilitas->image_path) }}" alt="Thumbnail"
                                        class="w-16 h-10 object-cover rounded">
                                @else
                                    <span class="text-xs text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                <button onclick="openModal('edit', {{ json_encode($fasilitas) }})"
                                    class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete({{ $fasilitas->id }})"
                                    class="text-red-600 hover:text-red-800 p-1 bg-red-50 rounded">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada fasilitas yang
                                ditambahkan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{-- Tambahkan class hidden --}}
    <div id="fasilitasModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-lg transform transition-all scale-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Tambahkan Fasilitas</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="fasilitasForm" method="POST" data-action="{{ route('tu.facility.store') }} classp-6 space-y-4"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST" mu>
                <input type="hidden" id="fasilitasId" name="id">

                <div class="grid grid-cols-1 gap-4 px-6 py-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                        <input type="text" name="title" id="fasilitasTitle"
                            class="w-full px-4 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Gambar (Opsional)</label>
                        <input type="file" name="image_path" id="fasilitasImage" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 border-t border-gray-100 px-6 py-4">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Jangan lupa class hidden --}}
    <form action="" id="deleteForm" method="POST"
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
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Data Fasilitas?</h3>
            <p class="text-gray-500 text-sm mb-6">Data yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>
            <div class="flex space-x-3 justify-center">
                <button type="button"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
                    onclick="document.getElementById('deleteForm').classList.add('hidden')">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-md">Ya,
                    Hapus</button>
            </div>
        </div>
    </form>
@endsection
@push('js')
    <script src="{{ asset('assets/js/tu/fasilitas.js') }}"></script>
@endpush
