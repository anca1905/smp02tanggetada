<?php

namespace App\Actions\Teacher\Graduation;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class GetGraduationDataAction
{
    /**
     * Mengambil siswa aktif dari kelas homeroom guru.
     * Parse homeroom_class untuk mendapatkan level kelas.
     */
    public function execute(): array
    {
        $teacher = Auth::user();

        $kelas = (int) filter_var($teacher->homeroom_class, FILTER_SANITIZE_NUMBER_INT);

        $students = Student::where('classroom_id', $kelas)
            ->where('student_status', 'Active')
            ->orderBy('student_name', 'asc')
            ->get();

        return compact('students', 'kelas');
    }
}
