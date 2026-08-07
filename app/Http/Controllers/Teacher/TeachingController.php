<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\Teacher\Teaching\DeleteAssignmentAction;
use App\Actions\Teacher\Teaching\DeleteMaterialAction;
use App\Actions\Teacher\Teaching\GetCourseDetailsAction;
use App\Actions\Teacher\Teaching\GetTeacherSchedulesAction;
use App\Actions\Teacher\Teaching\GradeSubmissionAction;
use App\Actions\Teacher\Teaching\StoreAssignmentAction;
use App\Actions\Teacher\Teaching\StoreMaterialAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\GradeSubmissionRequest;
use App\Http\Requests\Teacher\StoreAssignmentRequest;
use App\Http\Requests\Teacher\StoreMaterialRequest;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class TeachingController extends Controller
{
    public function index(GetTeacherSchedulesAction $action)
    {
        $teacherId = Auth::guard('teacher')->id();
        $data = $action->execute($teacherId);

        if (isset($data['error'])) {
            return back()->with('error', $data['error']);
        }

        return view('teacher.lms.index', $data);
    }

    public function show($schedule_id, GetCourseDetailsAction $action)
    {
        $data = $action->execute($schedule_id, Auth::guard('teacher')->id());

        return view('teacher.lms.course', $data);
    }

    public function storeMaterial(StoreMaterialRequest $request, StoreMaterialAction $action)
    {
        $action->execute($request->validated(), $request->file('file'));

        return back()->with('success', 'Materi berhasil dibagikan!');
    }

    public function destroyMaterial($id, DeleteMaterialAction $action)
    {
        $action->execute($id);

        return back()->with('success', 'Materi dihapus.');
    }

    public function storeAssignment(StoreAssignmentRequest $request, StoreAssignmentAction $action)
    {
        $action->execute(
            $request->validated(),
            Auth::guard('teacher')->id(),
            $request->file('file')
        );

        return back()->with('success', 'Tugas berhasil dipublish!');
    }

    public function destroyAssignment($id, DeleteAssignmentAction $action)
    {
        $action->execute($id);

        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function viewSubmissions($assignment_id)
    {
        $assignment = Assignment::with(['submissions.student'])->findOrFail($assignment_id);

        return view('teacher.lms.submissions', compact('assignment'));
    }

    public function gradeSubmission(GradeSubmissionRequest $request, $submission_id, GradeSubmissionAction $action)
    {
        $data = $request->validated();
        $data['feedback'] = $request->feedback; // feedback is optional and not validated strictly

        $action->execute($submission_id, $data);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
