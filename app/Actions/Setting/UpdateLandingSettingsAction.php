<?php

namespace App\Actions\Setting;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class UpdateLandingSettingsAction
{
    /**
     * Memperbarui pengaturan website/landing page dan menangani upload gambar
     *
     * @param  array  $textData  Data pengaturan berupa teks
     * @param  array  $files  Data pengaturan berupa file upload
     */
    public function execute(array $textData, array $files): void
    {
        // 1. Simpan pengaturan teks
        foreach ($textData as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 2. Daftar key untuk gambar yang mungkin diupload
        $imageKeys = ['hero_bg', 'struktur_img', 'school_logo', 'history_image'];

        // 3. Proses upload setiap gambar jika ada
        foreach ($imageKeys as $key) {
            if (isset($files[$key]) && $files[$key]->isValid()) {
                // Hapus gambar lama jika ada
                $oldImage = Setting::where('key', $key)->value('value');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Simpan gambar baru
                $path = $files[$key]->store('settings', 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }
    }
}
