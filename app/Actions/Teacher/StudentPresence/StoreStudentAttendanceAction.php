<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreStudentAttendanceAction
{
    /**
     * Menyimpan/update header Attendance dan detail StudentAttendance.
     *
     * @param  array  $data  Validated data (class, session_type, date, attendance array)
     */
    public function execute(array $data): void
    {
        $guru = Auth::guard('teacher')->user();

        DB::transaction(function () use ($data, $guru) {
            $sessionType = $data['session_type'];
            $subjectId = ($sessionType === 'kelas') ? ($data['subject_id'] ?? null) : null;

            $matchAttributes = [
                'class' => $data['class'],
                'session_type' => $sessionType,
                'date' => $data['date'],
            ];

            if ($sessionType === 'kelas') {
                $matchAttributes['subject_id'] = $subjectId;
            } else {
                $matchAttributes['subject_id'] = null;
            }

            $header = Attendance::updateOrCreate(
                $matchAttributes,
                [
                    'teacher_id' => $guru?->id,
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
        });
    }
}
