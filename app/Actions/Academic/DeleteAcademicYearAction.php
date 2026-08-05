<?php

namespace App\Actions\Academic;

use App\Models\AcademicYear;

class DeleteAcademicYearAction
{
    /**
     * Menghapus tahun ajaran
     *
     * @param AcademicYear $academicYear
     * @return void
     */
    public function execute(AcademicYear $academicYear): void
    {
        $academicYear->delete();
    }
}
