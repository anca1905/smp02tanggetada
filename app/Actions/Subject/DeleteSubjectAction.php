<?php

namespace App\Actions\Subject;

use App\Models\Subject;
use Illuminate\Support\Facades\Storage;

class DeleteSubjectAction
{
    /**
     * Menghapus subject/mata pelajaran
     */
    public function execute(Subject $subject): void
    {
        if ($subject->cover && Storage::disk('public')->exists($subject->cover)) {
            Storage::disk('public')->delete($subject->cover);
        }

        $subject->delete();
    }
}
