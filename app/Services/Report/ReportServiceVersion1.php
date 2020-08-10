<?php

namespace App\Report;

use App\User;
use App\Report\ReportData;
use App\Action\Exchange;
use Illuminate\Support\Facades\DB;
use App\Report\ReportServiceInterface;


class ReportServiceVersion1 implements ReportServiceInterface
{
    public function getReportDataByPeriod(User $user, string $startDate, string $endDate): ReportData
    {
        $totalGain = 
            $this->getIncomeGainByPeriod($user, $startDate, $endDate)
            + $this->getExchangeGainByPeriod($user, $startDate, $endDate)
            + $this->getForceExchangeGainByPeriod($user, $startDate, $endDate);
        $tax = $totalGain * $user->taxRate / 100;
        return new ReportData(
            max(0, $totalGain),
            max(0, $tax),
            $startDate,
            $endDate
        );
    }

    private function getIncomeGainByPeriod(User $user, string $startDate, string $endDate): float
    {
        return $user->incomes()
            ->join('exchange_rates', 'exchange_rates.id', '=', 'incomes.exchangeDataId')
            ->whereBetween('exchange_rates.date', [$startDate, $endDate])
            ->sum(DB::raw('exchange_rates.rate * incomes.amount'));
    }

    private function getExchangeGainByPeriod(User $user, string $startDate, string $endDate): float
    {
        return Exchange::whereHas('incomes', fn($query) => $query->where('userId', $user->id))
            ->join('exchange_segments', 'exchange_segments.exchangeId', '=', 'exchanges.id')
            ->join('incomes', 'exchange_segments.incomeId', '=', 'incomes.id')
            ->join('exchange_rates', 'incomes.exchangeDataId', '=', 'exchange_rates.id')
            ->whereBetween('exchanges.date', [$startDate, $endDate])
            ->sum(DB::raw('(exchanges.rate - exchange_rates.rate) * exchange_segments.amount_exchanged'));
    }

    private function getForceExchangeGainByPeriod(User $user, string $startDate, string $endDate): float
    {
        return $user->forceExchanges()
            ->join('exchange_rates', 'incomes.exchangeDataId', '=', 'exchange_rates.id')
            ->whereBetween('exchange_rates.date', [$startDate, $endDate])
            ->sum(DB::raw('force_exchanges.rate * force_exchanges.amount'));
    }
}