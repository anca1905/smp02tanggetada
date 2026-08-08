<?php

namespace App\Http\Controllers\Principal;

use App\Actions\Principal\GetPrincipalDashboardAction;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(GetPrincipalDashboardAction $action)
    {
        $data = $action->execute();

        return view('principal.dashboard', $data);
    }
}
