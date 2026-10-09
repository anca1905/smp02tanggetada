<?php

namespace App\Actions\Subject;

use App\Models\Subject;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateSubjectAction
{
    /**
     * Mengupdate data subject/mata pelajaran
     */
    public function execute(array $data, Subject $subject, ?UploadedFile $cover = null): Subject
    {
        if ($cover) {
            if ($subject->cover && Storage::disk('public')->exists($subject->cover)) {
                Storage::disk('public')->delete($subject->cover);
            }
            $data['cover'] = $cover->store('subjects', 'public');
        }

        $subject->update($data);

        return $subject;
    }
}
