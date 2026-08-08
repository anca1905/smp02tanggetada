<?php

namespace App\Actions\Student\Learning;

use App\Models\AssignmentSubmission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class SubmitStudentAssignmentAction
{
    /**
     * Upload file ke submissions disk public, updateOrCreate record AssignmentSubmission.
     */
    public function execute(array $data, UploadedFile $file): void
    {
        $studentId = Auth::guard('student')->id();

        $filePath = $file->store('submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $data['assignment_id'],
                'student_id' => $studentId,
            ],
            [
                'file_path' => $filePath,
                'student_note' => $data['note'] ?? null,
                'submitted_at' => now(),
            ]
        );
    }
}
