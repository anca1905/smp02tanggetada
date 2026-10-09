<?php

namespace App\Actions\Subject;

use App\Models\Subject;
use Illuminate\Http\UploadedFile;

class CreateSubjectAction
{
    /**
     * Membuat subject/mata pelajaran baru
     */
    public function execute(array $data, ?UploadedFile $cover = null): Subject
    {
        if ($cover) {
            $data['cover'] = $cover->store('subjects', 'public');
        }

        return Subject::create($data);
    }
}
