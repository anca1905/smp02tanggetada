<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;

class GetClassroomsAction
{
    /**
     * Mengambil daftar semua kelas.
     */
    public function execute(?int $academicYearId = null): Collection
    {
        $query = Classroom::with(['teacher', 'students', 'academicYear']);
        
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }
        
        return $query->get();
    }
}
