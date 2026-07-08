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
        // dd($request);
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role_type' => 'required|in:operator,teacher,student', // Tambahkan student
        ]);

        $guard = $request->role_type; // operator, teacher, atau student

        // Tentukan kredensial berdasarkan guard
        if ($guard === 'student') {
            // Jika siswa, field username di form dianggap sebagai 'nis'
            $credentials = [
                'nis' => $request->username,
                'password' => $request->password
            ];
        } else {
            $credentials = [
                'username' => $request->username,
                'password' => $request->password
            ];
        }

        if (Auth::guard($guard)->attempt($credentials)) {
            $request->session()->regenerate();

            if ($guard === 'operator') {
                return redirect()->route('tu.dashboard');
            } elseif ($guard === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif ($guard === 'student') {
                return redirect()->route('student.lms.index');
            }
        }

        return back()->withErrors([
            'username' => 'Login gagal. Periksa kembali Username/NIS dan Password Anda.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('operator')->check()) {
            Auth::guard('operator')->logout();
        } elseif (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
        } elseif (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
