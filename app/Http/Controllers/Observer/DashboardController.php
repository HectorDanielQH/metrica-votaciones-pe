<?php

namespace App\Http\Controllers\Observer;

use App\Http\Controllers\Controller;
use App\Services\ElectionReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ElectionReportService $reportService
    ) {
    }

    public function index(Request $request): View
    {
        return view('observer.dashboard', $this->reportService->buildActiveSurveyReport(
            $request->only(['surveyor_id', 'respondent_type', 'date_from', 'date_to'])
        ));
    }
}
