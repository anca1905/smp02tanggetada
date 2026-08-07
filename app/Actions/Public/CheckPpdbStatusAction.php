<?php

namespace App\Actions\Public;

use App\Models\Setting;

class CheckPpdbStatusAction
{
    /**
     * Check if PPDB is currently open.
     */
    public function execute(): bool
    {
        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';

        return $bukaPpdb === '1';
    }
}
