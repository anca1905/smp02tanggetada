<?php

namespace App\Actions\Teacher;

use App\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class CreateTeacherAction
{
    /**
     * Membuat data guru baru
     */
    public function execute(array $data, ?UploadedFile $photoFile): Teacher
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($photoFile instanceof UploadedFile) {
            $filename = time().'_'.$photoFile->getClientOriginalName();
            $photoFile->move(public_path('img/teacher-photo'), $filename);
            $data['photo_url'] = 'img/teacher-photo/'.$filename;
        }

        return Teacher::create($data);
    }
}
