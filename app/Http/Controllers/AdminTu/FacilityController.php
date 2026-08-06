<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Facility\CreateFacilityAction;
use App\Actions\Facility\DeleteFacilityAction;
use App\Actions\Facility\GetFacilitiesAction;
use App\Actions\Facility\UpdateFacilityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Facility\StoreFacilityRequest;
use App\Http\Requests\Facility\UpdateFacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Menampilkan daftar fasilitas sekolah
     */
    public function index(GetFacilitiesAction $action): View
    {
        $fasilitasList = $action->execute();

        return view('tu.facilities.index', compact('fasilitasList'));
    }

    /**
     * Menyimpan data fasilitas baru
     */
    public function store(
        StoreFacilityRequest $request,
        CreateFacilityAction $action,
    ): RedirectResponse {
        $action->execute($request->validated(), $request->file('image_path'));

        return back()->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    /**
     * Memperbarui data fasilitas yang sudah ada
     */
    public function update(
        UpdateFacilityRequest $request,
        Facility $facility,
        UpdateFacilityAction $action,
    ): RedirectResponse {
        $action->execute(
            $request->validated(),
            $facility,
            $request->file('image_path'),
        );

        return back()->with('success', 'Fasilitas berhasil diperbarui!');
    }

    /**
     * Menghapus data fasilitas dari database
     */
    public function destroy(
        Facility $facility,
        DeleteFacilityAction $action,
    ): RedirectResponse {
        $action->execute($facility);

        return back()->with('success', 'Data fasilitas berhasil dihapus!');
    }
}
