@extends('layouts.app')

@section('title', 'Submisi Tugas: ' . $assignment->title)

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('teacher.lms.show', ['schedule_id' => $assignment->subject_id]) }}" 
                   class="text-blue-600 hover:underline flex items-center text-sm mb-2">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kelas
                </a>
                <h1 class="text-2xl font-bold text-gray-800">{{ $assignment->title }}</h1>
                <p class="text-gray-500">Daftar pengumpulan tugas siswa</p>
            </div>
            <div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 text-blue-700 font-bold">
                {{ $assignment->submissions->count() }} / {{ $assignment->classroom->students->count() }} Terkumpul
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Kumpul</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($assignment->classroom->students as $student)
                            @php
                                $submission = $assignment->submissions->where('student_id', $student->id)->first();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mr-3">
                                            {{ substr($student->student_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $student->student_name }}</div>
                                            <div class="text-xs text-gray-400">NIS: {{ $student->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($submission)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Sudah Kumpul</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold">Belum Kumpul</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($submission)
                                        {{ $submission->submitted_at->format('d/m/Y, H:i') }}
                                        @if ($submission->submitted_at > $assignment->due_date)
                                            <span class="text-red-500 text-xs font-bold block">Terlambat</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($submission && $submission->score !== null)
                                        <span class="text-lg font-bold text-blue-600">{{ $submission->score }}</span>
                                    @elseif($submission)
                                        <span class="text-gray-400 italic text-sm">Belum dinilai</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($submission)
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800" title="Download Jawaban">
                                                <i class="fas fa-file-download text-lg"></i>
                                            </a>
                                            <button onclick="openGradeModal({{ json_encode($submission) }})"
                                                class="text-indigo-600 hover:text-indigo-800" title="Beri Nilai">
                                                <i class="fas fa-edit text-lg"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL PENILAIAN --}}
    <div id="gradeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Penilaian Tugas</h3>
                <button onclick="document.getElementById('gradeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            
            <form id="gradeForm" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai (0-100)</label>
                        <input type="number" name="score" id="gradeInput" min="0" max="100" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan/Feedback Guru</label>
                        <textarea name="feedback" id="feedbackInput" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" placeholder="Bagus, pertahankan!"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('gradeModal').classList.add('hidden')" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openGradeModal(submission) {
            const form = document.getElementById('gradeForm');
            form.action = `/teacher/lms/submission/${submission.id}/grade`;
            
            document.getElementById('gradeInput').value = submission.score || '';
            document.getElementById('feedbackInput').value = submission.teacher_feedback || '';
            
            document.getElementById('gradeModal').classList.remove('hidden');
        }
    </script>
@endsection
",Description:
