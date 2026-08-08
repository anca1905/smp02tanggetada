<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Auth\ApiLogoutAction;
use App\Actions\Api\Auth\ParentLoginAction;
use App\Actions\Api\Auth\StudentLoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StudentLoginRequest;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    /**
     * Login a student.
     */
    public function login(
        StudentLoginRequest $request,
        StudentLoginAction $action,
    ) {
        $result = $action->execute($request->nis, $request->password);
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }

    /**
     * Login a parent.
     */
    public function parentLogin(
        StudentLoginRequest $request,
        ParentLoginAction $action,
    ) {
        $result = $action->execute($request->nis, $request->password);
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }

    /**
     * Logout a user.
     */
    public function logout(Request $request, ApiLogoutAction $action)
    {
        return response()->json($action->execute($request), 200);
    }
}
