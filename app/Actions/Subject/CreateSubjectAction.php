<?php

namespace App\Actions\Subject;

use App\Models\Subject;

class CreateSubjectAction
{
    /**
     * Membuat subject/mata pelajaran baru

     * @param array $data
     * @return Subject
     */
    public function execute(array $data): Subject
    {
        return Subject::create($data);
    }
}
