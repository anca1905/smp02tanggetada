<?php

namespace App\Actions\Api\Attendance;

use App\Models\Attendance;
use Carbon\Carbon;

class GetSessionInfoAction
{
    /**
     * Get session info from QR token.
     */
    public function execute(string $qrToken): array
    {
        $attendance = Attendance::with(['teacher', 'subject'])->where('qr_token', $qrToken)->first();

        if (! $attendance) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid.',
                'status_code' => 422,
            ];
        }

        $expired = $attendance->qr_expires_at && Carbon::now()->isAfter($attendance->qr_expires_at);

        return [
            'success' => true,
            'data' => [
                'class' => $attendance->class,
                'date' => $attendance->date,
                'start_time' => $attendance->start_time,
                'teacher_name' => $attendance->teacher?->name ?? '-',
                'subject_name' => $attendance->subject?->name ?? null,
                'is_expired' => $expired,
                'expires_at' => $attendance->qr_expires_at?->toISOString(),
            ],
            'status_code' => 200,
        ];
    }
}
