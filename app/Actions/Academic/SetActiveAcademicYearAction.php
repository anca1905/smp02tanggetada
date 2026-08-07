<?php

namespace App\Actions\Academic;

use App\Models\AcademicYear;

class SetActiveAcademicYearAction
{
    /**
     * Mengaktifkan tahun ajaran dan menonaktifkan semua tahun ajaran lainnya
     */
    public function execute(AcademicYear $academicYear): AcademicYear
    {
        AcademicYear::query()->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        return $academicYear;
    }
}
