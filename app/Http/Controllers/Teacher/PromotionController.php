<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class PromotionController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        $kelas = null;
        $students = collect();
        
        if ($teacher->classroom) {
            $kelas = $teacher->classroom->name;
            $students = Student::where('classroom_id', $teacher->classroom->id)
                ->where('student_status', 'Active')
                ->orderBy('student_name', 'asc')
                ->get();
        }

        // Get all classrooms for the next_class dropdown
        $allClassrooms = \App\Models\Classroom::orderBy('level')->orderBy('name')->get();

        return view('teacher.promotion', compact('students', 'kelas', 'allClassrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|array', 
            'next_classroom_id' => 'required|integer|exists:classrooms,id',
        ]);

        foreach ($request->action as $nis => $action) {
            $student = Student::where('nis', $nis)->first();

            if ($student) {
                if ($action == 'Naik') {
                    $student->update([
                        'classroom_id' => $request->next_classroom_id
                    ]);
                }
            }
        }

        return back()->with('success', 'Data kenaikan kelas berhasil diproses!');
    }
}