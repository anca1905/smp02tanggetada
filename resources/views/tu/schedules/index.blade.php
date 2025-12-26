@extends('layouts.app')

@section('title', 'Manajemen Jadwal')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Jadwal Pelajaran</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100 flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <p class="text-sm text-blue-700">
                    Unggah file jadwal pelajaran terbaru dalam format <strong>PDF</strong>. File ini akan bisa diunduh oleh
                    siswa dan orang tua melalui halaman publik website.
                </p>
            </div>

            <form action="{{ route('tu.schedules.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-700">Kelas 10</h3>
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">X</span>
                        </div>

                        @if (isset($schedules['jadwal_kelas_10']))
                            <div class="mb-4 p-3 bg-green-100 rounded text-green-800 text-sm flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> File Tersedia
                                <a href="{{ asset('storage/' . $schedules['jadwal_kelas_10']) }}" target="_blank"
                                    class="ml-auto text-green-700 underline text-xs">Lihat</a>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-red-100 rounded text-red-800 text-sm flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Belum ada file
                            </div>
                        @endif

                        <input type="file" name="jadwal_kelas_10" accept=".pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-700">Kelas 11</h3>
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">XI</span>
                        </div>

                        @if (isset($schedules['jadwal_kelas_11']))
                            <div class="mb-4 p-3 bg-green-100 rounded text-green-800 text-sm flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> File Tersedia
                                <a href="{{ asset('storage/' . $schedules['jadwal_kelas_11']) }}" target="_blank"
                                    class="ml-auto text-green-700 underline text-xs">Lihat</a>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-red-100 rounded text-red-800 text-sm flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Belum ada file
                            </div>
                        @endif

                        <input type="file" name="jadwal_kelas_11" accept=".pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-700">Kelas 12</h3>
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">XII</span>
                        </div>

                        @if (isset($schedules['jadwal_kelas_12']))
                            <div class="mb-4 p-3 bg-green-100 rounded text-green-800 text-sm flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> File Tersedia
                                <a href="{{ asset('storage/' . $schedules['jadwal_kelas_12']) }}" target="_blank"
                                    class="ml-auto text-green-700 underline text-xs">Lihat</a>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-red-100 rounded text-red-800 text-sm flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Belum ada file
                            </div>
                        @endif

                        <input type="file" name="jadwal_kelas_12" accept=".pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="bg-blue-900 text-white px-8 py-3 rounded-lg font-bold shadow hover:bg-blue-800 transition flex items-center">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Simpan & Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
