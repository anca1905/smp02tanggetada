<?php

namespace App\Actions\Teacher;

use App\Models\TeacherAbsence;
use Carbon\Carbon;

class GetTeacherAttendanceStatsAction
{
    /**
     * Mengambil statistik presensi guru (datang dan pulang) hari ini.
     */
    public function execute(): array
    {
        $today = Carbon::today();

        return [
            'datang' => TeacherAbsence::whereDate('date', $today)
                ->whereNotNull('arrival_time')
                ->count(),
            'pulang' => TeacherAbsence::whereDate('date', $today)
                ->whereNotNull('return_time')
                ->count(),
        ];
    }
}
