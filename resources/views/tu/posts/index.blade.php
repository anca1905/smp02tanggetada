@extends('layouts.app')

@section('title', 'Manajemen Berita')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Berita & Pengumuman</h2>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Tulis Berita Baru
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if ($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-16 h-10 object-cover rounded"
                                        alt="Thumb">
                                @else
                                    <span class="text-xs text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ Str::limit($post->title, 50) }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $post->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $post->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form onsubmit="confirmDelete(event, this);"
                                    action="{{ route('tu.posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada berita yang
                                diterbitkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Tambah Berita --}}
    <div id="addModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-bold text-gray-800">Tulis Berita Baru</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('tu.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Siswa SIMS Raih Medali Emas...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="Akademik">Akademik</option>
                        <option value="Prestasi">Prestasi</option>
                        <option value="Kegiatan">Kegiatan Siswa</option>
                        <option value="Pengumuman">Pengumuman</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Berita</label>
                    <textarea name="content" rows="6" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tulis isi berita di sini..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Utama (Thumbnail)</label>
                    <input type="file" name="image" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max 2MB.</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md">Terbitkan Berita</button>
                </div>
            </form>
        </div>
    </div>
@endsection
