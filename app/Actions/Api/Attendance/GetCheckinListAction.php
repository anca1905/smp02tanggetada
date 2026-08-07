<?php

namespace App\Actions\Api\Attendance;

use App\Models\Attendance;
use App\Models\StudentAttendanceDetail;

class GetCheckinListAction
{
    /**
     * Get list of checked-in students for a session.
     */
    public function execute(string $class, string $date): array
    {
        $attendance = Attendance::where('class', $class)
            ->where('date', $date)
            ->first();

        if (! $attendance) {
            return ['success' => true, 'data' => []];
        }

        $details = StudentAttendanceDetail::with('student')
            ->where('attendance_id', $attendance->id)
            ->whereIn('status', ['present', 'late'])
            ->get()
            ->map(fn ($d) => [
                'student_id' => $d->student_id,
                'student_name' => $d->student?->student_name ?? '-',
                'status' => $d->status,
            ]);

        return ['success' => true, 'data' => $details];
    }
}
