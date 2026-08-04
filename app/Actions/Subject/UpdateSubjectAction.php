<?php

namespace App\Actions\Subject;

use App\Models\Subject;

class UpdateSubjectAction
{
    /**
     * Mengupdate data subject/mata pelajaran
     */
    public function execute(array $data, Subject $subject): Subject
    {
        $subject->update($data);
        return $subject;
    }
}
