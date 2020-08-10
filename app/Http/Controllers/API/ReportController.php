<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReportRequest;
use App\Report\ReportServiceInterface;
use App\Http\Resources\ReportResource;

class ReportController extends Controller
{
    protected ReportServiceInterface $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;
    }

    public function makeReport(ReportRequest $request)
    {
        return new ReportResource(
            $this->reportService->getReportDataByPeriod(
                $request->user(),
                $request->input('startDate'),
                $request->input('endDate')
            )
        );
    }
}
