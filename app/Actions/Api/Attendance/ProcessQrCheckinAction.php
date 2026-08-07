<?php

namespace App\Actions\Api\Attendance;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentAttendanceDetail;
use Carbon\Carbon;

class ProcessQrCheckinAction
{
    /**
     * Process student QR check-in.
     */
    public function execute(Student $student, string $qrToken): array
    {
        // Cari sesi presensi berdasarkan token
        $attendance = Attendance::where('qr_token', $qrToken)->first();

        if (! $attendance) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid.',
                'status_code' => 422,
            ];
        }

        // Cek apakah token sudah kadaluarsa
        if ($attendance->qr_expires_at && Carbon::now()->isAfter($attendance->qr_expires_at)) {
            return [
                'success' => false,
                'message' => 'QR Code sudah kadaluarsa. Minta guru untuk refresh QR.',
                'status_code' => 422,
            ];
        }

        // Cek apakah siswa ada di kelas yang sama
        if ((string) $student->classroom_id !== (string) $attendance->class) {
            return [
                'success' => false,
                'message' => 'QR Code ini bukan untuk kelas Anda.',
                'status_code' => 403,
            ];
        }

        // Cek apakah sudah check-in sebelumnya
        $existing = StudentAttendanceDetail::where('attendance_id', $attendance->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            $statusLabel = [
                'present' => 'Hadir',
                'late' => 'Terlambat',
                'sick' => 'Sakit',
                'permission' => 'Izin',
                'absent' => 'Alpa',
            ][$existing->status] ?? $existing->status;

            return [
                'success' => true,
                'already_checked' => true,
                'message' => "Anda sudah tercatat: $statusLabel.",
                'data' => $existing,
                'status_code' => 200,
            ];
        }

        // Tentukan status: jika sudah lewat 15 menit dari start_time, tandai "terlambat"
        $status = 'present';
        if ($attendance->start_time) {
            $startDateTime = Carbon::parse($attendance->date.' '.$attendance->start_time);
            if (Carbon::now()->diffInMinutes($startDateTime, false) < -15) {
                $status = 'late';
            }
        }

        // Simpan kehadiran
        $detail = StudentAttendanceDetail::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => $status,
        ]);

        return [
            'success' => true,
            'already_checked' => false,
            'message' => $status === 'late'
                ? 'Presensi berhasil dicatat, tapi Anda terlambat.'
                : 'Presensi berhasil! Selamat belajar 🎉',
            'data' => $detail,
            'status_code' => 200,
        ];
    }
}
