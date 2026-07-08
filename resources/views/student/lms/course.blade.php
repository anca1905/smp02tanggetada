@extends('layouts.student')

@section('title', $schedule->subject->name)

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Banner Kelas --}}
        <div class="bg-indigo-700 rounded-2xl h-40 p-5 flex flex-col justify-end text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent z-0"></div>
            <img src="https://source.unsplash.com/random/1200x400/?school,study"
                class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40" alt="Banner">

            <div class="relative z-10">
                <h1 class="text-2xl font-bold">{{ $schedule->subject->name }}</h1>
                <div class="flex items-center mt-2 space-x-2 text-[10px]">
                    <span class="flex items-center bg-white/20 px-3 py-1 rounded backdrop-blur-sm">
                        <i class="fas fa-user-tie mr-2"></i> {{ $schedule->teacher->name }}
                    </span>
                    <span class="flex items-center bg-white/20 px-3 py-1 rounded backdrop-blur-sm">
                        <i class="fas fa-clock mr-2"></i> {{ $schedule->day }}
                        ({{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }})
                    </span>
                </div>
            </div>
        </div>

        <div class="flex border-b border-gray-200 bg-white px-2 rounded-t-2xl sticky top-0 z-20 shadow-sm mt-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <button onclick="switchTab('materi')" id="btn-materi" class="px-4 py-3 text-indigo-600 border-b-2 border-indigo-600 font-bold text-sm w-1/3 text-center transition">
                Materi
            </button>
            <button onclick="switchTab('tugas')" id="btn-tugas" class="px-4 py-3 text-gray-400 font-medium text-sm w-1/3 text-center transition">
                Tugas
            </button>
            <button onclick="switchTab('pengajar')" id="btn-pengajar" class="px-4 py-3 text-gray-400 font-medium text-sm w-1/3 text-center transition">
                Pengajar
            </button>
        </div>

        <div class="grid grid-cols-1 gap-5">
            {{-- TAB MATERI --}}
            <div id="content-materi" class="space-y-4">
                @forelse($materials as $material)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                {{-- Icon --}}
                                <div class="shrink-0">
                                    @if ($material->type == 'pdf')
                                        <div class="w-12 h-12 bg-red-50 text-red-500 rounded-lg flex items-center justify-center border border-red-100">
                                            <i class="fas fa-file-pdf text-2xl"></i>
                                        </div>
                                    @elseif($material->type == 'youtube')
                                        <div class="w-12 h-12 bg-red-600 text-white rounded-lg flex items-center justify-center shadow-red-200">
                                            <i class="fab fa-youtube text-2xl"></i>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center border border-blue-100">
                                            <i class="fas fa-link text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $material->title }}</h3>
                                    <p class="text-gray-600 text-sm mt-1 leading-relaxed">{{ $material->description }}</p>
                                    <p class="text-xs text-gray-400 mt-2 mb-4">Diposting {{ $material->created_at->diffForHumans() }}</p>

                                    {{-- Action Button --}}
                                    @if ($material->type == 'pdf')
                                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                            <i class="fas fa-download mr-2"></i> Download Materi
                                        </a>
                                    @elseif($material->type == 'youtube')
                                        <div class="mt-3 aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-black shadow-lg">
                                            <iframe src="{{ str_replace('watch?v=', 'embed/', $material->file_path) }}"
                                                frameborder="0" allowfullscreen class="w-full h-48"></iframe>
                                        </div>
                                    @else
                                        <a href="{{ $material->file_path }}" target="_blank"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-medium hover:bg-indigo-100 transition">
                                            <i class="fas fa-external-link-alt mr-2"></i> Buka Tautan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" class="w-40 mx-auto opacity-50" alt="Empty">
                        <h3 class="text-gray-800 font-bold mt-4">Belum Ada Materi</h3>
                        <p class="text-gray-500 text-sm">Guru belum memposting materi apapun di kelas ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- TAB TUGAS --}}
            <div id="content-tugas" class="space-y-4 hidden">
                @forelse($assignments as $assignment)
                    @php $submission = $assignment->submissions->first(); @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-bold text-gray-800 text-lg flex-1">{{ $assignment->title }}</h3>
                                @if($submission)
                                    @if($submission->score !== null)
                                        <div class="bg-blue-600 text-white px-3 py-1 rounded-lg font-bold text-sm">
                                            {{ $submission->score }}
                                        </div>
                                    @else
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-bold">Terkumpul</span>
                                    @endif
                                @else
                                    <span class="bg-red-50 text-red-500 px-2 py-1 rounded text-[10px] font-bold">Belum Kumpul</span>
                                @endif
                            </div>

                            <p class="text-gray-500 text-xs mb-3 line-clamp-2">{{ $assignment->description }}</p>
                            
                            <div class="flex items-center text-[10px] text-gray-400 mb-4 bg-gray-50 p-2 rounded-lg">
                                <i class="far fa-clock mr-2 text-red-400"></i>
                                <span>Batas Waktu: <strong class="text-gray-600">{{ $assignment->due_date->format('d M, H:i') }}</strong></span>
                            </div>

                            @if($assignment->file_path)
                                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank"
                                    class="block w-full mb-3 text-center py-2 border-2 border-dashed border-indigo-100 text-indigo-600 rounded-xl text-xs font-bold hover:bg-indigo-50 transition">
                                    <i class="fas fa-file-download mr-2"></i> Download Lampiran Guru
                                </a>
                            @endif

                            @if($submission)
                                <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100">
                                    <div class="flex items-center justify-between text-[10px] mb-2">
                                        <span class="text-indigo-600 font-bold">Jawaban Anda</span>
                                        <span class="text-gray-400 italic">Kumpul: {{ $submission->submitted_at->format('d/m, H:i') }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                        class="flex items-center text-xs text-gray-700 hover:text-indigo-600 font-medium">
                                        <i class="fas fa-file-alt mr-2 text-indigo-400"></i> Lihat file saya
                                    </a>
                                    @if($submission->teacher_feedback)
                                        <div class="mt-2 pt-2 border-t border-indigo-100">
                                            <p class="text-[9px] text-indigo-400 font-bold uppercase tracking-widest mb-1">Feedback Guru:</p>
                                            <p class="text-xs text-indigo-700 italic">"{{ $submission->teacher_feedback }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <button onclick="openSubmitModal({{ $assignment->id }}, '{{ $assignment->title }}')"
                                    class="w-full py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 active:scale-95 transition">
                                    Upload Tugas Sekarang
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" class="w-40 mx-auto opacity-50" alt="Empty">
                        <h3 class="text-gray-800 font-bold mt-4">Tidak Ada Tugas</h3>
                        <p class="text-gray-500 text-sm">Santai dulu, belum ada tugas di mapel ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- TAB PENGAJAR --}}
            <div id="content-pengajar" class="space-y-4 hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($schedule->teacher->name) }}&background=6366f1&color=fff&size=128"
                        class="w-24 h-24 rounded-full border-4 border-indigo-50 shadow-xl mx-auto" alt="Avatar">
                    <h5 class="font-bold text-gray-800 text-lg mt-4">{{ $schedule->teacher->name }}</h5>
                    <p class="text-sm text-gray-400">Guru Mata Pelajaran</p>
                    
                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">NIP</p>
                            <p class="text-xs font-bold text-gray-700">{{ $schedule->teacher->employee_id ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Kontak</p>
                            <p class="text-xs font-bold text-gray-700">{{ $schedule->teacher->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <button class="w-full mt-6 py-3 bg-indigo-50 text-indigo-600 rounded-xl text-sm font-bold hover:bg-indigo-100 transition flex items-center justify-center">
                        <i class="far fa-comment-dots mr-2 text-lg"></i> Hubungi Guru
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL SUBMIT TUGAS --}}
    <div id="submitModal" class="fixed inset-0 bg-black/60 hidden items-end justify-center z-[60] backdrop-blur-sm">
        <div class="bg-white rounded-t-[2.5rem] w-full max-w-[480px] p-8 pb-10 animate-slide-up">
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>
            
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Kumpulkan Tugas</h3>
                <p class="text-sm text-gray-500 mt-1">Pastikan file sudah benar sebelum diunggah.</p>
            </div>

            <form action="{{ route('student.lms.assignment.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="assignment_id" id="modalAssignmentId">
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Upload File (PDF/DOCX/JPG)</label>
                        <div class="relative group">
                            <input type="file" name="file" required
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 border-2 border-dashed border-gray-200 p-4 rounded-2xl cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Catatan (Opsional)</label>
                        <textarea name="note" rows="3" 
                            class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 p-4 text-sm" 
                            placeholder="Tulis pesan ke guru jika perlu..."></textarea>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeSubmitModal()"
                        class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl text-sm font-bold active:scale-95 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] py-4 bg-indigo-600 text-white rounded-2xl text-sm font-bold shadow-lg shadow-indigo-200 active:scale-95 transition">
                        Kirim Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes slide-up {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        .animate-slide-up { animation: slide-up 0.3s ease-out; }
    </style>

    <script>
        function switchTab(tab) {
            const contents = ['materi', 'tugas', 'pengajar'];
            contents.forEach(c => {
                document.getElementById('content-' + c).classList.add('hidden');
                const btn = document.getElementById('btn-' + c);
                btn.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600', 'font-bold');
                btn.classList.add('text-gray-400', 'font-medium');
            });

            document.getElementById('content-' + tab).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tab);
            activeBtn.classList.remove('text-gray-400', 'font-medium');
            activeBtn.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600', 'font-bold');
        }

        function openSubmitModal(id, title) {
            document.getElementById('modalAssignmentId').value = id;
            document.getElementById('modalTitle').innerText = title;
            const modal = document.getElementById('submitModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSubmitModal() {
            const modal = document.getElementById('submitModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
",Description:
