<?php

namespace App\Actions\Api\Student;

use App\Models\Assignment;
use App\Models\Student;

class GetStudentAssignmentsAction
{
    /**
     * Get all assignments + submissions for a student.
     */
    public function execute(Student $student): array
    {
        $assignments = Assignment::with(['subject', 'teacher', 'submissions' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('due_date', 'asc')
            ->get();

        return [
            'success' => true,
            'data' => $assignments,
        ];
    }
}
