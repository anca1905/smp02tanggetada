<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class DeleteMaterialAction
{
    /**
     * Delete course material
     */
    public function execute(int $id): void
    {
        $material = Material::findOrFail($id);

        if ($material->type === 'pdf' && $material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
    }
}
