<?php

namespace App\Actions\Teacher\Teaching;

use App\Models\Material;
use Illuminate\Http\UploadedFile;

class StoreMaterialAction
{
    /**
     * Store course material
     *
     * @param array $data
     * @param UploadedFile|null $file
     * @return Material
     */
    public function execute(array $data, ?UploadedFile $file): Material
    {
        $filePath = null;
        $fileName = null;

        if ($data['type'] === 'pdf' && $file) {
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('materials', 'public');
        } else {
            $filePath = $data['url'] ?? null;
        }

        return Material::create([
            'schedule_id' => $data['schedule_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);
    }
}
