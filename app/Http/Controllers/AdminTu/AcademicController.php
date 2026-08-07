<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Academic\CreateAcademicYearAction;
use App\Actions\Academic\DeleteAcademicYearAction;
use App\Actions\Academic\GetAcademicYearsAction;
use App\Actions\Academic\SetActiveAcademicYearAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreAcademicRequest;
use App\Models\AcademicYear;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AcademicController extends Controller
{
    /**
     * Menampilkan daftar tahun akademik.
     */
    public function index(GetAcademicYearsAction $action): View
    {
        $years = $action->execute();

        return view('tu.academic.index', compact('years'));
    }

    /**
     * Menambahkan tahun akademik baru.
     */
    public function store(
        StoreAcademicRequest $request,
        CreateAcademicYearAction $action,
    ): RedirectResponse {
        $action->execute($request->validated());

        return back()->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    /**
     * Mengubah tahun ajaran yang aktif.
     */
    public function setActive(
        AcademicYear $academicYear,
        SetActiveAcademicYearAction $action,
    ): RedirectResponse {
        $action->execute($academicYear);

        return back()->with('success', 'Tahun ajaran aktif berhasil diubah!');
    }

    /**
     * Menghapus tahun ajaran.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(
        AcademicYear $academicYear,
        DeleteAcademicYearAction $action,
    ): RedirectResponse {
        $action->execute($academicYear);

        return back()->with('success', 'Data dihapus');
    }
}
