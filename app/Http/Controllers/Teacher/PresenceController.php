<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StorePresenceRequest;
use App\Actions\Teacher\Presence\SearchTeacherForPresenceAction;
use App\Actions\Teacher\Presence\StoreTeacherPresenceAction;

class PresenceController extends Controller
{
    public function index()
    {
        return view('presence');
    }

    public function search(Request $request, SearchTeacherForPresenceAction $action)
    {
        $teachers = $action->execute($request->get('query'));
        return response()->json($teachers);
    }

    public function store(StorePresenceRequest $request, StoreTeacherPresenceAction $action)
    {
        $result = $action->execute($request->validated());
        return response()->json($result);
    }
}
