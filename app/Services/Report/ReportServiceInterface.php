<?php

namespace App\Report;

use App\User;

interface ReportServiceInterface
{
    public function getReportDataByPeriod(User $user, string $startDate, string $endDate): ReportData;
}