<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Http\UploadedFile;

class CreateStudentAction
{
    /**
     * Membuat data siswa baru
     */
    public function execute(array $data): Student
    {
        $data['password'] = bcrypt($data['nis']);
        $data['parent_password'] = bcrypt('ortu'.$data['nis']);

        if (isset($data['photo_url']) && $data['photo_url'] instanceof UploadedFile) {
            $data['photo_url'] = $data['photo_url']->store('students/photos', 'public');
        } else {
            unset($data['photo_url']);
        }

        return Student::create($data);
    }
}
