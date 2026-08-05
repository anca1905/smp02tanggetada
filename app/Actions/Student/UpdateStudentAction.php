<?php

namespace App\Actions\Student;

use App\Models\Student;

class UpdateStudentAction
{
    /**
     * Mengupdate data siswa dan password orang tua jika belum diatur
     *
     * @param array $data Data siswa yang akan diupdate
     * @param Student $student Instance siswa yang akan diupdate
     * @return Student Instance siswa yang sudah diupdate
     */
    public function execute(array $data, Student $student): Student
    {
        if (empty($student->parent_password)) {
            $data["parent_password"] = bcrypt("ortu" . $data["nis"]);
        }

        $student->update($data);

        return $student;
    }
}
