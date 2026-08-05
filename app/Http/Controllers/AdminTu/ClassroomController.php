<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Classroom\CreateClassroomAction;
use App\Actions\Classroom\DeleteClassroomAction;
use App\Actions\Classroom\GetClassroomsAction;
use App\Actions\Classroom\UpdateClassroomAction;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;

class ClassroomController extends Controller
{
    /**
     * Menampilkan daftar kelas
     *
     * @param Request $request
     * @param GetClassroomsAction $action
     */
    public function index(Request $request, GetClassroomsAction $action)
    {
        // Ambil tahun ajaran aktif untuk default filter
        $activeYear = AcademicYear::where("is_active", true)->first();

        // Filter berdasarkan request user atau default ke tahun aktif
        $selectedYearId = $request->input("academic_year_id", $activeYear?->id);

        $classrooms = $action->execute($selectedYearId);

        $years = AcademicYear::orderBy("name", "desc")->get();
        // Ambil guru yang belum jadi wali kelas (opsional, atau ambil semua guru)
        $teachers = Teacher::orderBy("name")->get();

        return view(
            "tu.classrooms.index",
            compact("classrooms", "years", "teachers", "selectedYearId"),
        );
    }

    /**
     * Membuat kelas baru
     *
     * @param StoreClassroomRequest $request
     * @param CreateClassroomAction $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(
        StoreClassroomRequest $request,
        CreateClassroomAction $action,
    ) {
        $action->execute($request->validated());
        return back()->with("success", "Kelas berhasil dibuat!");
    }

    /**
     * Update the specified classroom.
     *
     * @param UpdateClassroomRequest $request
     * @param Classroom $classroom
     * @param UpdateClassroomAction $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(
        UpdateClassroomRequest $request,
        Classroom $classroom,
        UpdateClassroomAction $action,
    ) {
        $action->execute($request->validated(), $classroom);

        return back()->with("success", "Data kelas diperbarui!");
    }

    /**
     * Hapus kelas dari database.
     *
     * @param Classroom $classroom
     * @param DeleteClassroomAction $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Classroom $classroom, DeleteClassroomAction $action)
    {
        $action->execute($classroom);
        return back()->with("success", "Kelas dihapus!");
    }
}
