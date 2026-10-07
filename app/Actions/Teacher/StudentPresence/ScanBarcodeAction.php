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
     * Mendukung pemindaian per-kelas spesifik atau seluruh siswa (school-wide) saat apel/pulang.
     *
     * @param  string  $sessionType  apel|kelas|pulang
     * @param  string|null  $classId  ID kelas (classroom_id) atau 'all' untuk seluruh siswa
     * @param  string  $date  Y-m-d
     * @return array ['success', 'message', 'student_name', 'classroom_name', 'classroom_id', 'already_checked', 'scan_time', 'total_present']
     */
    public function execute(string $nis, string $sessionType, ?string $classId, string $date): array
    {
        $nis = trim($nis);

        // Cari siswa berdasarkan NIS beserta relasi kelasnya
        $student = Student::with('classroom')->where('nis', $nis)->first();

        if (! $student) {
            return [
                'success' => false,
                'message' => "Siswa dengan NIS {$nis} tidak ditemukan.",
                'student_name' => null,
                'classroom_name' => null,
                'classroom_id' => null,
                'already_checked' => false,
            ];
        }

        // Tentukan apakah dalam mode seluruh siswa (school-wide)
        // Jika classId bernilai 'all', kosong, atau null: otomatis gunakan kelas siswa itu sendiri
        $isSchoolWide = ($classId === 'all' || empty($classId));
        $targetClassId = $isSchoolWide ? (string) $student->classroom_id : (string) $classId;

        // Pastikan siswa benar-benar ada di kelas ini jika bukan mode school-wide
        if (! $isSchoolWide && (string) $student->classroom_id !== $targetClassId) {
            return [
                'success' => false,
                'message' => "{$student->student_name} bukan bagian dari kelas ini.",
                'student_name' => $student->student_name,
                'classroom_name' => $student->classroom?->name,
                'classroom_id' => $student->classroom_id,
                'already_checked' => false,
            ];
        }

        return DB::transaction(function () use ($student, $sessionType, $targetClassId, $date, $isSchoolWide) {
            // Ambil atau buat header sesi attendance untuk kelas siswa
            $header = Attendance::firstOrCreate(
                [
                    'class' => $targetClassId,
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
                    'classroom_name' => $student->classroom?->name,
                    'classroom_id' => $student->classroom_id,
                    'already_checked' => true,
                ];
            }

            // Catat kehadiran
            StudentAttendance::create([
                'attendance_id' => $header->id,
                'student_id' => $student->id,
                'status' => 'present',
            ]);

            $totalPresent = $isSchoolWide
                ? StudentAttendance::whereHas('attendance', fn ($q) => $q->where('session_type', $sessionType)->where('date', $date))->where('status', 'present')->count()
                : StudentAttendance::where('attendance_id', $header->id)->where('status', 'present')->count();

            return [
                'success' => true,
                'message' => "{$student->student_name} berhasil di-absen.",
                'student_name' => $student->student_name,
                'classroom_name' => $student->classroom?->name,
                'classroom_id' => $student->classroom_id,
                'already_checked' => false,
                'scan_time' => Carbon::now()->format('H:i:s'),
                'total_present' => $totalPresent,
            ];
        });
    }
}
