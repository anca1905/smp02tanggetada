<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class AttemptWebLoginAction
{
    /**
     * Executes the web login attempt.
     *
     * @param  string  $guard  Guard name (operator or teacher)
     * @return string|false Returns route name on success, false on failure
     */
    public function execute(string $username, string $password, string $guard): string|false
    {
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        if (Auth::guard($guard)->attempt($credentials)) {
            session()->regenerate();

            if ($guard === 'operator') {
                $operator = Auth::guard('operator')->user();
                if (in_array($operator->role_operator, ['Kepala Sekolah', 'principal'])) {
                    return 'principal.dashboard';
                }

                return 'tu.dashboard';
            } elseif ($guard === 'teacher') {
                return 'teacher.dashboard';
            }
        }

        return false;
    }
}
