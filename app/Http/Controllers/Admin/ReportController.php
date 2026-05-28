<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ElectionReportService;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private readonly ElectionReportService $reportService
    ) {
    }

    public function index(): View
    {
        return view('admin.reports.index', $this->reportService->buildActiveSurveyReport());
    }
}
