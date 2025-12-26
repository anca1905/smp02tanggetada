<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Student::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('student_name', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('class', 'like', "%{$search}%");
        }

        if ($request->has('kelas_filter') && $request->kelas_filter != '') {
            $query->where('class', $request->kelas_filter);
        }

        $students = $query->orderBy('class', 'asc')
            ->orderBy('student_name', 'asc')
            ->paginate(10);

        return view('tu.student_data', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name'    => 'required|string|max:100',
            'nis'           => 'required|unique:students,nis|max:20',
            'gender' => 'required|in:M,F',
            'class'         => 'required|string',
            'phone_number'         => 'nullable|string|max:20',
            'student_status'  => 'required|in:Active,Graduated,Inactive',
        ]);

        Student::create($request->all());

        return back()->with('success', 'Student berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $student = Student::where('nis', $id)->firstOrFail();

        $request->validate([
            'student_name'    => 'required|string|max:100',
            'nis'           => ['required', Rule::unique('students', 'nis')->ignore($student->nis, 'nis')],
            'gender' => 'required|in:M,F',
            'class'         => 'required|string',
            'student_status'  => 'required',
        ]);

        $student->update($request->all());

        return back()->with('success', 'Data Student berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $Student = Student::where('nis', $id)->firstOrFail();
        $Student->delete();

        return back()->with('success', 'Data Student berhasil dihapus!');
    }
}
