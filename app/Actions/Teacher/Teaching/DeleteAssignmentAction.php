<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\Assignment;
use Illuminate\Support\Facades\Storage;

class DeleteAssignmentAction
{
    /**
     * Delete assignment
     *
     * @param int $id
     * @return void
     */
    public function execute(int $id): void
    {
        $assignment = Assignment::findOrFail($id);
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }
        $assignment->delete();
    }
}
