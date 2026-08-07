<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\AcademicYear;
use App\Models\Schedule;

class GetTeacherSchedulesAction
{
    /**
     * Get teacher schedules grouped by classroom and subject
     */
    public function execute(int $teacherId): array
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (! $activeYear) {
            return ['error' => 'Tahun ajaran aktif belum diset oleh Admin!'];
        }

        $myClasses = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacherId)
            ->whereHas('classroom', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->get()
            ->groupBy(function ($data) {
                return $data->classroom->name.' - '.$data->subject->name;
            });

        return [
            'myClasses' => $myClasses,
            'activeYear' => $activeYear,
        ];
    }
}
