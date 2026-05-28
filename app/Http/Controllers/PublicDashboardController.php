<?php

namespace App\Http\Controllers;

use App\Services\ElectionReportService;
use Illuminate\View\View;

class PublicDashboardController extends Controller
{
    public function __construct(
        private readonly ElectionReportService $reportService
    ) {
    }

    public function index(): View
    {
        return view('public.dashboard', $this->reportService->buildActiveSurveyReport());
    }
}
