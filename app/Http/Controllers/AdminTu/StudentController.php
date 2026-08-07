<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Student\CreateStudentAction;
use App\Actions\Student\DeleteStudentAction;
use App\Actions\Student\GetStudentsAction;
use App\Actions\Student\UpdateStudentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, GetStudentsAction $action)
    {
        $students = $action->execute(
            $request->input('search'),
            $request->input('kelas_filter'),
            10,
        );
        $classrooms = Classroom::all();

        return view('tu.student_data', compact('students', 'classrooms'));
    }

    /**
     * Menyimpan data siswa baru
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(
        StoreStudentRequest $request,
        CreateStudentAction $action,
    ) {
        $action->execute($request->validated());

        return back()->with('success', 'Student berhasil ditambahkan!');
    }

    /**
     * Mengupdate data siswa yang sudah ada
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(
        UpdateStudentRequest $request,
        UpdateStudentAction $action,
        Student $student,
    ) {
        $action->execute($request->validated(), $student);

        return back()->with('success', 'Data Student berhasil diperbarui!');
    }

    /**
     * Menghapus data siswa yang sudah ada
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Student $student, DeleteStudentAction $action)
    {
        $action->execute($student);

        return back()->with('success', 'Data Student berhasil dihapus!');
    }
}
