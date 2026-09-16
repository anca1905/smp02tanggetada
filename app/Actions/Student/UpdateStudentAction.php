<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateStudentAction
{
    /**
     * Mengupdate data siswa dan password orang tua jika belum diatur
     *
     * @param  array  $data  Data siswa yang akan diupdate
     * @param  Student  $student  Instance siswa yang akan diupdate
     * @return Student Instance siswa yang sudah diupdate
     */
    public function execute(array $data, Student $student): Student
    {
        if (empty($student->parent_password)) {
            $nis = $data['nis'] ?? $student->nis;
            $data['parent_password'] = bcrypt('ortu'.$nis);
        }

        if (isset($data['photo_url']) && $data['photo_url'] instanceof UploadedFile) {
            if ($student->photo_url && Storage::disk('public')->exists($student->photo_url)) {
                Storage::disk('public')->delete($student->photo_url);
            }
            $data['photo_url'] = $data['photo_url']->store('students/photos', 'public');
        } else {
            unset($data['photo_url']);
        }

        $student->update($data);

        return $student;
    }
}
