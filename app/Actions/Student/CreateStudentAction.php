<?php

namespace App\Actions\Student;

use App\Models\Student;

class CreateStudentAction
{
    /**
     * Membuat data siswa baru
     *
     * @param array $data
     * @return Student
     */
    public function execute(array $data): Student
    {
        $data["password"] = bcrypt($data["nis"]);
        $data["parent_password"] = bcrypt("ortu" . $data["nis"]);
        return Student::create($data);
    }
}
