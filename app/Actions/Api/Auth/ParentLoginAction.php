<?php

namespace App\Actions\Api\Auth;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ParentLoginAction
{
    /**
     * Authenticate parent and create token.
     *
     * @throws ValidationException
     */
    public function execute(string $nis, string $password): array
    {
        $student = Student::where('nis', $nis)->first();

        if (! $student || ! $student->parent_password || ! Hash::check($password, $student->parent_password)) {
            return [
                'success' => false,
                'message' => 'NIS atau Password Orang Tua salah',
                'status_code' => 401,
            ];
        }

        // Create token
        $token = $student->createToken('parent_mobile_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Login Orang Tua berhasil',
            'data' => [
                'student' => $student,
                'token' => $token,
                'is_parent' => true,
            ],
            'status_code' => 200,
        ];
    }
}
