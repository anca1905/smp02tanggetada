<?php

namespace App\Actions\Api\Student;

use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Http\Request;

class SubmitAssignmentAction
{
    /**
     * Submit an assignment for a student.
     */
    public function execute(Request $request, Student $student, int $assignmentId): array
    {
        $assignment = Assignment::findOrFail($assignmentId);

        if ($assignment->classroom_id != $student->classroom_id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access.',
                'status_code' => 403,
            ];
        }

        $submission = \App\Models\AssignmentSubmission::firstOrNew([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('assignments/submissions', $filename, 'public');
            $submission->file_path = $path;
        }

        $submission->student_note = $request->student_note;
        $submission->submitted_at = now();
        $submission->save();

        return [
            'success' => true,
            'message' => 'Assignment submitted successfully.',
            'data' => $submission,
            'status_code' => 200,
        ];
    }
}
