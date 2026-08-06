<?php

namespace App\Actions\Setting;

use App\Models\Setting;

class GetLandingSettingsAction
{
    /**
     * Mengambil seluruh pengaturan website/landing page dalam bentuk array asosiatif (key => value)
     */
    public function execute(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }
}
