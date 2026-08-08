<?php

namespace App\Actions\Student\Learning;

use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class GetStudentCoursesAction
{
    /**
     * Mengambil schedules berdasarkan classroom_id siswa, group by subject name.
     */
    public function execute(): array
    {
        $student = Auth::guard('student')->user();

        if (! $student->classroom_id) {
            return [];
        }

        $myCourses = Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $student->classroom_id)
            ->get()
            ->groupBy(function ($data) {
                return $data->subject->name;
            });

        return compact('myCourses', 'student');
    }
}
