@extends('layouts.app')

@section('title', 'Presensi Siswa')

@section('content')
    <div class="space-y-6">

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('teacher.student-attendance') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                    <select name="kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required onchange="this.form.submit()">
                        <option value="" disabled {{ !$selectedClass ? 'selected' : '' }}>-- Pilih Kelas --</option>
                        @foreach ($classList as $kls)
                            <option value="{{ $kls }}" {{ $selectedClass == $kls ? 'selected' : '' }}>Kelas
                                {{ $kls }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $selectedDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                </div>
                <div class="md:col-span-2 flex items-center">
                    @if ($selectedClass)
                        <span class="text-sm {{ $attendanceData ? 'text-green-600' : 'text-gray-500' }}">
                            <i class="fas {{ $attendanceData ? 'fa-check-circle' : 'fa-info-circle' }} mr-1"></i>
                            {{ $attendanceData ? 'Data sudah tersimpan (Mode Edit)' : 'Belum ada data absensi (Mode Input Baru)' }}
                        </span>
                    @endif
                </div>
            </form>
        </div>

        @if ($selectedClass && count($students) > 0)
            <form action="{{ route('teacher.student-attendance-store') }}" method="POST">
                @csrf
                <input type="hidden" name="class" value="{{ $selectedClass }}">
                <input type="hidden" name="date" value="{{ $selectedDate }}">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 w-10">No</th>
                                    <th class="px-6 py-3">Nama Siswa</th>
                                    <th class="px-6 py-3 text-center">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($students as $index => $student)
                                    @php
                                        $status = $student->saved_status ?? 'present';
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900">{{ $student->student_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $student->nis }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center space-x-4">
                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="present" class="peer sr-only"
                                                        {{ $status == 'present' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white text-gray-400 transition-all hover:bg-green-50">
                                                        <span class="font-bold">H</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-green-600 font-medium">Hadir</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="sick" class="peer sr-only"
                                                        {{ $status == 'sick' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-yellow-400 peer-checked:border-yellow-500 peer-checked:text-white text-gray-400 transition-all hover:bg-yellow-50">
                                                        <span class="font-bold">S</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-yellow-500 font-medium">Sakit</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="permit" class="peer sr-only"
                                                        {{ $status == 'permit' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-blue-500 peer-checked:border-blue-600 peer-checked:text-white text-gray-400 transition-all hover:bg-blue-50">
                                                        <span class="font-bold">I</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-blue-600 font-medium">Izin</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="alpha" class="peer sr-only"
                                                        {{ $status == 'alpha' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-red-500 peer-checked:border-red-600 peer-checked:text-white text-gray-400 transition-all hover:bg-red-50">
                                                        <span class="font-bold">A</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-red-600 font-medium">Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end sticky bottom-0 z-10">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-lg flex items-center transform transition hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Presensi
                        </button>
                    </div>
                </div>
            </form>
        @elseif($selectedClass)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Tidak ada siswa ditemukan di <strong>Kelas {{ $selectedClass }}</strong>.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div
                class="flex flex-col items-center justify-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                <div class="p-4 bg-blue-50 rounded-full mb-3">
                    <i class="fas fa-user-graduate text-3xl text-blue-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Mulai Presensi</h3>
                <p class="text-gray-500">Silakan pilih <strong>Kelas</strong> dan <strong>Tanggal</strong> di atas untuk
                    memuat daftar siswa.</p>
            </div>
        @endif

    </div>
@endsection
