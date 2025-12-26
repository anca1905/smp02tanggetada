<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        return view('teacher.setting', compact('teacher'));
    }

    public function update(Request $request)
    {
          /** @var \App\Models\Teacher $teacher */
        $teacher = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:100',
            'username'         => ['required', Rule::unique('teachers', 'username')->ignore($teacher->teacher_id, 'teacher_id')],
            'photo_url'        => 'nullable|image|max:2048',
            'current_password' => 'required_with:new_password,username',
            'new_password'     => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $teacher->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.']);
            }
        }

        $teacher->name = $request->name;
        $teacher->username = $request->username;

        if ($request->filled('new_password')) {
            $teacher->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('photo_url')) {
            if ($teacher->photo_url && file_exists(public_path($teacher->photo_url))) {
                unlink(public_path($teacher->photo_url));
            }

            $file = $request->file('photo_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/guru'), $filename);

            $teacher->photo_url = 'img/guru/' . $filename;
        }

        $teacher->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}