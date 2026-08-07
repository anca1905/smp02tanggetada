<?php

namespace App\Actions\Api\Student;

use App\Models\Schedule;
use App\Models\Student;

class GetStudentMaterialsAction
{
    /**
     * Get materials based on student's classroom schedules.
     */
    public function execute(Student $student): array
    {
        $scheduleIds = Schedule::where('classroom_id', $student->classroom_id)->pluck('id');

        $materials = \App\Models\Material::with(['schedule.subject', 'schedule.teacher'])
            ->whereIn('schedule_id', $scheduleIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'success' => true,
            'data' => $materials,
        ];
    }
}
