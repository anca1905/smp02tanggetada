<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Student\GetAnnouncementsAction;
use App\Actions\Api\Student\GetStudentAssignmentsAction;
use App\Actions\Api\Student\GetStudentAttendancesAction;
use App\Actions\Api\Student\GetStudentDashboardAction;
use App\Actions\Api\Student\GetStudentGradesAction;
use App\Actions\Api\Student\GetStudentMaterialsAction;
use App\Actions\Api\Student\GetStudentSchedulesAction;
use App\Actions\Api\Student\SubmitAssignmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubmitAssignmentRequest;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    /**
     * Get the student's dashboard.
     */
    public function dashboard(
        Request $request,
        GetStudentDashboardAction $action,
    ) {
        return response()->json($action->execute($request->user()));
    }

    /**
     * Get the student's schedules.
     */
    public function schedules(
        Request $request,
        GetStudentSchedulesAction $action,
    ) {
        return response()->json($action->execute($request->user()));
    }

    /**
     * Get the student's assignments.
     */
    public function assignments(
        Request $request,
        GetStudentAssignmentsAction $action,
    ) {
        return response()->json($action->execute($request->user()));
    }

    /**
     * Get the student's materials.
     */
    public function materials(
        Request $request,
        GetStudentMaterialsAction $action,
    ) {
        return response()->json($action->execute($request->user()));
    }

    /**
     * Submit an assignment.
     */
    public function submitAssignment(
        SubmitAssignmentRequest $request,
        $id,
        SubmitAssignmentAction $action,
    ) {
        $result = $action->execute($request, $request->user(), $id);
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }

    /**
     * Get the student's attendances.
     */
    public function attendances(
        Request $request,
        GetStudentAttendancesAction $action,
    ) {
        return response()->json($action->execute($request->user()));
    }

    /**
     * Get the student's grades.
     */
    public function grades(Request $request, GetStudentGradesAction $action)
    {
        return response()->json($action->execute($request->user()));
    }

    /**
     * Get the student's announcements.
     */
    public function announcements(GetAnnouncementsAction $action)
    {
        return response()->json($action->execute());
    }
}
