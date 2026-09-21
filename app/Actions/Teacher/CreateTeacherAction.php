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
            $path = $photoFile->store('teachers/photos', 'public');
            $data['photo_url'] = $path;
        }

        return Teacher::create($data);
    }
}
