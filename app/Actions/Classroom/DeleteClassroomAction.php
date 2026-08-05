<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;

class DeleteClassroomAction
{
    /**
     * Menghapus kelas dari database.
     *
     * @param Classroom $classroom
     * @return void
     */
    public function execute(Classroom $classroom): void
    {
        $classroom->delete();
    }
}
