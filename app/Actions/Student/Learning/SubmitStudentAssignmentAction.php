<?php

namespace App\Actions\Student\Learning;

use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmitStudentAssignmentAction
{
    /**
     * Upload file ke submissions disk public, updateOrCreate record AssignmentSubmission.
     *
     * @param  Request  $request  (Memerlukan object Request untuk handle file upload langsung)
     */
    public function execute(Request $request): void
    {
        $studentId = Auth::guard('student')->id();

        $filePath = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $request->assignment_id,
                'student_id' => $studentId,
            ],
            [
                'file_path' => $filePath,
                'student_note' => $request->note,
                'submitted_at' => now(),
            ]
        );
    }
}
