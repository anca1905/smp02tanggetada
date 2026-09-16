<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? ['operator', 'teacher', 'student', 'web'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($guard === 'operator') {
                    return in_array($user->role_operator, ['Kepala Sekolah', 'principal'])
                        ? redirect()->route('principal.dashboard')
                        : redirect()->route('tu.dashboard');
                }
                if ($guard === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                }
                if ($guard === 'student') {
                    return redirect()->route('student.dashboard');
                }
                if ($guard === 'web') {
                    return redirect()->route('home');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
