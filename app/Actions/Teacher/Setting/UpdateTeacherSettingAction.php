<?php

namespace App\Actions\Teacher\Setting;

use App\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UpdateTeacherSettingAction
{
    /**
     * Update teacher profile setting
     */
    public function execute(Teacher $teacher, array $data, ?UploadedFile $photoFile): Teacher
    {
        $teacher->name = $data['name'];
        $teacher->username = $data['username'];

        if (! empty($data['new_password'])) {
            $teacher->password = Hash::make($data['new_password']);
        }

        if ($photoFile) {
            if ($teacher->photo_url && file_exists(public_path($teacher->photo_url))) {
                unlink(public_path($teacher->photo_url));
            }

            $filename = time().'_'.$photoFile->getClientOriginalName();
            $photoFile->move(public_path('img/guru'), $filename);

            $teacher->photo_url = 'img/guru/'.$filename;
        }

        $teacher->save();

        return $teacher;
    }
}
