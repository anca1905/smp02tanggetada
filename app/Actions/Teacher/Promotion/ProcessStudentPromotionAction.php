<?php

namespace App\Actions\Teacher\Promotion;

use App\Models\Student;

class ProcessStudentPromotionAction
{
    /**
     * Process student promotions
     */
    public function execute(array $data): void
    {
        foreach ($data['action'] as $nis => $action) {
            $student = Student::where('nis', $nis)->first();

            if ($student) {
                if ($action == 'Naik') {
                    $student->update([
                        'classroom_id' => $data['next_classroom_id'],
                    ]);
                }
            }
        }
    }
}
