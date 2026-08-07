<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AttemptWebLoginAction;
use App\Actions\Auth\WebLogoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\WebLoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showLoginAdminTu()
    {
        return view('auth.login-admin-tu');
    }

    public function showLoginPegawai()
    {
        return view('auth.login-pegawai');
    }

    public function showLoginKepsek()
    {
        return view('auth.login-kepala-sekolah');
    }

    public function login(WebLoginRequest $request, AttemptWebLoginAction $action)
    {
        $redirectRoute = $action->execute(
            $request->username,
            $request->password,
            $request->role_type
        );

        if ($redirectRoute) {
            return redirect()->route($redirectRoute);
        }

        return back()->withErrors([
            'username' => 'Login gagal. Periksa kembali Username/NIS dan Password Anda.',
        ])->onlyInput('username');
    }

    public function logout(Request $request, WebLogoutAction $action)
    {
        $action->execute();

        return redirect()->route('login');
    }
}
