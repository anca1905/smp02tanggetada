<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Student;

class GetStudentsByClassAction
{
    /**
     * Ambil daftar siswa berdasarkan ID kelas untuk keperluan AJAX.
     *
     * @param  int|string  $kelas
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function execute($kelas)
    {
        return Student::where('classroom_id', $kelas)
            ->orderBy('student_name')
            ->get(['id', 'nis', 'student_name']);
    }
}
