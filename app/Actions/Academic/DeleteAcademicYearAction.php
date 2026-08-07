<?php

namespace App\Actions\Academic;

use App\Models\AcademicYear;

class DeleteAcademicYearAction
{
    /**
     * Menghapus tahun ajaran
     */
    public function execute(AcademicYear $academicYear): void
    {
        $academicYear->delete();
    }
}
