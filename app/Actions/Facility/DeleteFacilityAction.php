<?php

namespace App\Actions\Facility;

use App\Models\Facility;

class DeleteFacilityAction
{
    /**
     * Menghapus fasilitas dan file gambar terkait di penyedia penyimpanan
     */
    public function execute(Facility $facility): ?bool
    {
        if (
            $facility->image_path &&
            file_exists(public_path($facility->image_path))
        ) {
            unlink(public_path($facility->image_path));
        }

        return $facility->delete();
    }
}
