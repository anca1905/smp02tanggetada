<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Setting\GetLandingSettingsAction;
use App\Actions\Setting\UpdateLandingSettingsAction;
use App\Actions\Setting\UpdateOperatorProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateLandingSettingsRequest;
use App\Http\Requests\Setting\UpdateOperatorProfileRequest;
use App\Models\Operator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SettingsController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil operator.
     */
    public function index(): View
    {
        $data = Operator::first();

        return view('tu.settings', compact('data'));
    }

    /**
     * Memperbarui profil operator.
     *
     * @param  int  $id
     */
    public function update(
        UpdateOperatorProfileRequest $request,
        $id, // Tetap gunakan $id karena modelnya memiliki primary key 'operator_id' dan implicit binding mungkin terkendala jika tidak di-set up dengan benar di model.
        UpdateOperatorProfileAction $action
    ): RedirectResponse {
        $data = $request->validated();
        $photo = $request->file('photo_url');

        $action->execute((int) $id, $data, $photo);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman pengaturan landing page/website.
     */
    public function index_landing(GetLandingSettingsAction $action): View
    {
        $settings = $action->execute();

        return view('tu.landing.settings', compact('settings'));
    }

    /**
     * Memperbarui pengaturan landing page/website.
     */
    public function update_landing(
        UpdateLandingSettingsRequest $request,
        UpdateLandingSettingsAction $action
    ): RedirectResponse {
        $request->validated(); // Jalankan validasi file (gambar)

        // Ambil data teks saja
        $textData = $request->except(['_token', '_method', 'hero_bg', 'school_logo', 'history_image', 'struktur_img']);

        // Ambil data file (gambar)
        $files = [
            'hero_bg' => $request->file('hero_bg'),
            'struktur_img' => $request->file('struktur_img'),
            'school_logo' => $request->file('school_logo'),
            'history_image' => $request->file('history_image'),
        ];

        $action->execute($textData, array_filter($files));

        return back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
