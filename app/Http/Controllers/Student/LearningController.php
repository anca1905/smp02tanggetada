<?php

namespace App\Http\Controllers\Student;

use App\Actions\Student\Learning\GetCourseDetailAction;
use App\Actions\Student\Learning\GetStudentCoursesAction;
use App\Actions\Student\Learning\SubmitStudentAssignmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitAssignmentRequest;

class LearningController extends Controller
{
    public function index(GetStudentCoursesAction $action)
    {
        $data = $action->execute();

        if (empty($data)) {
            return response('Anda belum terdaftar di kelas apapun.', 200);
        }

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
        $action->execute($request->validated(), $request->file('file'));

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
