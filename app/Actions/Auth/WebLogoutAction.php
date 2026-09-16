<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class WebLogoutAction
{
    /**
     * Logs out the currently authenticated user from web guards and invalidates session.
     */
    public function execute(): void
    {
        foreach (['operator', 'teacher', 'student', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        session()->invalidate();
        session()->regenerateToken();
    }
}
