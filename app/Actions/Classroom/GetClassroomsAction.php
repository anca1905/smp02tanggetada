<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;

class GetClassroomsAction
{
    /**
     * Mengambil daftar kelas dengan relasi dan
     * dan filter tahun ajaran.
     * @param string|null $selectedYearId
     * @return Collection
     */
    public function execute(?string $selectedYearId): Collection
    {
        return Classroom::with(["academicYear", "teacher", "students"])
            ->when($selectedYearId, function ($query) use ($selectedYearId) {
                return $query->where("academic_year_id", $selectedYearId);
            })
            ->orderBy("level")
            ->orderBy("name")
            ->get();
    }
}
