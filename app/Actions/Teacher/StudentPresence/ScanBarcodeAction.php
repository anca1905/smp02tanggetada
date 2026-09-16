<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScanBarcodeAction
{
    /**
     * Proses scan barcode (NIS) siswa untuk satu sesi absensi.
     *
     * @param  string  $nis
     * @param  string  $sessionType  apel|kelas|pulang
     * @param  string  $classId      ID kelas (classroom_id)
     * @param  string  $date         Y-m-d
     * @return array   ['success', 'message', 'student_name', 'already_checked']
     */
    public function execute(string $nis, string $sessionType, string $classId, string $date): array
    {
        // Cari siswa berdasarkan NIS
        $student = Student::where('nis', $nis)->first();

        if (! $student) {
            return [
                'success' => false,
                'message' => "Siswa dengan NIS {$nis} tidak ditemukan.",
                'student_name' => null,
                'already_checked' => false,
            ];
        }

        // Pastikan siswa benar-benar ada di kelas ini
        if ((string) $student->classroom_id !== (string) $classId) {
            return [
                'success' => false,
                'message' => "{$student->student_name} bukan bagian dari kelas ini.",
                'student_name' => $student->student_name,
                'already_checked' => false,
            ];
        }

        return DB::transaction(function () use ($student, $sessionType, $classId, $date) {
            // Ambil atau buat header sesi attendance
            $header = Attendance::firstOrCreate(
                [
                    'class' => $classId,
                    'session_type' => $sessionType,
                    'date' => $date,
                ],
                [
                    'teacher_id' => null,
                    'start_time' => Carbon::now()->format('H:i:00'),
                    'end_time' => null,
                ]
            );

            // Cek apakah sudah scan di sesi ini
            $existing = StudentAttendance::where('attendance_id', $header->id)
                ->where('student_id', $student->id)
                ->first();

            if ($existing) {
                return [
                    'success' => false,
                    'message' => "{$student->student_name} sudah absen di sesi ini.",
                    'student_name' => $student->student_name,
                    'already_checked' => true,
                ];
            }

            // Catat kehadiran
            StudentAttendance::create([
                'attendance_id' => $header->id,
                'student_id' => $student->id,
                'status' => 'present',
            ]);

            return [
                'success' => true,
                'message' => "{$student->student_name} berhasil di-absen.",
                'student_name' => $student->student_name,
                'already_checked' => false,
                'scan_time' => Carbon::now()->format('H:i:s'),
            ];
        });
    }
}
