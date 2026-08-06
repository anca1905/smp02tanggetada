<?php

namespace App\Actions\Facility;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Collection;

class GetFacilitiesAction
{
    /**
     * Mengambil seluruh data fasilitas dari database
     */
    public function execute(): Collection
    {
        return Facility::all();
    }
}
