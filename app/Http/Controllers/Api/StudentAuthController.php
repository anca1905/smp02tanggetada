<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::where('nis', $request->nis)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIS atau Password salah'
            ], 401);
        }

        // Create token
        $token = $student->createToken('student_mobile_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'student' => $student,
                'token' => $token
            ]
        ], 200);
    }

    public function parentLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::where('nis', $request->nis)->first();

        if (!$student || !$student->parent_password || !Hash::check($request->password, $student->parent_password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIS atau Password Orang Tua salah'
            ], 401);
        }

        // Create token
        $token = $student->createToken('parent_mobile_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Orang Tua berhasil',
            'data' => [
                'student' => $student,
                'token' => $token,
                'is_parent' => true
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
