<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\AssignmentSubmission;

class GradeSubmissionAction
{
    /**
     * Grade submission
     */
    public function execute(int $submission_id, array $data): void
    {
        $submission = AssignmentSubmission::findOrFail($submission_id);
        $submission->update([
            'score' => $data['score'],
            'teacher_feedback' => $data['feedback'] ?? null,
        ]);
    }
}
