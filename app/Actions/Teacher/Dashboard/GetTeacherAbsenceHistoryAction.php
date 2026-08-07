<?php

namespace App\Actions\Teacher\Dashboard;

use App\Models\Teacher;
use App\Models\Teacher_absence;

class GetTeacherAbsenceHistoryAction
{
    /**
     * Get paginated absence history for teacher
     *
     * @param  int  $bulan
     * @param  int  $tahun
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function execute(Teacher $teacher, $bulan, $tahun)
    {
        return Teacher_absence::where('teacher_id', $teacher->id)
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->orderBy('date', 'desc')
            ->paginate(10);
    }
}
