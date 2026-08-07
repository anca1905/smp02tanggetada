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
        if (Auth::guard('operator')->check()) {
            Auth::guard('operator')->logout();
        } elseif (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
        } elseif (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }

        session()->invalidate();
        session()->regenerateToken();
    }
}
