<?php

namespace App\Actions\Api\Student;

use App\Models\Schedule;
use App\Models\Student;

class GetStudentSchedulesAction
{
    /**
     * Get all schedules for the student's classroom grouped by day.
     */
    public function execute(Student $student): array
    {
        $schedules = Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return [
            'success' => true,
            'data' => $schedules,
        ];
    }
}
