<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;

class UpdateClassroomAction
{
    /**
     * Mengupdate data kelas
     */
    public function execute(array $data, Classroom $classroom): Classroom
    {
        $classroom->update($data);

        return $classroom;
    }
}
