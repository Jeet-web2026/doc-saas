<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardManagementController extends Controller
{
    public function __construct(private readonly DashboardManagementService $service) {}

    public function index(Request $request)
    {
        $orgaUin = Session::get('selected_orga_uin');

        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        return response()->json(
            $this->service->getCalendarData($orgaUin, $year, $month)
        );
    }
}
