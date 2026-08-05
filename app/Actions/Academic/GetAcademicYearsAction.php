<?php

namespace App\Actions\Academic;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Collection;

class GetAcademicYearsAction
{
    /**
     * Mengambil daftar tahun akademik terbaru
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function execute(): Collection
    {
        return AcademicYear::orderBy("name", "desc")->get();
    }
}
