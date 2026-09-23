@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')
    <div class="space-y-6">
        {{-- Header & Filter --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Data Kelas</h2>
                <p class="text-gray-500 text-sm">Kelola kelas dan wali kelas per tahun ajaran</p>
            </div>

            <div class="flex gap-3">
                {{-- Filter Tahun Ajaran --}}
                <form action="{{ route('tu.classrooms.index') }}" method="GET" class="flex items-center">
                    <select name="academic_year_id" onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} ({{ $year->semester }})
                            </option>
                        @endforeach
                    </select>
                </form>

                <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Kelas
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
        @endif

        {{-- Grid Card Kelas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classrooms as $class)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $class->name }}</h3>
                            <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded">
                                Tingkat {{ $class->level }}
                            </span>
                        </div>
                        <div class="relative group">
                            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
                            {{-- Dropdown Menu --}}
                            <div
                                class="absolute right-0 mt-2 w-32 bg-white border rounded-lg shadow-lg hidden group-hover:block z-10">
                                <button
                                    onclick="editClass({{ $class->id }}, '{{ $class->name }}', '{{ $class->level }}', '{{ $class->teacher_id }}')"
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Edit</button>
                                <form action="{{ route('tu.classrooms.destroy', $class->id) }}" method="POST"
                                    onsubmit="confirmDelete(event, this);">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user-tie w-6 text-center text-gray-400"></i>
                            <span class="ml-2">{{ $class->teacher->name ?? 'Belum ada Wali Kelas' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users w-6 text-center text-gray-400"></i>
                            <span class="ml-2">{{ $class->students->count() }} Siswa</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar w-6 text-center text-gray-400"></i>
                            <span class="ml-2">{{ $class->academicYear->name }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-3">
                        <i class="fas fa-chalkboard text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500">Belum ada data kelas di tahun ajaran ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="addModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">Buat Kelas Baru</h3>
            <form action="{{ route('tu.classrooms.store') }}" method="POST">
                @csrf
                {{-- Hidden: Otomatis ikut tahun yang sedang dipilih di filter --}}
                <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                    <input type="text" name="name" class="px-4 py-2 w-full border-gray-300 rounded-lg" required
                        placeholder="Contoh: X IPA 1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                    <select name="level" class="px-4 py-2 w-full border border-gray-300 rounded-lg">
                        <option value="7">Kelas 7</option>
                        <option value="8">Kelas 8</option>
                        <option value="9">Kelas 9</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas</label>
                    <select name="teacher_id" class="px-4 py-2 w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
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

    {{-- Javascript untuk Edit Modal bisa ditambahkan nanti jika perlu --}}
@endsection
