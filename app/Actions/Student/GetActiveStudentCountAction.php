<?php

namespace App\Actions\Student;

use App\Models\Student;

class GetActiveStudentCountAction
{
    /**
     * Menghitung jumlah total siswa aktif
     */
    public function execute(): int
    {
        return Student::where('student_status', 'Active')->count();
    }
}
