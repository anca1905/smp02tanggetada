@extends('layouts.app')

@section('title', 'Manajemen Berita')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Berita & Pengumuman</h2>
                <p class="text-sm text-gray-500">Kelola artikel publik, prestasi sekolah, dan pengumuman resmi.</p>
            </div>
            <a href="{{ route('tu.posts.create') }}"
                class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition inline-flex items-center shadow-sm font-semibold text-sm">
                <i class="fas fa-plus mr-2"></i> Tulis Berita Baru
            </a>
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
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-16 h-10 object-cover rounded shadow-xs"
                                        alt="Thumb">
                                @else
                                    <div class="w-16 h-10 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <a href="{{ route('public.berita.show', $post->slug) }}" target="_blank"
                                    class="text-gray-900 hover:text-blue-600 hover:underline">
                                    {{ Str::limit($post->title, 55) }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $post->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $post->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    {{-- Tombol Lihat di Web --}}
                                    <a href="{{ route('public.berita.show', $post->slug) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-900 p-1.5 rounded hover:bg-blue-50 transition"
                                        title="Lihat Berita di Web">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('tu.posts.edit', $post->id) }}"
                                        class="text-yellow-600 hover:text-yellow-900 p-1.5 rounded hover:bg-yellow-50 transition"
                                        title="Edit Berita">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form onsubmit="confirmDelete(event, this);"
                                        action="{{ route('tu.posts.destroy', $post->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1.5 rounded hover:bg-red-50 transition"
                                            title="Hapus Berita">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i class="fas fa-newspaper text-3xl text-gray-300 mb-2"></i>
                                    <p>Belum ada berita yang diterbitkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
