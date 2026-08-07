<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\Assignment;
use Illuminate\Http\UploadedFile;

class StoreAssignmentAction
{
    /**
     * Store assignment
     */
    public function execute(array $data, int $teacher_id, ?UploadedFile $file): Assignment
    {
        $filePath = null;
        if ($file) {
            $filePath = $file->store('assignments', 'public');
        }

        return Assignment::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'],
            'file_path' => $filePath,
            'classroom_id' => $data['classroom_id'],
            'subject_id' => $data['subject_id'],
            'teacher_id' => $teacher_id,
        ]);
    }
}
