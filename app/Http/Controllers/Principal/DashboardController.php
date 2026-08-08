<?php

namespace App\Http\Controllers\Principal;

use App\Actions\Principal\GetPrincipalDashboardAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $operator = Auth::guard('operator')->user();
            if ($operator && ! in_array($operator->role_operator, ['Kepala Sekolah', 'principal'])) {
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }

    public function index(GetPrincipalDashboardAction $action)
    {
        $data = $action->execute();

        return view('principal.dashboard', $data);
    }
}
