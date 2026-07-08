@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')
    <div class="max-w-5xl mx-auto">



        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-yellow-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Proses Kenaikan Kelas</h2>
                        <p class="text-sm text-yellow-700 font-medium">Kelas Asal: {{ $kelas }}</p>
                    </div>
                </div>
            </div>

            @if (count($students) > 0)
                <form action="{{ route('teacher.promotion.store') }}" method="POST" onsubmit="confirmAction(event, this, 'Proses Kenaikan Kelas?', 'Kelas siswa akan diperbarui secara permanen.', 'Ya, Proses Kenaikan')">
                    @csrf

                    <div class="p-6 bg-white border-b border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Naik ke Kelas (Tujuan)</label>
                        <select name="next_classroom_id" required class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Pilih Kelas Tujuan</option>
                            @if(isset($allClassrooms))
                                @foreach($allClassrooms as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} (Tingkat {{ $c->level }})</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Siswa yang dipilih "Naik" akan dipindahkan ke kelas ini.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                                <tr>
                                    <th class="px-6 py-3 w-12">No</th>
                                    <th class="px-6 py-3">Nama Siswa</th>
                                    <th class="px-6 py-3">NIS</th>
                                    <th class="px-6 py-3 text-center">Keputusan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($students as $index => $siswa)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $siswa->student_name }}</td>
                                        <td class="px-6 py-4">{{ $siswa->nis }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-4">
                                                <label
                                                    class="cursor-pointer flex items-center p-2 rounded-lg border border-gray-200 hover:bg-green-50 hover:border-green-300 transition-all has-[:checked]:bg-green-100 has-[:checked]:border-green-500 has-[:checked]:text-green-700">
                                                    <input type="radio" name="action[{{ $siswa->nis }}]" value="Naik"
                                                        class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300"
                                                        checked>
                                                    <span class="ml-2 font-medium">Naik Kelas</span>
                                                </label>

                                                <label
                                                    class="cursor-pointer flex items-center p-2 rounded-lg border border-gray-200 hover:bg-red-50 hover:border-red-300 transition-all has-[:checked]:bg-red-100 has-[:checked]:border-red-500 has-[:checked]:text-red-700">
                                                    <input type="radio" name="action[{{ $siswa->nis }}]" value="Tinggal"
                                                        class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                                    <span class="ml-2 font-medium">Tinggal Kelas</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end">
                        <button type="submit"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md flex items-center transition-transform hover:-translate-y-0.5">
                            <i class="fas fa-level-up-alt mr-2"></i> Proses Kenaikan
                        </button>
                    </div>
                </form>
            @else
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada siswa aktif di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
