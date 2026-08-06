<?php

namespace App\Actions\Student;

use App\Models\Student;

class DeleteStudentAction
{
    /**
     * Menghapus student dari database
     */
    public function execute(Student $student): void
    {
        $student->delete();
    }
}
