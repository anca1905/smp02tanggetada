<?php

namespace App\Actions\Student\Learning;

use App\Models\Assignment;
use App\Models\Material;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class GetCourseDetailAction
{
    /**
     * Mengambil schedule + materi + tugas beserta submission milik siswa.
     * Validasi bahwa siswa milik kelas yang benar (abort 403 jika tidak).
     */
    public function execute(int $schedule_id): array
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

        return compact('schedule', 'materials', 'assignments');
    }
}
