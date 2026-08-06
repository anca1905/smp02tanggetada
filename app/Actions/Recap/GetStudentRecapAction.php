<?php

namespace App\Actions\Recap;

use App\Models\StudentAttendance;
use Illuminate\Pagination\LengthAwarePaginator;

class GetStudentRecapAction
{
    /**
     * Mengambil data rekap kehadiran siswa berdasarkan filter
     *
     * @param  string|int|null  $classId
     */
    public function execute(int $bulan, int $tahun, ?string $search, $classId): LengthAwarePaginator
    {
        $query = StudentAttendance::with(['student', 'attendance'])
            ->whereHas('attendance', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('date', $bulan)
                    ->whereYear('date', $tahun);
            });

        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhereHas('classroom', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($classId !== null && $classId !== '') {
            $query->whereHas('student', function ($q) use ($classId) {
                $q->where('classroom_id', $classId);
            });
        }

        return $query->paginate(10);
    }
}
