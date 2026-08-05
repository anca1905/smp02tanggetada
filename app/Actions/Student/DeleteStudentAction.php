<?php

namespace App\Actions\Student;

use App\Models\Student;

class DeleteStudentAction
{
    /**
     * Menghapus student dari database
     *
     * @param Student $student
     * @return void
     */
    public function execute(Student $student): void
    {
        $student->delete();
    }
}
