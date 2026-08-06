<?php

namespace App\Actions\Teacher;

use App\Models\Teacher;

class GetActiveTeacherCountAction
{
    /**
     * Menghitung jumlah total guru aktif
     */
    public function execute(): int
    {
        return Teacher::where('status', 'Active')->count();
    }
}
