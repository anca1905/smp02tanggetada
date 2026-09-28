@extends('layouts.app')

@section('title', 'Manajemen E-Dokumen')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar E-Dokumen</h2>
        <a href="{{ route('tu.edokumen.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Dokumen
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm">
                        <th class="p-4 font-semibold">No</th>
                        <th class="p-4 font-semibold">Judul Dokumen</th>
                        <th class="p-4 font-semibold">Kategori</th>
                        <th class="p-4 font-semibold">File</th>
                        <th class="p-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($edokumens as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="p-4 font-medium text-gray-800">{{ $item->title }}</td>
                            <td class="p-4 text-gray-600">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="p-4">
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500"></i> Lihat File
                                </a>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('tu.edokumen.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.form)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus Dokumen">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada dokumen yang diunggah.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
