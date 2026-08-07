<?php

namespace App\Actions\Academic;

use App\Models\AcademicYear;

class CreateAcademicYearAction
{
    /**
     * Membuat tahun akademik baru
     */
    public function execute(array $data): AcademicYear
    {
        return AcademicYear::create([
            'name' => $data['name'],
            'semester' => $data['semester'],
            'is_active' => false,
        ]);
    }
}
