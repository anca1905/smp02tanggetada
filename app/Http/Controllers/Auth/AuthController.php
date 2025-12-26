<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role_type' => 'required|in:operator,teacher',
        ]);

        $credentials = $request->only('username', 'password');

        $guard = ($request->role_type === 'operator') ? 'operator' : 'teacher';

        if (Auth::guard($guard)->attempt($credentials)) {
            $request->session()->regenerate();

            if ($guard === 'operator') {
                return redirect()->route('tu.dashboard');
            } else {
                return redirect()->route('teacher.dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('operator')->check()) {
            Auth::guard('operator')->logout();
        } elseif (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
