<?php

namespace App\Actions\Teacher;

use App\Models\Teacher;

class DeleteTeacherAction
{
    /**
     * Menghapus data guru yang sudah ada
     */
    public function execute(Teacher $teacher): void
    {
        if (
            $teacher->photo_url &&
            file_exists(public_path($teacher->photo_url))
        ) {
            unlink(public_path($teacher->photo_url));
        }

        $teacher->delete();
    }
}
