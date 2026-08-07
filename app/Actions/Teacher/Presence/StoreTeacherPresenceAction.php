<?php

namespace App\Actions\Teacher\Presence;

use App\Models\Teacher;
use App\Models\Teacher_absence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StoreTeacherPresenceAction
{
    /**
     * Store teacher presence
     *
     * @return array
     */
    public function execute(array $data)
    {
        $teacher = Teacher::where('ID', $data['identity'])
            ->orWhere('name', $data['identity'])
            ->orWhere('username', $data['identity'])
            ->first();

        if (! $teacher) {
            return [
                'status' => 'error',
                'message' => 'Data guru tidak ditemukan.',
            ];
        }

        Auth::guard('teacher')->login($teacher);

        if (! Hash::check($data['password'], $teacher->password)) {
            return [
                'status' => 'error',
                'message' => 'Password salah. Silakan coba lagi.',
            ];
        }

        $image = $data['image'];
        $image = preg_replace("/^data:image\/\w+;base64,/", '', $image);
        $image = str_replace(' ', '+', $image);
        $fileName =
            'attendance/'.
            date('Y-m-d').
            '_'.
            $teacher->id.
            '_'.
            time().
            '.png';

        Storage::disk('public')->put($fileName, base64_decode($image));
        $photoUrl = 'storage/'.$fileName;

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        $timeNow = $now->format('H:i:s');
        $dateNowFormatted = $now->isoFormat('dddd, D MMMM Y');

        $attendance = Teacher_absence::where('teacher_id', $teacher->id)
            ->where('date', $today)
            ->first();

        $statusKeterangan = '';
        $tipeAbsen = '';

        if (! $attendance) {
            $tipeAbsen = 'Absen Datang';
            $batasMasuk = Carbon::createFromTime(7, 30, 0);
            $statusKeterangan = $now->gt($batasMasuk)
                ? 'Terlambat'
                : 'Tepat Waktu';

            Teacher_absence::create([
                'teacher_id' => $teacher->id,
                'date' => $today,
                'arrival_time' => $timeNow,
                'arrival_photo_url' => $photoUrl,
            ]);
        } elseif (
            $attendance->arrival_time &&
            is_null($attendance->return_time)
        ) {
            $arrivalTime = Carbon::parse($attendance->arrival_time);
            if ($now->diffInMinutes($arrivalTime) < 1) {
                return [
                    'status' => 'warning',
                    'message' => 'Anda baru saja absen masuk. Tunggu beberapa saat.',
                ];
            }

            $tipeAbsen = 'Absen Pulang';
            $batasPulang = Carbon::createFromTime(14, 0, 0);
            $statusKeterangan = $now->lt($batasPulang)
                ? 'Pulang Cepat'
                : 'Tepat Waktu';

            $attendance->update([
                'return_time' => $timeNow,
                'return_photo_url' => $photoUrl,
            ]);
        } else {
            return [
                'status' => 'info',
                'message' => 'Anda sudah menyelesaikan absensi hari ini.',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Presensi Berhasil',
            'detail_status' => $statusKeterangan,
            'detail' => [
                'nama' => $teacher->name,
                'id' => $teacher->employee_id,
                'tanggal' => $dateNowFormatted,
                'waktu' => $timeNow,
                'keterangan' => "$tipeAbsen ($statusKeterangan)",
            ],
        ];
    }
}
