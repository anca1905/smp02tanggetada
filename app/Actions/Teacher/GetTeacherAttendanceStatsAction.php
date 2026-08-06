<?php

namespace App\Actions\Teacher;

use App\Models\Teacher_absence;
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
            'datang' => Teacher_absence::whereDate('date', $today)
                ->whereNotNull('arrival_time')
                ->count(),
            'pulang' => Teacher_absence::whereDate('date', $today)
                ->whereNotNull('return_time')
                ->count(),
        ];
    }
}
