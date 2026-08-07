<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Teacher\CreateTeacherAction;
use App\Actions\Teacher\DeleteTeacherAction;
use App\Actions\Teacher\GetTeachersAction;
use App\Actions\Teacher\UpdateTeacherAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Menampilkan daftar guru dengan pagination dan filter pencarian
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, GetTeachersAction $action)
    {
        $paginatedTeachers = $action->execute($request->input('search'), 10);

        return view('tu.teacher_data', compact('paginatedTeachers'));
    }

    /**
     * Menyimpan data guru baru
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(
        StoreTeacherRequest $request,
        CreateTeacherAction $action,
    ) {
        $action->execute($request->validated(), $request->file('photo_url'));

        return back()->with('success', 'Guru berhasil ditambahkan!');
    }

    /**
     * Mengupdate data guru yang sudah ada
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(
        UpdateTeacherRequest $request,
        Teacher $teacher,
        UpdateTeacherAction $action,
    ) {
        $action->execute(
            $request->validated(),
            $request->file('photo_url'),
            $teacher,
        );

        return back()->with('success', 'Data guru berhasil diperbarui!');
    }

    /**
     * Menghapus data guru yang sudah ada
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Teacher $teacher, DeleteTeacherAction $action)
    {
        $action->execute($teacher);

        return back()->with('success', 'Data guru berhasil dihapus!');
    }
}
