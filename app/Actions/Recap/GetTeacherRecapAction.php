<?php

namespace App\Actions\Recap;

use App\Models\Teacher_absence;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTeacherRecapAction
{
    /**
     * Mengambil data rekap kehadiran guru berdasarkan filter
     */
    public function execute(int $bulan, int $tahun, ?string $search): LengthAwarePaginator
    {
        $query = Teacher_absence::with('teacher')
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun);

        if ($search) {
            $query->whereHas('teacher', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('date', 'desc')->paginate(10);
    }
}
