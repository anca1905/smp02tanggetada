<?php

namespace App\Actions\Api\Student;

use App\Models\Student;
use App\Models\StudentAttendanceDetail;

class GetStudentAttendancesAction
{
    /**
     * Get attendance history for a student.
     */
    public function execute(Student $student): array
    {
        $attendances = StudentAttendanceDetail::with(['attendance.teacher'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'success' => true,
            'data' => $attendances,
        ];
    }
}
