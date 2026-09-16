<?php

namespace App\Actions\Recap;

use App\Models\StudentAttendance;
use Illuminate\Pagination\LengthAwarePaginator;

class GetStudentRecapAction
{
    /**
     * Mengambil data rekap kehadiran siswa berdasarkan filter.
     *
     * @param  string|int|null  $classId
     * @param  string|null      $sesi     apel|kelas|pulang|null (semua)
     */
    public function execute(int $bulan, int $tahun, ?string $search, $classId, ?string $sesi = null): LengthAwarePaginator
    {
        $query = StudentAttendance::with(['student', 'student.classroom', 'attendance'])
            ->whereHas('attendance', function ($q) use ($bulan, $tahun, $sesi) {
                $q->whereMonth('date', $bulan)
                    ->whereYear('date', $tahun);

                if ($sesi) {
                    $q->where('session_type', $sesi);
                }
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

        return $query->orderByDesc('id')->paginate(15);
    }
}
