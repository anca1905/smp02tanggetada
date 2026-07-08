<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->photo_url);

        $query = Teacher::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $paginatedTeachers = $query->orderBy('name', 'asc')->paginate(10);

        return view('tu.teacher_data', compact('paginatedTeachers', 'query'));
    }

    public function store(Request $request)
    {
        // dd($request->photo_url);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
            'employee_id' => 'required|unique:teachers,employee_id',
            'phone' => 'required|string|max:20',
            'subject' => 'nullable|string',
            'homeroom_class' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'username' => 'required|unique:teachers,username',
            'password' => 'required|min:6',
            'photo_url' => 'nullable|image|max:2048'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // $data = null;
        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/teacher-photo'), $filename);
            $data['photo_url'] = 'img/teacher-photo/' . $filename;
        }

        Teacher::create($data);

        return back()->with('success', 'Guru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // dd($request->gender);
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'employee_id' => ['required', Rule::unique('teachers', 'employee_id')->ignore($teacher->id)],
            'phone' => 'required|string|max:20',
            'username' => ['required', Rule::unique('teachers', 'username')->ignore($teacher->id)],
            'password' => 'nullable|min:6',
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'gender' => $request->gender,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'homeroom_class' => $request->homeroom_class,
            'status' => $request->status,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo_url')) {
            if ($teacher->foto_url && file_exists(public_path($teacher->foto_url))) {
                unlink(public_path($teacher->foto_url));
            }

            $file = $request->file('photo_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/teacher-photo'), $filename);
            $dataToUpdate['photo_url'] = 'img/teacher-photo/' . $filename;
        }

        $teacher->update($dataToUpdate);

        return back()->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // dd($id);
        $teacher = Teacher::findOrFail($id);

        if ($teacher->photo_url && file_exists(public_path($teacher->photo_url))) {
            unlink(public_path($teacher->photo_url));
        }

        $teacher->delete();

        return back()->with('success', 'Data guru berhasil dihapus!');
    }
}
