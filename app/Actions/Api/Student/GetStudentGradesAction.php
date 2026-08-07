<?php

namespace App\Actions\Api\Student;

use App\Models\Student;
use App\Models\StudentGrade;

class GetStudentGradesAction
{
    /**
     * Get grades + summary + distribution for a student.
     */
    public function execute(Student $student): array
    {
        $grades = StudentGrade::with(['subject'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $average = $grades->count() > 0 ? $grades->avg('score') : 0;
        $highest = $grades->count() > 0 ? $grades->max('score') : 0;
        $lowest = $grades->count() > 0 ? $grades->min('score') : 0;

        // Group grades into A, B, C, D (dummy logic based on standard KKM 75)
        // A >= 90, B >= 80, C >= 75, D < 75
        $distribution = [
            'A' => $grades->where('score', '>=', 90)->count(),
            'B' => $grades->whereBetween('score', [80, 89.9])->count(),
            'C' => $grades->whereBetween('score', [75, 79.9])->count(),
            'D' => $grades->where('score', '<', 75)->count(),
        ];

        return [
            'success' => true,
            'data' => [
                'grades' => $grades,
                'summary' => [
                    'average' => round($average, 2),
                    'highest' => round($highest, 2),
                    'lowest' => round($lowest, 2),
                    'total_subjects' => $grades->count(),
                ],
                'distribution' => $distribution,
            ],
        ];
    }
}
