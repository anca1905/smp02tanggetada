<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class GraduationController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        $kelas = (int) filter_var($teacher->homeroom_class, FILTER_SANITIZE_NUMBER_INT);

        $students = Student::where('class', $kelas)
            ->where('student_status', 'Active')
            ->orderBy('student_name', 'asc')
            ->get();

        return view('teacher.graduation', compact('students', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|array',
        ]);

        foreach ($request->status as $nis => $status) {
            $student = Student::where('nis', $nis)->first();
            if ($student) {
                $newStatus = ($status == 'Lulus') ? 'Graduated' : 'Active';

                $student->update(['student_status' => $newStatus]);
            }
        }

        return back()->with('success', 'Data kelulusan berhasil disimpan!');
    }
}