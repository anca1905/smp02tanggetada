@extends('layouts.public')

@section('title', 'Jadwal Pelajaran')
@section('header', 'Jadwal Akademik')
@section('subheader', 'Jadwal Kegiatan Belajar Mengajar Tahun Ajaran 2024/2025')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 rounded-r-lg">
            <div class="flex">
                <div class="shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Jadwal ini berlaku efektif mulai Semester Ganjil. Perubahan jadwal mendadak akan diinformasikan
                        melalui wali kelas masing-masing.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition">
                <div class="flex items-center">
                    <div
                        class="bg-red-100 p-3 rounded-lg mr-4 text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Jadwal Kelas 7</h4>
                        @if (isset($site_settings['jadwal_kelas_7']))
                            <span class="text-xs text-green-600 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> Tersedia
                            </span>
                        @else
                            <span class="text-xs text-gray-400 font-medium flex items-center">
                                <i class="fas fa-times-circle mr-1"></i> Belum rilis
                            </span>
                        @endif
                    </div>
                </div>

                @if (isset($site_settings['jadwal_kelas_7']))
                    <a href="{{ asset('storage/' . $site_settings['jadwal_kelas_7']) }}" target="_blank"
                        class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition"
                        title="Download PDF">
                        <i class="fas fa-download"></i>
                    </a>
                @else
                    <button disabled
                        class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed"
                        title="File belum diupload">
                        <i class="fas fa-lock"></i>
                    </button>
                @endif
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition">
                <div class="flex items-center">
                    <div
                        class="bg-red-100 p-3 rounded-lg mr-4 text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Jadwal Kelas 8</h4>
                        @if (isset($site_settings['jadwal_kelas_8']))
                            <span class="text-xs text-green-600 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> Tersedia
                            </span>
                        @else
                            <span class="text-xs text-gray-400 font-medium flex items-center">
                                <i class="fas fa-times-circle mr-1"></i> Belum rilis
                            </span>
                        @endif
                    </div>
                </div>

                @if (isset($site_settings['jadwal_kelas_8']))
                    <a href="{{ asset('storage/' . $site_settings['jadwal_kelas_8']) }}" target="_blank"
                        class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition"
                        title="Download PDF">
                        <i class="fas fa-download"></i>
                    </a>
                @else
                    <button disabled
                        class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed"
                        title="File belum diupload">
                        <i class="fas fa-lock"></i>
                    </button>
                @endif
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition">
                <div class="flex items-center">
                    <div
                        class="bg-red-100 p-3 rounded-lg mr-4 text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Jadwal Kelas 9</h4>
                        @if (isset($site_settings['jadwal_kelas_9']))
                            <span class="text-xs text-green-600 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> Tersedia
                            </span>
                        @else
                            <span class="text-xs text-gray-400 font-medium flex items-center">
                                <i class="fas fa-times-circle mr-1"></i> Belum rilis
                            </span>
                        @endif
                    </div>
                </div>

                @if (isset($site_settings['jadwal_kelas_9']))
                    <a href="{{ asset('storage/' . $site_settings['jadwal_kelas_9']) }}" target="_blank"
                        class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition"
                        title="Download PDF">
                        <i class="fas fa-download"></i>
                    </a>
                @else
                    <button disabled
                        class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed"
                        title="File belum diupload">
                        <i class="fas fa-lock"></i>
                    </button>
                @endif
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Preview: Jadwal Umum (Contoh Struktur)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Jam Ke-</th>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="bg-white">
                            <td class="px-6 py-4 font-medium">0</td>
                            <td class="px-6 py-4">07:00 - 07:45</td>
                            <td class="px-6 py-4"><span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Upacara
                                    Bendera / Wali Kelas</span></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 font-medium">1</td>
                            <td class="px-6 py-4">07:45 - 08:30</td>
                            <td class="px-6 py-4">KBM Sesi 1</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-6 py-4 font-medium">2</td>
                            <td class="px-6 py-4">08:30 - 09:15</td>
                            <td class="px-6 py-4">KBM Sesi 2</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 font-medium">3</td>
                            <td class="px-6 py-4">09:15 - 10:00</td>
                            <td class="px-6 py-4">KBM Sesi 3</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-6 py-4 font-medium">-</td>
                            <td class="px-6 py-4 font-bold text-gray-800">10:00 - 10:15</td>
                            <td class="px-6 py-4"><span
                                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Istirahat</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
