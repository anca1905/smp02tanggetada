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
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Mengecek apakah ada foto yang diunggah dan memindahkan jika ada
        if ($photoFile) {
            if ($teacher->photo_url) {
                if (str_starts_with($teacher->photo_url, 'img/') && file_exists(public_path($teacher->photo_url))) {
                    unlink(public_path($teacher->photo_url));
                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($teacher->photo_url)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->photo_url);
                }
            }

            $path = $photoFile->store('teachers/photos', 'public');
            $data['photo_url'] = $path;
        }

        $teacher->update($data);

        return $teacher;
    }
}
