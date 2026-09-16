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
        $ppdbOpen = Setting::where('key', 'ppdb_open')->value('value');
        if ($ppdbOpen !== null && in_array(strtolower((string) $ppdbOpen), ['0', 'false', 'off', 'no'], true)) {
            return false;
        }

        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value');
        if ($bukaPpdb !== null && in_array(strtolower((string) $bukaPpdb), ['0', 'false', 'off', 'no'], true)) {
            return false;
        }

        return true;
    }
}
