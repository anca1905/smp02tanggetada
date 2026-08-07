<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Teacher\UpdateTeacherSettingRequest;
use App\Actions\Teacher\Setting\UpdateTeacherSettingAction;

class SettingController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        return view('teacher.setting', compact('teacher'));
    }

    public function update(UpdateTeacherSettingRequest $request, UpdateTeacherSettingAction $action)
    {
        $action->execute(
            Auth::user(),
            $request->validated(),
            $request->file('photo_url')
        );

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
