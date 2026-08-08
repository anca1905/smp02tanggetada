<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StoreStudentAttendanceAction
{
    /**
     * Menyimpan/update header Attendance dan detail StudentAttendance.
     *
     * @param  array  $data  Validated data (class, date, attendance array)
     */
    public function execute(array $data): void
    {
        $guru = Auth::user();

        $header = Attendance::updateOrCreate(
            [
                'class' => $data['class'],
                'date' => $data['date'],
            ],
            [
                'teacher_id' => $guru->id,
                'start_time' => Carbon::now()->format('H:i:00'),
                'end_time' => Carbon::now()->addHour()->format('H:i:00'),
            ]
        );

        foreach ($data['attendance'] as $nis => $item) {
            $student = Student::where('nis', $nis)->first();
            if (! $student) {
                continue;
            }

            StudentAttendance::updateOrCreate(
                [
                    'attendance_id' => $header->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => $item['status'],
                ]
            );
        }
    }
}
