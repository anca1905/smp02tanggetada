@extends('layouts.app')

@section('title', $schedule->subject->name)

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Banner Kelas --}}
        <div class="bg-blue-600 rounded-xl h-48 p-8 flex flex-col justify-end text-white shadow-lg relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-0"></div>
            <img src="https://source.unsplash.com/random/1200x400/?technology,code"
                class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50" alt="Banner">

            <div class="relative z-10">
                <h1 class="text-3xl font-bold">{{ $schedule->subject->name }}</h1>
                <p class="text-xl opacity-90 mt-1 flex items-center">
                    <i class="fas fa-chalkboard-teacher mr-2"></i> {{ $schedule->classroom->name }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar Kiri --}}
            <div class="hidden lg:block space-y-4">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wide">Jadwal</h4>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p class="flex items-center"><i class="far fa-calendar-alt w-6 text-blue-500"></i> {{ $schedule->day }}</p>
                        <p class="flex items-center"><i class="far fa-clock w-6 text-blue-500"></i>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wide">Kode Kelas</h4>
                    <div class="bg-gray-100 p-3 rounded text-center cursor-pointer hover:bg-gray-200 transition"
                        onclick="navigator.clipboard.writeText('{{ $schedule->subject->code }}'); alert('Kode disalin!')">
                        <span class="font-mono text-lg font-bold text-blue-600">{{ $schedule->subject->code }}</span>
                    </div>
                    <p class="text-xs text-gray-400 text-center mt-2">Klik untuk menyalin</p>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3 space-y-5">
                {{-- Tab Switcher --}}
                <div class="flex border-b border-gray-200">
                    <button onclick="switchTab('materi')" id="tab-materi"
                        class="px-6 py-3 border-b-2 border-blue-600 text-blue-600 font-bold transition">
                        <i class="fas fa-book mr-2"></i> Materi
                    </button>
                    <button onclick="switchTab('tugas')" id="tab-tugas"
                        class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-blue-600 font-bold transition">
                        <i class="fas fa-tasks mr-2"></i> Tugas
                    </button>
                </div>

                {{-- MATERI TAB --}}
                <div id="content-materi" class="space-y-5">
                    <div onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4 cursor-pointer hover:shadow-md transition group">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                            <i class="fas fa-plus text-xl"></i>
                        </div>
                        <span class="text-gray-500 font-medium group-hover:text-blue-600">Bagikan materi baru ke kelas ini...</span>
                    </div>

                    @forelse($materials as $material)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:border-blue-200 transition">
                            <div class="p-5 flex items-start gap-4">
                                <div class="shrink-0">
                                    @if ($material->type == 'pdf')
                                        <div class="w-12 h-12 bg-red-100 text-red-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-2xl"></i>
                                        </div>
                                    @elseif($material->type == 'youtube')
                                        <div class="w-12 h-12 bg-red-600 text-white rounded-lg flex items-center justify-center">
                                            <i class="fab fa-youtube text-2xl"></i>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-link text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-bold text-gray-800 text-lg hover:text-blue-600">
                                            <a href="{{ $material->type == 'pdf' ? asset('storage/' . $material->file_path) : $material->file_path }}" target="_blank">
                                                {{ $material->title }}
                                            </a>
                                        </h3>
                                        <div class="relative group/menu ml-2">
                                            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v px-2"></i></button>
                                            <div class="absolute right-0 mt-1 w-32 bg-white border rounded shadow-lg hidden group-hover/menu:block z-10">
                                                <form action="{{ route('teacher.lms.material.destroy', $material->id) }}" method="POST" onsubmit="confirmDelete(event, this);">
                                                    @csrf @method('DELETE')
                                                    <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">{{ $material->description }}</p>
                                    @if ($material->type == 'pdf')
                                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200 transition">
                                            <i class="fas fa-download mr-2"></i> Download PDF
                                        </a>
                                    @elseif($material->type == 'youtube')
                                        <div class="mt-2 aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-black">
                                            <iframe src="{{ str_replace('watch?v=', 'embed/', $material->file_path) }}" frameborder="0" allowfullscreen class="w-full h-64"></iframe>
                                        </div>
                                    @else
                                        <a href="{{ $material->file_path }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded text-sm hover:bg-blue-100 transition">
                                            <i class="fas fa-external-link-alt mr-2"></i> Buka Tautan
                                        </a>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-3">Diposting {{ $material->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <h3 class="text-gray-500 font-medium">Belum ada materi</h3>
                            <p class="text-gray-400 text-sm">Klik tombol di atas untuk mulai membagikan materi.</p>
                        </div>
                    @endforelse
                </div>

                {{-- TUGAS TAB --}}
                <div id="content-tugas" class="space-y-5 hidden">
                    <div onclick="document.getElementById('assignmentModal').classList.remove('hidden')"
                        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4 cursor-pointer hover:shadow-md transition group">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                            <i class="fas fa-clipboard-list text-xl"></i>
                        </div>
                        <span class="text-gray-500 font-medium group-hover:text-indigo-600">Buat tugas baru untuk kelas ini...</span>
                    </div>

                    @forelse($assignments as $assignment)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:border-indigo-200 transition">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg">{{ $assignment->title }}</h3>
                                        <div class="flex items-center text-xs text-red-500 font-bold mt-1">
                                            <i class="far fa-clock mr-1"></i> Deadline: {{ $assignment->due_date->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <div class="relative group/menu ml-2">
                                        <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v px-2"></i></button>
                                        <div class="absolute right-0 mt-1 w-32 bg-white border rounded shadow-lg hidden group-hover/menu:block z-10">
                                            <form action="{{ route('teacher.lms.assignment.destroy', $assignment->id) }}" method="POST" onsubmit="confirmDelete(event, this);">
                                                @csrf @method('DELETE')
                                                <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mb-4">{{ $assignment->description }}</p>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-2"></i>
                                        <span class="font-bold text-blue-600 mr-1">{{ $assignment->submissions_count }}</span> Siswa mengumpulkan
                                    </div>
                                    <a href="{{ route('teacher.lms.assignment.submissions', $assignment->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm">
                                        Lihat Jawaban <i class="fas fa-chevron-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                            <i class="fas fa-tasks text-4xl text-gray-300 mb-3"></i>
                            <h3 class="text-gray-500 font-medium">Belum ada tugas</h3>
                            <p class="text-gray-400 text-sm">Klik tombol di atas untuk membuat tugas baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD --}}
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Bagikan Materi Baru</h3>
                <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('teacher.lms.material.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" placeholder="Contoh: Pertemuan 1 - Aljabar" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" placeholder="Instruksi singkat..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Materi</label>
                        <select name="type" id="typeSelector" onchange="toggleInputType()" class="w-full border-gray-300 rounded-lg focus:ring-blue-500">
                            <option value="pdf">File PDF / Dokumen</option>
                            <option value="youtube">Video Youtube</option>
                            <option value="link">Link Website / Artikel</option>
                        </select>
                    </div>
                    <div id="inputPdf">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload File (Max 10MB)</label>
                        <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                    <div id="inputUrl" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Masukkan URL</label>
                        <input type="url" name="url" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" placeholder="https://...">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow">Posting</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TUGAS --}}
    <div id="assignmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Buat Tugas Baru</h3>
                <button onclick="document.getElementById('assignmentModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('teacher.lms.assignment.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="classroom_id" value="{{ $schedule->classroom_id }}">
                <input type="hidden" name="subject_id" value="{{ $schedule->subject_id }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" placeholder="Contoh: Tugas 1 - Persamaan Kuadrat" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instruksi Tugas</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" placeholder="Jelaskan detail tugas..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batas Waktu (Deadline)</label>
                        <input type="datetime-local" name="due_date" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran File (Opsional)</label>
                        <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('assignmentModal').classList.add('hidden')" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold shadow">Terbitkan Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const materiContent = document.getElementById('content-materi');
            const tugasContent = document.getElementById('content-tugas');
            const materiBtn = document.getElementById('tab-materi');
            const tugasBtn = document.getElementById('tab-tugas');

            if (tab === 'materi') {
                materiContent.classList.remove('hidden');
                tugasContent.classList.add('hidden');
                materiBtn.classList.add('border-blue-600', 'text-blue-600');
                materiBtn.classList.remove('border-transparent', 'text-gray-500');
                tugasBtn.classList.remove('border-blue-600', 'text-blue-600');
                tugasBtn.classList.add('border-transparent', 'text-gray-500');
            } else {
                materiContent.classList.add('hidden');
                tugasContent.classList.remove('hidden');
                tugasBtn.classList.add('border-blue-600', 'text-blue-600');
                tugasBtn.classList.remove('border-transparent', 'text-gray-500');
                materiBtn.classList.remove('border-blue-600', 'text-blue-600');
                materiBtn.classList.add('border-transparent', 'text-gray-500');
            }
        }

        function toggleInputType() {
            const type = document.getElementById('typeSelector').value;
            if (type === 'pdf') {
                document.getElementById('inputPdf').classList.remove('hidden');
                document.getElementById('inputUrl').classList.add('hidden');
            } else {
                document.getElementById('inputPdf').classList.add('hidden');
                document.getElementById('inputUrl').classList.remove('hidden');
            }
        }
    </script>
@endsection
",Description:
