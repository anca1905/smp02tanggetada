<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Dashboard\GetAdminTuDashboardDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk Admin TU.
     */
    public function index(GetAdminTuDashboardDataAction $action): View
    {
        $dashboardData = $action->execute();

        return view('tu.index', $dashboardData);
    }
}
