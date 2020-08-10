<?php

namespace App\Action;

use App\User;
use App\Action\Income;
use Illuminate\Support\LazyCollection;
use App\ExchangeRate\ExchangeData;
use App\ExchangeRate\CurrencyExchangeService;
use Illuminate\Support\Facades\DB;
use App\Action\ForceExchangeService;


class IncomeService
{
    public function __construct(CurrencyExchangeService $currencyService, ForceExchangeService $forceExchangeService)
    {
        $this->currencyService = $currencyService;
        $this->forceExchangeService = $forceExchangeService;
    }

    public function isPossibleToCreateIncome(
        User $user,
        string $currencyCode,
        string $date
    ): bool
    {
        return $user->incomes()
            ->join('exchange_rates', 'incomes.exchangeDataId', '=', 'exchange_rates.id')
            ->join('currencies', 'exchange_rates.currencyId', '=', 'currencies.id')
            ->where('currencies.currencyCode', $currencyCode)
            ->where('exchange_rates.date', $date)
            ->first('exchange_rates.*')
            ? false
            : true;
    }

    public function create(
        User $user,
        string $currencyCode,
        string $date,
        float $amount
    )
    {
        $exchangeData = $this->currencyService->getOrFetchExchangeDataByDate($currencyCode, $date);
        $income = Income::create([
            'amount' => $amount,
            'pending' => $amount,
            'userId' => $user->id,
            'exchangeDataId' => $exchangeData->id
        ]);
        if ($user->forceExchange && $currencyCode !== 'UAH'){
            $this->forceExchangeService->create(
                $user,
                $currencyCode,
                $date,
                $user->forceExchangeAmount,
                $exchangeData->rate
            );
        }
    }

    public function delete(Income $income)
    {
        $income->exchanges()->delete();
        $income->forceExchange()->delete();
        $income->delete();
    }

    public function indexFiltered(
        User $user,
        ?string $startDate = null,
        ?string $endDate = null,
        ?array $currencyCodes = null
    ): LazyCollection
    {
        $query = $this->getIndexQuery($user);
        $query = $query->whereHas('exchangeData', function ($query) use ($startDate, $endDate, $currencyCodes){
            $newQuery = $startDate 
                ? $query->where('date', '>=', $startDate)
                : $query;
            $newQuery = $endDate 
                ? $newQuery->where('date', '<=', $endDate)
                : $newQuery;
            $newQuery = $currencyCodes 
                ? $newQuery->whereHas('currency', function ($query) use ($currencyCodes){
                        return $query->whereIn('currencyCode', $currencyCodes);
                    })
                : $newQuery;
            return $newQuery;
        });
        return $query->orderBy('created_at', 'desc')->cursor();
    }

    private function getIndexQuery(User $user)
    {
        return $query = $user->incomes()
            ->with('exchangeData', 'exchangeData.currency');
    }

    public function findById(int $id)
    {
        return Income::with('exchangeData', 'exchangeData.currency', 'exchanges', 'forceExchange')
            ->where('id', $id);

    }
}