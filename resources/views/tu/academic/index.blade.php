@extends('layouts.app') {{-- Sesuaikan dengan layout admin kamu --}}

@section('title', 'Data Tahun Ajaran')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tahun Ajaran</h2>
                <p class="text-gray-500 text-sm">Atur periode akademik sekolah</p>
            </div>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Tambah Periode
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-4">Periode</th>
                        <th class="p-4">Semester</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                    @foreach ($years as $year)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-medium">{{ $year->name }}</td>
                            <td class="p-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $year->semester == 'Ganjil' ? 'bg-orange-100 text-orange-600' : 'bg-purple-100 text-purple-600' }}">
                                    {{ $year->semester }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if ($year->is_active)
                                    <span
                                        class="flex items-center text-green-600 font-bold text-xs uppercase tracking-wider">
                                        <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs uppercase tracking-wider">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                @if (!$year->is_active)
                                    <form action="{{ route('tu.academic-years.set-active', $year->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-bold border border-blue-200 px-3 py-1 rounded hover:bg-blue-50">
                                            Set Aktif
                                        </button>
                                    </form>
                                    <form action="{{ route('tu.academic-years.destroy', $year->id) }}" method="POST"
                                        class="inline" onsubmit="confirmDelete(event, this);">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Simple Modal Tambah --}}
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">Tambah Tahun Ajaran</h3>
            <form action="{{ route('tu.academic-years.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun (Contoh: 2026/2027)</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-lg px-4 py-2" required
                        placeholder="YYYY/YYYY">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                    <select name="semester" class="w-full border-gray-300 rounded-lg px-4 py-2">
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="text-gray-500 px-4 py-2">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
