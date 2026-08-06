<?php

namespace App\Actions\Schedule;

use App\Models\Schedule;
use Illuminate\Validation\ValidationException;

class CreateScheduleAction
{
    /**
     * Membuat jadwal pelajaran baru setelah memeriksa ketiadaan bentrok waktu mengajar bagi guru.
     *
     * @param  array  $data  Data schedule yang akan divalidasi dan disimpan
     *
     * @throws ValidationException Jika terdapat bentrok jam mengajar pada guru di waktu yang sama
     */
    public function execute(array $data): Schedule
    {
        // Pengecekan bentrok jadwal mengajar (Overlap Detection) dengan formula:
        // (existing.start_time < new.end_time) AND (existing.end_time > new.start_time)
        $isOverlap = Schedule::where('teacher_id', $data['teacher_id'])
            ->where('day', $data['day'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();

        if ($isOverlap) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Guru yang bersangkutan sudah ada jadwal mengajar di jam dan hari tersebut pada kelas lain!',
            ]);
        }

        return Schedule::create($data);
    }
}
