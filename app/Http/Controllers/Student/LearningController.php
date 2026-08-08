<?php

namespace App\Http\Controllers\Student;

use App\Actions\Student\Learning\GetCourseDetailAction;
use App\Actions\Student\Learning\GetStudentCoursesAction;
use App\Actions\Student\Learning\SubmitStudentAssignmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index(GetStudentCoursesAction $action)
    {
        $student = Auth::guard('student')->user();

        if (! $student->classroom_id) {
            // Jika view tidak ada, kita redirect aja ke halaman yang valid atau tampilkan teks sederhana.
            // Sebelumnya error karena no-class view not found.
            // Bisa redirect ke halaman profile / dashboard jika ada, atau return string.
            return response('Anda belum terdaftar di kelas apapun.', 200);
        }

        $data = $action->execute();

        return view('student.lms.index', $data);
    }

    /**
     * Masuk ke Ruang Kelas Mapel tertentu untuk lihat materi.
     */
    public function show($schedule_id, GetCourseDetailAction $action)
    {
        $data = $action->execute($schedule_id);

        return view('student.lms.course', $data);
    }

    public function submitAssignment(SubmitAssignmentRequest $request, SubmitStudentAssignmentAction $action)
    {
        $action->execute($request);

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
