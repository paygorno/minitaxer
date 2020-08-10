<?php

namespace App\Action;

use App\User;
use App\Action\ForceExchange;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\DB;


class ForceExchangeService
{
    public function isPossibleToCreateForceExchange(
        User $user,
        string $currencyCode,
        string $date,
        float $precentAmount
    ): bool
    {
        $income = $user->incomes()
            ->join('exchange_rates', 'exchange_rates.id', 'incomes.exchangeDataId')
            ->join('currencies', 'currencies.id', 'exchange_rates.currencyId')
            ->where('currencies.currencyCode', $currencyCode)
            ->where('exchange_rates.date', '=', $date)
            ->first('incomes.*');
        return $income && ($precentAmount / 100 * $income->amount >= $income->pending) && !$income->forceExchange;
    }

    public function create(
        User $user,
        string $currencyCode,
        string $date,
        float $precentAmount,
        float $exchangeRate
    )
    {
        $income = $user->incomes()
            ->join('exchange_rates', 'exchange_rates.id', 'incomes.exchangeDataId')
            ->join('currencies', 'currencies.id', 'exchange_rates.currencyId')
            ->where('currencies.currencyCode', $currencyCode)
            ->where('exchange_rates.date', '=', $date)
            ->first('incomes.*');
        $forceExchange = ForceExchange::create([
            'amount' => $precentAmount / 100 * $income->amount,
            'rate' => $exchangeRate,
            'incomeId' => $income->id
        ]);
        $income->pending -= $forceExchange->amount;
        $income->amount -= $forceExchange->amount;
        $income->save();
    }

    public function delete(ForceExchange $forceExchange)
    {
        $income = $forceExchange->income;
        $income->pending += $forceExchange->amount;
        $income->amount += $forceExchange->amount;
        $income->save();
        $forceExchange->delete();
    }

    public function indexFiltered(
        User $user,
        ?string $startDate = null,
        ?string $endDate = null,
        ?array $currencyCodes = null
    ): LazyCollection
    {
        $query = $this->getIndexQuery($user);
        $query = $query->whereHas('income', function ($query) use ($startDate, $endDate, $currencyCodes){
            return $query->whereHas('exchangeData', function ($query) use ($startDate, $endDate, $currencyCodes){
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
        });
        return $query->orderBy('force_exchanges.created_at', 'desc')->select('force_exchanges.*')->cursor();
    }

    private function getIndexQuery(User $user)
    {
        return $user->forceExchanges()
            ->with('income', 'income.exchangeData', 'income.exchangeData.currency');
    }

    public function findById(int $id)
    {
        return ForceExchange::with('income', 'income.exchangeData', 'income.exchangeData.currency')
            ->where('id', $id);
    }
}
