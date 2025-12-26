@extends('layouts.app')

@section('title', 'Tambah Agenda')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">Jadwalkan Kegiatan Baru</h3>
            </div>

            <form action="{{ route('tu.events.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kegiatan</label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Ujian Tengah Semester Ganjil">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (Opsional)</label>
                        <input type="date" name="end_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika hanya 1 hari.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Kegiatan</label>
                    <select name="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="academic">Akademik (Ujian, Rapor, dll)</option>
                        <option value="holiday">Hari Libur / Tanggal Merah</option>
                        <option value="event">Kegiatan Sekolah (Lomba, Upacara)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat (Opsional)</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Keterangan tambahan..."></textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <a href="{{ route('tu.events.index') }}"
                        class="px-6 py-2.5 text-gray-700 font-medium hover:bg-gray-100 rounded-lg mr-3">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> Simpan Agenda
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
