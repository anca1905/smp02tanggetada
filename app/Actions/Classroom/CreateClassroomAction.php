<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;

class CreateClassroomAction
{
    /**
     * Membuat kelas baru
     *
     * @param array $data
     * @return Classroom
     */
    public function execute(array $data): Classroom
    {
        return Classroom::create($data);
    }
}
