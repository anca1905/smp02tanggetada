<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\StudentAttendanceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceCheckinController extends Controller
{
    /**
     * Siswa scan QR lalu check-in.
     * POST /api/student/attendance/checkin
     * Body: { qr_token: "xxx" }
     */
    public function checkin(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $student = $request->user();

        // Cari sesi presensi berdasarkan token
        $attendance = Attendance::where('qr_token', $request->qr_token)->first();

        if (! $attendance) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid.',
            ], 422);
        }

        // Cek apakah token sudah kadaluarsa
        if ($attendance->qr_expires_at && Carbon::now()->isAfter($attendance->qr_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code sudah kadaluarsa. Minta guru untuk refresh QR.',
            ], 422);
        }

        // Cek apakah siswa ada di kelas yang sama
        $classroomId = $student->classroom_id;
        if ((string) $student->classroom_id !== (string) $attendance->class) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code ini bukan untuk kelas Anda.',
            ], 403);
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

            return response()->json([
                'success' => true,
                'already_checked' => true,
                'message' => "Anda sudah tercatat: $statusLabel.",
                'data' => $existing,
            ]);
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

        return response()->json([
            'success' => true,
            'already_checked' => false,
            'message' => $status === 'late'
                ? 'Presensi berhasil dicatat, tapi Anda terlambat.'
                : 'Presensi berhasil! Selamat belajar 🎉',
            'data' => $detail,
        ]);
    }

    /**
     * Daftar siswa yang sudah check-in untuk sesi tertentu.
     * GET /api/student/attendance/checkin-list?class=X&date=Y
     * (Bisa diakses tanpa auth – hanya untuk display di web guru)
     */
    public function checkinList(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::where('class', $request->class)
            ->where('date', $request->date)
            ->first();

        if (! $attendance) {
            return response()->json(['success' => true, 'data' => []]);
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

        return response()->json(['success' => true, 'data' => $details]);
    }

    /**
     * Ambil detail sesi dari token (untuk preview sebelum confirm di mobile).
     * GET /api/student/attendance/session?qr_token=xxx
     */
    public function sessionInfo(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $attendance = Attendance::with('teacher')->where('qr_token', $request->qr_token)->first();

        if (! $attendance) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid.',
            ], 422);
        }

        $expired = $attendance->qr_expires_at && Carbon::now()->isAfter($attendance->qr_expires_at);

        return response()->json([
            'success' => true,
            'data' => [
                'class' => $attendance->class,
                'date' => $attendance->date,
                'start_time' => $attendance->start_time,
                'teacher_name' => $attendance->teacher?->name ?? '-',
                'is_expired' => $expired,
                'expires_at' => $attendance->qr_expires_at?->toISOString(),
            ],
        ]);
    }
}
