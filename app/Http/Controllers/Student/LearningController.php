<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Material;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        if (! $student->classroom_id) {
            return view('student.lms.no-class');
        }

        $myCourses = Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $student->classroom_id)
            ->get()
            ->groupBy(function ($data) {
                return $data->subject->name;
            });

        return view('student.lms.index', compact('myCourses', 'student'));
    }

    /**
     * Masuk ke Ruang Kelas Mapel tertentu untuk lihat materi.
     */
    public function show($schedule_id)
    {
        $student = Auth::guard('student')->user();

        $schedule = Schedule::with(['subject', 'teacher', 'classroom'])
            ->findOrFail($schedule_id);

        if ($schedule->classroom_id != $student->classroom_id) {
            abort(403, 'Anda bukan siswa dari kelas ini.');
        }

        $materials = Material::where('schedule_id', $schedule_id)
            ->latest()
            ->get();

        $assignments = Assignment::with(['submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
            ->where('classroom_id', $student->classroom_id)
            ->where('subject_id', $schedule->subject_id)
            ->latest()
            ->get();

        return view('student.lms.course', compact('schedule', 'materials', 'assignments'));
    }

    public function submitAssignment(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required',
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $studentId = Auth::guard('student')->id();

        $filePath = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $request->assignment_id,
                'student_id' => $studentId,
            ],
            [
                'file_path' => $filePath,
                'student_note' => $request->note,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
