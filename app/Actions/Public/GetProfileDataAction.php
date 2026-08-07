<?php

namespace App\Actions\Public;

use App\Models\Facility;

class GetProfileDataAction
{
    /**
     * Get all facility data.
     */
    public function execute(): array
    {
        $facility = Facility::all();

        return compact('facility');
    }
}
