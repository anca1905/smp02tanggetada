<?php

namespace App\Actions\Ppdb;

use App\Models\Setting;

class TogglePpdbStatusAction
{
    /**
     * Toggle status buka/tutup PPDB pada konfigurasi setting.
     *
     * @return string Value setting yang baru
     */
    public function execute(): string
    {
        $current = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';
        $new = $current === '1' ? '0' : '1';

        Setting::updateOrCreate(
            ['key' => 'buka_ppdb'],
            ['value' => $new]
        );

        return $new;
    }
}
