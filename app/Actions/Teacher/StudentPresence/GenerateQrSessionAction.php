<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GenerateQrSessionAction
{
    /**
     * Membuat atau update sesi presensi, lalu generate random token QR.
     * Expired 5 menit dari sekarang.
     *
     * @param  array  $data  Validated data (class, session_type, date)
     */
    public function execute(array $data): array
    {
        $guru = Auth::guard('teacher')->user();

        $sessionType = $data['session_type'] ?? 'kelas';
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

        $token = Str::random(32);
        $header->update([
            'qr_token' => $token,
            'qr_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return [
            'success' => true,
            'qr_token' => $token,
            'expires_at' => $header->qr_expires_at->toISOString(),
            'class' => $data['class'],
            'session_type' => $sessionType,
            'subject_id' => $subjectId,
            'date' => $data['date'],
        ];
    }
}
