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
     * @param  array  $data  Validated data (class, date)
     */
    public function execute(array $data): array
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
            'date' => $data['date'],
        ];
    }
}
