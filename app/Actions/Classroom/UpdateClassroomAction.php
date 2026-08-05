<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;

class UpdateClassroomAction
{
    /**
     * Mengupdate data kelas
     *
     * @param array $data
     * @param Classroom $classroom
     * @return Classroom
     */
    public function execute(array $data, Classroom $classroom): Classroom
    {
        $classroom->update($data);
        return $classroom;
    }
}
