<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\Schedule;
use App\Models\Material;
use App\Models\Assignment;

class GetCourseDetailsAction
{
    /**
     * Get course details (schedule, materials, assignments)
     *
     * @param int $schedule_id
     * @param int $teacher_id
     * @return array
     */
    public function execute(int $schedule_id, int $teacher_id): array
    {
        $schedule = Schedule::with(['classroom', 'subject', 'classroom.students'])
            ->findOrFail($schedule_id);

        if ($schedule->teacher_id != $teacher_id) {
            abort(403);
        }

        $materials = Material::where('schedule_id', $schedule_id)
            ->latest()
            ->get();

        $assignments = Assignment::withCount('submissions')
            ->where('classroom_id', $schedule->classroom_id)
            ->where('subject_id', $schedule->subject_id)
            ->latest()
            ->get();

        return compact('schedule', 'materials', 'assignments');
    }
}
