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
        
        $query = Teacher::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('ID', 'like', "%{$search}%");
            });
        }

        $paginatedTeachers = $query->orderBy('name', 'asc')->paginate(10);

        return view('tu.teacher_data', compact('paginatedTeachers', 'query'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'ID'           => 'required|unique:teachers,ID',
            'subject' => 'required|string',
            'homeroom_class'    => 'nullable|string',
            'status'        => 'required|in:Aktif,Tidak Aktif',
            'username'      => 'required|unique:teachers,username',
            'password'      => 'required|min:6',
            'photo_url'   => 'nullable|image|max:2048'
        ]);

        // $data = null;
        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/teacher'), $filename);
            $data['photo_url'] = 'img/teacher/' . $filename;
        }

        Teacher::create($data);

        return back()->with('success', 'Guru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'ID'           => ['required', Rule::unique('teachers', 'ID')->ignore($teacher->ID, 'ID')],
            'username'      => ['required', Rule::unique('teachers', 'username')->ignore($teacher->username, 'username')],
            'password'      => 'nullable|min:6',
        ]);

        $dataToUpdate = [
            'name'     => $request->name,
            'gender' => $request->gender,
            'ID'            => $request->ID,
            'subject' => $request->subject,
            'homeroom_class'    => $request->homeroom_class,
            'status'        => $request->status,
            'username'      => $request->username,
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
            $file->move(public_path('img/teacher$teacher'), $filename);
            $dataToUpdate['photo_url'] = 'img/teacher/' . $filename;
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
