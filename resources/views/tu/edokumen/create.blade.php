@extends('layouts.app')

@section('title', 'Unggah E-Dokumen')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-center gap-4">
        <a href="{{ route('tu.edokumen.index') }}" class="text-gray-500 hover:text-blue-600 transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Unggah Dokumen Baru</h2>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('tu.edokumen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Dokumen <span class="text-red-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Kalender Akademik 2026/2027" class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Dokumen Administrasi" {{ old('category') == 'Dokumen Administrasi' ? 'selected' : '' }}>Dokumen Administrasi</option>
                    <option value="Kurikulum & Program" {{ old('category') == 'Kurikulum & Program' ? 'selected' : '' }}>Kurikulum & Program</option>
                    <option value="Prestasi & Sertifikat" {{ old('category') == 'Prestasi & Sertifikat' ? 'selected' : '' }}>Prestasi & Sertifikat</option>
                    <option value="Peraturan & Kebijakan" {{ old('category') == 'Peraturan & Kebijakan' ? 'selected' : '' }}>Peraturan & Kebijakan</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">File Dokumen <span class="text-red-500">*</span></label>
                <input type="file" name="file_path" required accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="mt-2 text-xs text-gray-500">Format yang diizinkan: PDF, DOC, DOCX. Maksimal ukuran file: 10MB.</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-blue-700 transition">
                    <i class="fas fa-upload mr-2"></i> Unggah Dokumen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
