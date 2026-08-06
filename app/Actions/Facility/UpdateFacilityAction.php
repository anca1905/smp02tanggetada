<?php

namespace App\Actions\Facility;

use App\Models\Facility;
use Illuminate\Http\UploadedFile;

class UpdateFacilityAction
{
    /**
     * Memperbarui data fasilitas dan menangani penggantian gambar bila ada
     */
    public function execute(
        array $data,
        Facility $facility,
        ?UploadedFile $image = null
    ): Facility {
        $image = $image ?? ($data['image_path'] ?? null);

        if ($image instanceof UploadedFile) {
            if (
                $facility->image_path &&
                file_exists(public_path($facility->image_path))
            ) {
                unlink(public_path($facility->image_path));
            }

            $filename = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('img/facility'), $filename);
            $data['image_path'] = 'img/facility/'.$filename;
        } else {
            unset($data['image_path']);
        }

        $facility->update($data);

        return $facility;
    }
}
