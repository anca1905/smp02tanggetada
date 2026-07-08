@extends('layouts.app')

@section('title', 'Kelulusan Siswa')

@section('content')
    <div class="max-w-5xl mx-auto">



        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-blue-50">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Proses Kelulusan Siswa</h2>
                    <p class="text-sm text-blue-600 font-medium">Kelas: {{ $kelas }}</p>
                </div>
                <div class="text-sm text-gray-500">
                    Total Siswa: <span class="font-bold">{{ count($students) }}</span>
                </div>
            </div>

            @if (count($students) > 0)
                <form action="{{ route('teacher.graduation.store') }}" method="POST" onsubmit="confirmAction(event, this, 'Proses Kelulusan?', 'Data siswa yang Lulus akan diperbarui.', 'Ya, Proses')">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                                <tr>
                                    <th class="px-6 py-3 w-12">No</th>
                                    <th class="px-6 py-3">Nama Siswa</th>
                                    <th class="px-6 py-3">NIS</th>
                                    <th class="px-6 py-3 text-center">Status Kelulusan</th>
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
                                                    <input type="radio" name="status[{{ $siswa->nis }}]" value="Lulus"
                                                        class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300"
                                                        checked>
                                                    <span class="ml-2 font-medium">Lulus</span>
                                                </label>

                                                <label
                                                    class="cursor-pointer flex items-center p-2 rounded-lg border border-gray-200 hover:bg-red-50 hover:border-red-300 transition-all has-[:checked]:bg-red-100 has-[:checked]:border-red-500 has-[:checked]:text-red-700">
                                                    <input type="radio" name="status[{{ $siswa->nis }}]"
                                                        value="Tidak Lulus"
                                                        class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                                    <span class="ml-2 font-medium">Tertunda</span>
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
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md flex items-center transition-transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Data Kelulusan
                        </button>
                    </div>
                </form>
            @else
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-user-graduate text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada siswa aktif di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
