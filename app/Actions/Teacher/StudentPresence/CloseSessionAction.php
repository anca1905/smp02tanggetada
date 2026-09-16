<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CloseSessionAction
{
    /**
     * Tutup sesi absensi:
     * - Set end_time di header Attendance
     * - Siswa yang belum scan otomatis dicatat sebagai 'absent' (Alpha)
     *
     * @param  string  $sessionType  apel|kelas|pulang
     * @param  string  $date  Y-m-d
     * @return array ['success', 'absent_count', 'message']
     */
    public function execute(string $classId, string $sessionType, string $date, ?int $subjectId = null): array
    {
        return DB::transaction(function () use ($classId, $sessionType, $date, $subjectId) {
            $query = Attendance::where('class', $classId)
                ->where('session_type', $sessionType)
                ->where('date', $date);

            if ($sessionType === 'kelas' && $subjectId) {
                $query->where('subject_id', $subjectId);
            } elseif ($sessionType !== 'kelas') {
                $query->whereNull('subject_id');
            }

            $header = $query->first();

            // Jika belum ada sesi sama sekali, buat header kosong dulu
            if (! $header) {
                $header = Attendance::create([
                    'class' => $classId,
                    'session_type' => $sessionType,
                    'subject_id' => $sessionType === 'kelas' ? $subjectId : null,
                    'date' => $date,
                    'teacher_id' => null,
                    'start_time' => Carbon::now()->format('H:i:00'),
                    'end_time' => Carbon::now()->format('H:i:00'),
                ]);
            } else {
                $header->update(['end_time' => Carbon::now()->format('H:i:00')]);
            }

            // Ambil semua siswa di kelas ini
            $allStudents = Student::where('classroom_id', $classId)->get();

            // Ambil ID siswa yang sudah scan
            $scannedIds = StudentAttendance::where('attendance_id', $header->id)
                ->pluck('student_id')
                ->toArray();

            // Auto-mark absent untuk yang belum scan
            $absentCount = 0;
            foreach ($allStudents as $student) {
                if (! in_array($student->id, $scannedIds)) {
                    StudentAttendance::create([
                        'attendance_id' => $header->id,
                        'student_id' => $student->id,
                        'status' => 'absent',
                    ]);
                    $absentCount++;
                }
            }

            $sessionLabel = match ($sessionType) {
                'apel' => 'Apel Pagi',
                'kelas' => 'Di Kelas',
                'pulang' => 'Pulang',
                default => $sessionType,
            };

            return [
                'success' => true,
                'absent_count' => $absentCount,
                'message' => "Sesi {$sessionLabel} ditutup. {$absentCount} siswa dicatat Alpha.",
            ];
        });
    }
}
