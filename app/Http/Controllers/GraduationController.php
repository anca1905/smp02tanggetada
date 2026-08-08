<?php

namespace App\Http\Controllers;

use App\Actions\Teacher\Graduation\GetGraduationDataAction;
use App\Actions\Teacher\Graduation\ProcessGraduationAction;
use App\Http\Requests\Teacher\ProcessGraduationRequest;

class GraduationController extends Controller
{
    public function index(GetGraduationDataAction $action)
    {
        $data = $action->execute();

        return view('teacher.graduation', $data);
    }

    public function store(ProcessGraduationRequest $request, ProcessGraduationAction $action)
    {
        $action->execute($request->validated('status'));

        return back()->with('success', 'Data kelulusan berhasil disimpan!');
    }
}
