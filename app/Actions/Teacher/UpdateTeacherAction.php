<?php

namespace App\Actions\Teacher;

use App\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UpdateTeacherAction
{
    /**
     * Execute the action
     */
    public function execute(
        array $data,
        ?UploadedFile $photoFile,
        Teacher $teacher,
    ): Teacher {
        // Mengecek apakah password diubah dan di HASH jika ada
        if (!empty($data["password"])) {
            $data["password"] = Hash::make($data["password"]);
        } else {
            unset($data["password"]);
        }

        // Mengecek apakah ada foto yang diunggah dan memindahkan jika ada
        if ($photoFile) {
            if (
                $teacher->photo_url &&
                file_exists(public_path($teacher->photo_url))
            ) {
                unlink(public_path($teacher->photo_url));
            }

            $filename = time() . "_" . $photoFile->getClientOriginalName();
            $photoFile->move(public_path("img/teacher-photo"), $filename);
            $data["photo_url"] = "img/teacher-photo/" . $filename;
        }

        $teacher->update($data);
        return $teacher;
    }
}
