<?php

namespace App\Actions\Teacher\Promotion;

use App\Models\Student;
use App\Models\Teacher;

class GetPromotionDataAction
{
    /**
     * Get students and classrooms data for promotion
     */
    public function execute(Teacher $teacher): array
    {
        $kelas = null;
        $students = collect();

        if ($teacher->classroom) {
            $kelas = $teacher->classroom->name;
            $students = Student::where('classroom_id', $teacher->classroom->id)
                ->where('student_status', 'Active')
                ->orderBy('student_name', 'asc')
                ->get();
        }

        $allClassrooms = \App\Models\Classroom::orderBy('level')->orderBy('name')->get();

        return compact('students', 'kelas', 'allClassrooms');
    }
}
