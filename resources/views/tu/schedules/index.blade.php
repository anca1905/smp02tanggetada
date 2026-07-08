@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="space-y-6">
    {{-- HEADER & FILTER KELAS --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Jadwal</h2>
            <p class="text-gray-500 text-sm">Atur jadwal pelajaran per kelas secara dinamis.</p>
        </div>
        
        <div class="flex-1 w-full md:w-auto flex flex-col md:flex-row justify-end gap-3">
            {{-- Form Filter Kelas (Otomatis Submit saat ganti pilihan) --}}
            <form action="{{ route('tu.schedules.index') }}" method="GET" class="flex items-center w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-chalkboard text-blue-500"></i>
                    </div>
                    <select name="classroom_id" onchange="this.form.submit()" 
                            class="w-full pl-10 pr-4 py-2 border-blue-200 bg-blue-50 text-blue-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 font-medium cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                Kelas {{ $class->name }} ({{ $class->academicYear->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- Tombol Tambah (Hanya muncul jika kelas sudah dipilih) --}}
            @if($selectedClassId)
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fas fa-plus mr-2"></i> Tambah Jadwal
            </button>
            @endif
        </div>
    </div>

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm flex justify-between items-center">
            <div>
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm">
            <p class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan:</p>
            <ul class="list-disc pl-8 mt-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- KONTEN JADWAL --}}
    @if(!$selectedClassId)
        {{-- State Kosong (User belum pilih kelas) --}}
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
            <div class="bg-blue-50 p-4 rounded-full mb-4">
                <i class="fas fa-search text-4xl text-blue-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700">Kelas Belum Dipilih</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan pilih kelas pada dropdown di atas untuk mengelola jadwal.</p>
        </div>
    @else
        {{-- Grid Jadwal Senin - Sabtu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp
            
            @foreach($days as $day)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                {{-- Header Hari --}}
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="far fa-calendar-alt mr-2 text-blue-500"></i> {{ $day }}
                    </h3>
                    <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded-full">
                        {{ isset($schedules[$day]) ? count($schedules[$day]) : 0 }} Mapel
                    </span>
                </div>
                
                {{-- List Mapel --}}
                <div class="divide-y divide-gray-50 flex-1">
                    @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                        @foreach($schedules[$day] as $schedule)
                        <div class="p-4 hover:bg-blue-50 transition group relative">
                            <div class="flex justify-between items-start gap-3">
                                {{-- Waktu --}}
                                <div class="text-center min-w-[60px]">
                                    <span class="block text-sm font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    </span>
                                    <span class="block text-xs text-gray-400 font-medium">s.d</span>
                                    <span class="block text-sm font-bold text-gray-500">
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                </div>

                                {{-- Detail Mapel --}}
                                <div class="flex-1 border-l-2 border-blue-200 pl-3">
                                    <h4 class="font-bold text-blue-900 text-sm line-clamp-1">
                                        {{ $schedule->subject->name }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-user-tie text-[10px] mr-1.5 w-3"></i> 
                                        {{ $schedule->teacher->name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center">
                                        <i class="fas fa-tag text-[10px] mr-1.5 w-3"></i> 
                                        {{ $schedule->subject->code }}
                                    </p>
                                </div>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('tu.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="confirmDelete(event, this);">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition p-1 rounded hover:bg-red-50" title="Hapus Jadwal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center flex flex-col items-center justify-center h-full text-gray-400">
                            <i class="fas fa-mug-hot text-2xl mb-2 opacity-30"></i>
                            <span class="text-xs italic">Tidak ada jadwal</span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- MODAL TAMBAH JADWAL --}}
@if($selectedClassId)
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all scale-100">
        {{-- Modal Header --}}
        <div class="bg-blue-600 px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Jadwal Pelajaran
            </h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-white hover:text-blue-200 transition text-xl font-bold">
                &times;
            </button>
        </div>

        {{-- Modal Body --}}
        <form action="{{ route('tu.schedules.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $selectedClassId }}">

            <div class="grid grid-cols-1 gap-5">
                {{-- Hari --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hari</label>
                    <select name="day" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>

                {{-- Jam Mulai & Selesai --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                {{-- Mata Pelajaran --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="subject_id" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Guru --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Guru Pengajar</label>
                    <select name="teacher_id" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" 
                        class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30 transition">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- SCRIPT OPSIONAL: Tutup modal kalau klik di luar --}}
<script>
    window.onclick = function(event) {
        const modal = document.getElementById('addModal');
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection