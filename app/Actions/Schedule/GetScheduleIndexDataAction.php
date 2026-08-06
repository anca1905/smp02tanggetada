<?php

namespace App\Actions\Schedule;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;

class GetScheduleIndexDataAction
{
    /**
     * Mengambil kumpulan data yang diperlukan untuk halaman daftar jadwal pelajaran.
     *
     * @param  string|int|null  $selectedClassId  ID kelas yang sedang difilter (jika ada)
     */
    public function execute($selectedClassId = null): array
    {
        $classrooms = Classroom::orderBy('level')
            ->orderBy('name')
            ->get();
        $schedules = collect();

        if ($selectedClassId) {
            $schedules = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $selectedClassId)
                ->orderByRaw(
                    "FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')",
                )
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');
        }

        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        return compact(
            'classrooms',
            'selectedClassId',
            'schedules',
            'subjects',
            'teachers',
        );
    }
}
