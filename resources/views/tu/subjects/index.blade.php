@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div
            class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Mata Pelajaran</h2>
                <p class="text-gray-500 text-sm">Daftar semua mata pelajaran yang tersedia di sekolah.</p>
            </div>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center shadow-lg shadow-blue-500/30">
                <i class="fas fa-plus mr-2"></i> Tambah Mapel
            </button>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel Data --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="p-4 border-b w-16">Cover</th>
                            <th class="p-4 border-b">Kode</th>
                            <th class="p-4 border-b">Nama Mata Pelajaran</th>
                            <th class="p-4 border-b text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                        @forelse($subjects as $subject)
                            <tr class="hover:bg-blue-50 transition group">
                                <td class="p-4 w-16">
                                    @if($subject->cover_url)
                                        <img src="{{ $subject->cover_url }}" alt="{{ $subject->name }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-xs">
                                    @else
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white shadow-xs text-base"
                                             style="background: {{ $subject->theme['gradient'] }};">
                                            <i class="{{ $subject->theme['icon'] }}"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 font-mono font-bold text-blue-600">
                                    <span class="bg-blue-100 px-2 py-1 rounded">{{ $subject->code }}</span>
                                </td>
                                <td class="p-4 font-medium">{{ $subject->name }}</td>
                                <td class="p-4 text-right space-x-2">
                                    {{-- Tombol Edit --}}
                                    <button
                                        onclick="openEditModal({{ $subject->id }}, '{{ $subject->code }}', '{{ addslashes($subject->name) }}', '{{ $subject->cover_url ?? '' }}')"
                                        class="text-yellow-500 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 p-2 rounded transition"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('tu.subjects.destroy', $subject->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="confirmDelete(event, this);">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-book-open text-4xl mb-3 opacity-30"></i>
                                        <p>Belum ada data mata pelajaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="addModal"
        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 mx-4 transform transition-all scale-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Tambah Mata Pelajaran</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form action="{{ route('tu.subjects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Mapel</label>
                    <input type="text" name="code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: BHS-INDO-01" required>
                    <p class="text-xs text-gray-400 mt-1">Harus unik, misal: BHS-INDO-01</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mapel</label>
                    <input type="text" name="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Bahasa Indonesia" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cover Mata Pelajaran (Opsional)</label>
                    <input type="file" name="cover" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP (Maks. 2MB). Ditampilkan di kartu LMS Guru & Siswa.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="text-gray-500 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editModal"
        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 mx-4 transform transition-all scale-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Edit Mata Pelajaran</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Mapel</label>
                    <input type="text" name="code" id="edit_code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mapel</label>
                    <input type="text" name="name" id="edit_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ubah Cover (Opsional)</label>
                    <div id="edit_cover_container" class="mb-2 hidden">
                        <p class="text-xs text-gray-500 mb-1">Cover saat ini:</p>
                        <img id="edit_cover_preview" src="" class="h-20 w-36 object-cover rounded-lg border border-gray-200 shadow-xs" alt="Cover Preview">
                    </div>
                    <input type="file" name="cover" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah cover.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="text-gray-500 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT: Menutup modal saat klik luar & Handle Edit --}}
    <script>
        // Logic untuk mengisi data ke Modal Edit secara dinamis
        function openEditModal(id, code, name, coverUrl) {
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;

            const coverContainer = document.getElementById('edit_cover_container');
            const coverPreview = document.getElementById('edit_cover_preview');
            if (coverUrl && coverUrl.trim() !== '') {
                coverPreview.src = coverUrl;
                coverContainer.classList.remove('hidden');
            } else {
                coverContainer.classList.add('hidden');
                coverPreview.src = '';
            }

            // Update URL action pada form agar sesuai ID yang diedit
            document.getElementById('editForm').action = "{{ url('tu/subjects') }}/" + id;

            document.getElementById('editModal').classList.remove('hidden');
        }

        // Logic Klik di luar modal untuk menutup
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (event.target == addModal) {
                addModal.classList.add('hidden');
            }
            if (event.target == editModal) {
                editModal.classList.add('hidden');
            }
        }
    </script>
@endsection
