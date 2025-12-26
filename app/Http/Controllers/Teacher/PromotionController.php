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
        $kelas = (int) filter_var($teacher->homeroom_class, FILTER_SANITIZE_NUMBER_INT);

        $students = Student::where('class', $kelas)
            ->where('student_status', 'Active')
            ->orderBy('student_name', 'asc')
            ->get();

        return view('teacher.promotion', compact('students', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|array', 
            'next_class' => 'required|string',
        ]);

        if (empty($request->next_class)) {
            return back()->withErrors(['next_class' => 'Nama kelas tujuan wajib diisi!']);
        }

        foreach ($request->action as $nis => $action) {
            $student = Student::where('nis', $nis)->first();

            if ($student) {
                if ($action == 'Naik') {
                    $student->update([
                        'class' => $request->next_class
                    ]);
                }
            }
        }

        return back()->with('success', 'Data kenaikan kelas berhasil diproses!');
    }
}