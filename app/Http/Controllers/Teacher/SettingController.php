<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\Teacher\Setting\UpdateTeacherSettingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdateTeacherSettingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $teacher = Auth::user()->load('classroom');

        return view('teacher.setting', compact('teacher'));
    }

    public function update(UpdateTeacherSettingRequest $request, UpdateTeacherSettingAction $action): RedirectResponse
    {
        $action->execute(
            Auth::user(),
            $request->validated(),
            $request->file('photo_url')
        );

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
