<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class DeleteStudentAction
{
    /**
     * Menghapus student dari database
     */
    public function execute(Student $student): void
    {
        if ($student->photo_url && Storage::disk('public')->exists($student->photo_url)) {
            Storage::disk('public')->delete($student->photo_url);
        }

        $student->delete();
    }
}
