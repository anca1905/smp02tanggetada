<?php

namespace App\Actions\Subject;

use App\Models\Subject;

class DeleteSubjectAction
{
    /**
     * Menghapus subject/mata pelajaran
     */
    public function execute(Subject $subject): void
    {
        $subject->delete();
    }
}
