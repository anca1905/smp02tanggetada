<?php

namespace App\Actions\Teacher\Graduation;

use App\Models\Student;

class ProcessGraduationAction
{
    /**
     * Loop array status dari request dan update status kelulusan siswa.
     * Jika Lulus -> update student_status menjadi Graduated.
     * Jika tidak -> tetap Active.
     */
    public function execute(array $statusArray): void
    {
        foreach ($statusArray as $nis => $status) {
            $student = Student::where('nis', $nis)->first();
            if ($student) {
                $newStatus = ($status == 'Lulus') ? 'Graduated' : 'Active';

                $student->update(['student_status' => $newStatus]);
            }
        }
    }
}
