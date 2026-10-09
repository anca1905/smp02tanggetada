<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Subject\CreateSubjectAction;
use App\Actions\Subject\DeleteSubjectAction;
use App\Actions\Subject\UpdateSubjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::orderBy('code')->get();

        return view('tu.subjects.index', compact('subjects'));
    }

    // Menyimpan Mapel Baru
    public function store(
        StoreSubjectRequest $request,
        CreateSubjectAction $action,
    ): RedirectResponse {
        $action->execute($request->validated(), $request->file('cover'));

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    // Update Mapel
    public function update(
        UpdateSubjectRequest $request,
        Subject $subject,
        UpdateSubjectAction $action,
    ): RedirectResponse {
        $action->execute($request->validated(), $subject, $request->file('cover'));

        return back()->with('success', 'Data diperbarui');
    }

    // Hapus Mapel
    public function destroy(Subject $subject, DeleteSubjectAction $action): RedirectResponse
    {
        $action->execute($subject);

        return back()->with('success', 'Data dihapus');
    }
}
