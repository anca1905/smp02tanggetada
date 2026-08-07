<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\Teacher\Promotion\GetPromotionDataAction;
use App\Actions\Teacher\Promotion\ProcessStudentPromotionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ProcessPromotionRequest;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index(GetPromotionDataAction $action)
    {
        $teacher = Auth::user();
        $data = $action->execute($teacher);

        return view('teacher.promotion', $data);
    }

    public function store(ProcessPromotionRequest $request, ProcessStudentPromotionAction $action)
    {
        $action->execute($request->validated());

        return back()->with('success', 'Data kenaikan kelas berhasil diproses!');
    }
}
