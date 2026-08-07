<?php

namespace App\Actions\Subject;

use App\Models\Subject;

class CreateSubjectAction
{
    /**
     * Membuat subject/mata pelajaran baru
     */
    public function execute(array $data): Subject
    {
        return Subject::create($data);
    }
}
