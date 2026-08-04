<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Subject\CreateSubjectAction;
use App\Actions\Subject\DeleteSubjectAction;
use App\Actions\Subject\UpdateSubjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy("code")->get();

        // Pastikan kamu sudah buat view di: resources/views/tu/subjects/index.blade.php
        return view("tu.subjects.index", compact("subjects"));
    }

    // Menyimpan Mapel Baru
    public function store(
        StoreSubjectRequest $request,
        CreateSubjectAction $action,
    ) {
        $action->execute($request->validated());
        return back()->with("success", "Mata pelajaran berhasil ditambahkan");
    }

    // Update Mapel
    public function update(
        UpdateSubjectRequest $request,
        Subject $subject,
        UpdateSubjectAction $action,
    ) {
        $action->execute($request->validated(), $subject);
        return back()->with("success", "Data diperbarui");
    }

    // Hapus Mapel
    public function destroy(Subject $subject, DeleteSubjectAction $action)
    {
        $action->execute($subject);

        return back()->with("success", "Data dihapus");
    }
}
