<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun ajaran aktif untuk default filter
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Filter berdasarkan request user atau default ke tahun aktif
        $selectedYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);

        $classrooms = Classroom::with(['academicYear', 'teacher', 'students'])
            ->when($selectedYearId, function ($query) use ($selectedYearId) {
                return $query->where('academic_year_id', $selectedYearId);
            })
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $years = AcademicYear::orderBy('name', 'desc')->get();
        // Ambil guru yang belum jadi wali kelas (opsional, atau ambil semua guru)
        $teachers = Teacher::orderBy('name')->get();

        return view('tu.classrooms.index', compact('classrooms', 'years', 'teachers', 'selectedYearId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'level' => 'required|string', // 10, 11, 12
            'academic_year_id' => 'required|exists:academic_years,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        Classroom::create($request->all());

        return back()->with('success', 'Kelas berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'level' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $classroom = Classroom::findOrFail($id);
        $classroom->update($request->all());

        return back()->with('success', 'Data kelas diperbarui!');
    }

    public function destroy($id)
    {
        Classroom::destroy($id);
        return back()->with('success', 'Kelas dihapus!');
    }
}
