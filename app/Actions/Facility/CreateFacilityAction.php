<?php

namespace App\Actions\Facility;

use App\Models\Facility;
use Illuminate\Http\UploadedFile;

class CreateFacilityAction
{
    /**
     * Menyimpan fasilitas baru ke database
     */
    public function execute(array $data, ?UploadedFile $image = null): Facility
    {
        $image = $image ?? ($data['image_path'] ?? null);

        if ($image instanceof UploadedFile) {
            $filename = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('img/facility'), $filename);
            $data['image_path'] = 'img/facility/'.$filename;
        } else {
            unset($data['image_path']);
        }

        return Facility::create($data);
    }
}
