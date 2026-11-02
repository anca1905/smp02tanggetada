<?php

namespace App\Actions\Recap;

use App\Models\StudentAttendance;

class UpdateStudentAttendanceStatusAction
{
    /**
     * Memperbarui status kehadiran siswa pada record student_attendance_details.
     */
    public function execute(StudentAttendance $attendanceDetail, string $newStatus): StudentAttendance
    {
        $attendanceDetail->update([
            'status' => $newStatus,
        ]);

        return $attendanceDetail;
    }
}
