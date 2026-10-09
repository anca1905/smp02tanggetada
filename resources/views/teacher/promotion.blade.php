@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')
    <div class="max-w-5xl mx-auto">



        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-level-up-alt text-blue-600"></i> Proses Kenaikan Kelas
                        </h2>
                        <p class="text-sm text-gray-600 mt-0.5">
                            Wali Kelas: <span class="font-bold text-gray-800">{{ $kelas ?? 'Belum ditentukan' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            @if ($isFinalGrade)
                <div class="p-10 text-center bg-blue-50/40">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Kelas {{ $kelas }} adalah Tingkat Akhir</h3>
                    <p class="text-sm text-gray-600 mt-2 max-w-md mx-auto">
                        Siswa pada kelas tingkat 9 (tingkat akhir SMP) tidak diproses naik kelas, melainkan diproses kelulusan siswa.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('teacher.graduation') }}"
                            class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">
                            <i class="fas fa-graduation-cap mr-2"></i> Buka Menu Kelulusan Siswa
                        </a>
                    </div>
                </div>
            @elseif ($error)
                <div class="p-6 m-6 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                    <div class="flex items-center gap-2 text-red-800 font-bold mb-1">
                        <i class="fas fa-exclamation-triangle"></i> Kelas Tujuan Belum Tersedia
                    </div>
                    <p class="text-sm text-red-700">{{ $error }}</p>
                </div>
            @elseif (count($students) > 0 && $targetClassroom)
                <form action="{{ route('teacher.promotion.store') }}" method="POST" onsubmit="confirmAction(event, this, 'Proses Kenaikan Kelas?', 'Kelas siswa akan diperbarui secara permanen.', 'Ya, Proses Kenaikan')">
                    @csrf

                    {{-- Target Kenaikan Kelas Otomatis --}}
                    <div class="p-6 bg-white border-b border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-xl bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-green-50/70 border border-blue-100">
                            <div>
                                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-2">
                                    <i class="fas fa-magic"></i> Target Kenaikan Otomatis
                                </span>
                                <div class="flex items-center gap-3">
                                    <div class="bg-white px-3.5 py-1.5 rounded-lg border border-gray-200 shadow-2xs">
                                        <span class="text-[10px] text-gray-400 block uppercase font-semibold">Kelas Asal</span>
                                        <span class="font-bold text-gray-800 text-sm">{{ $kelas }}</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-green-500"></i>
                                    <div class="bg-white px-3.5 py-1.5 rounded-lg border border-green-200 shadow-2xs bg-green-50/50">
                                        <span class="text-[10px] text-green-600 block uppercase font-bold">Naik ke Tingkat {{ $targetClassroom->level }}</span>
                                        <span class="font-bold text-green-700 text-sm flex items-center gap-1">
                                            <i class="fas fa-check-circle text-green-500 text-xs"></i> {{ $targetClassroom->name }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Siswa dengan status <strong class="text-green-700">"Naik Kelas"</strong> akan otomatis dialihkan ke rombel <strong>{{ $targetClassroom->name }}</strong>.
                                </p>
                            </div>

                            @if($nextLevelClassrooms->count() > 1)
                                <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-2xs self-start md:self-auto">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Pilih Rombel Lain (Bila Perlu):
                                    </label>
                                    <select name="next_classroom_id" class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                        @foreach($nextLevelClassrooms as $c)
                                            <option value="{{ $c->id }}" {{ $targetClassroom->id === $c->id ? 'selected' : '' }}>
                                                {{ $c->name }} (Tingkat {{ $c->level }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="next_classroom_id" value="{{ $targetClassroom->id }}">
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold border-b">
                                <tr>
                                    <th class="px-6 py-3.5 w-12 text-center">No</th>
                                    <th class="px-6 py-3.5">Nama Siswa</th>
                                    <th class="px-6 py-3.5">NIS</th>
                                    <th class="px-6 py-3.5 text-center">Keputusan Kenaikan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($students as $index => $siswa)
                                    <tr class="hover:bg-blue-50/40 transition-colors">
                                        <td class="px-6 py-4 text-center text-gray-400 font-mono">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $siswa->student_name }}</td>
                                        <td class="px-6 py-4 font-mono text-gray-500">{{ $siswa->nis }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-4">
                                                <label
                                                    class="cursor-pointer flex items-center px-3.5 py-1.5 rounded-lg border border-gray-200 hover:bg-green-50 hover:border-green-300 transition-all has-[:checked]:bg-green-100 has-[:checked]:border-green-500 has-[:checked]:text-green-700 text-xs font-semibold">
                                                    <input type="radio" name="action[{{ $siswa->nis }}]" value="Naik"
                                                        class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300"
                                                        checked>
                                                    <span class="ml-2">Naik Kelas</span>
                                                </label>

                                                <label
                                                    class="cursor-pointer flex items-center px-3.5 py-1.5 rounded-lg border border-gray-200 hover:bg-red-50 hover:border-red-300 transition-all has-[:checked]:bg-red-100 has-[:checked]:border-red-500 has-[:checked]:text-red-700 text-xs font-semibold">
                                                    <input type="radio" name="action[{{ $siswa->nis }}]" value="Tinggal"
                                                        class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                                    <span class="ml-2">Tinggal Kelas</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 border-t border-gray-100 bg-gray-50/80 flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            Total Siswa: <strong>{{ count($students) }}</strong> siswa
                        </span>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm flex items-center gap-2 transition hover:-translate-y-0.5">
                            <i class="fas fa-check"></i> Proses Kenaikan Kelas
                        </button>
                    </div>
                </form>
            @else
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                    <p class="font-medium">Tidak ada siswa aktif di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
