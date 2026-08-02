<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('classroom');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhereHas('classroom', function($qc) use ($search) {
                      $qc->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('kelas_filter') && $request->kelas_filter != '') {
            $query->where('classroom_id', $request->kelas_filter);
        }

        $students = $query->orderBy('student_name', 'asc')->paginate(10);
        $classrooms = Classroom::all();

        return view('tu.student_data', compact('students', 'classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name'    => 'required|string|max:100',
            'nis'           => 'required|unique:students,nis|max:20',
            'gender' => 'required|in:M,F',
            'classroom_id'  => 'required|exists:classrooms,id',
            'phone_number'         => 'nullable|string|max:20',
            'student_status'  => 'required|in:Active,Graduated,Inactive',
            'parent_name'     => 'nullable|string|max:100',
            'parent_phone'    => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->nis); // Default password as NIS
        $data['parent_password'] = bcrypt('ortu' . $request->nis); // Default parent password
        Student::create($data);

        return back()->with('success', 'Student berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $student = Student::where('nis', $id)->firstOrFail();

        $request->validate([
            'student_name'    => 'required|string|max:100',
            'nis'           => ['required', Rule::unique('students', 'nis')->ignore($student->nis, 'nis')],
            'gender' => 'required|in:M,F',
            'classroom_id'  => 'required|exists:classrooms,id',
            'student_status'  => 'required',
            'phone_number'    => 'nullable|string|max:20',
            'parent_name'     => 'nullable|string|max:100',
            'parent_phone'    => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        
        // Generate password orang tua jika sebelumnya masih kosong (kasus data lama)
        if (empty($student->parent_password)) {
            $data['parent_password'] = bcrypt('ortu' . $request->nis);
        }

        $student->update($data);

        return back()->with('success', 'Data Student berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $Student = Student::where('nis', $id)->firstOrFail();
        $Student->delete();

        return back()->with('success', 'Data Student berhasil dihapus!');
    }
}
