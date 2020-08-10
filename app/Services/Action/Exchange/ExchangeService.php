<?php

namespace App\Action;

use App\User;
use App\Action\Exchange;
use App\Action\Income;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\DB;


class ExchangeService
{
    public function isPossibleToCreateExchange(
        User $user,
        string $currencyCode,
        string $date,
        float $amount
    ): bool
    {
        $currencyAmountAtDate = $user->incomes()
            ->join('exchange_rates', 'exchange_rates.id', 'incomes.exchangeDataId')
            ->join('currencies', 'currencies.id', 'exchange_rates.currencyId')
            ->where('currencies.currencyCode', $currencyCode)
            ->where('exchange_rates.date', '<=', $date)
            ->sum('incomes.pending');
        return $currencyAmountAtDate >= $amount;
    }

    public function create(
        User $user,
        string $currencyCode,
        string $date,
        float $amount,
        float $exchangeRate
    )
    {
        $exchange = Exchange::create([
            'amount' => $amount,
            'date' => $date,
            'rate' => $exchangeRate
        ]);
        $exchange->incomes()->attach(
            $this->createExchangeSegments(
                $user,
                $currencyCode,
                $date,
                $amount
            )
        );
    }

    public function delete(Exchange $exchange)
    {
        $incomes = $exchange->incomes;
        foreach ($incomes as $income){
            $income->pending += $income->pivot->amount_exchanged;
            $income->save();
        }
        $exchange->delete();
    }

    public function indexFiltered(
        User $user,
        ?string $startDate = null,
        ?string $endDate = null,
        ?array $currencyCodes = null
    ): LazyCollection
    {
        $query = $this->getIndexQuery($user);
        if ($startDate)
            $query = $query->where('date', '>=', $startDate);
        if ($startDate)
            $query = $query->where('date', '<=', $endDate);
        if ($currencyCodes)
            $query = $query->whereHas('incomes', function ($query) use ($currencyCodes){
                return $query->whereHas('exchangeData', function ($query) use ($currencyCodes) {
                    $query->whereHas('currency', function ($query) use ($currencyCodes){
                        return $query->whereIn('currencyCode', $currencyCodes);
                    });
                });
            });
        return $query->orderBy('created_at', 'desc')->cursor('exchanges.*');
    }

    private function getIndexQuery(User $user)
    {
        return Exchange::with('incomes', 'incomes.exchangeData', 'incomes.exchangeData.currency')
            ->whereHas('incomes', function ($query) use ($user){
                $query->where('userId', $user->id);
            });
    }

    public function findById(int $id)
    {
        return Exchange::with('incomes', 'incomes.exchangeData', 'incomes.exchangeData.currency')
            ->where('id', $id);
    }

    private function createExchangeSegments(
        $user,
        $currencyCode,
        $date,
        $amount
    )
    {
        $incomesToExchange = $user->incomes()
            ->join('exchange_rates', 'exchange_rates.id', '=', 'incomes.exchangeDataId')
            ->join('currencies', 'exchange_rates.currencyId', '=', 'currencies.id')
            ->where('exchange_rates.date', '<=', $date)
            ->where('currencies.currencyCode', $currencyCode)
            ->where('incomes.pending', '>', 0)
            ->orderBy('exchange_rates.date')
            ->select('incomes.*')
            ->cursor();
        $idsToAmounts = [];
        foreach($incomesToExchange as $income){
            if ($amount <= 0) break;
            $exchanging = min($income->pending, $amount);
            $amount -= $exchanging;
            $income->pending -= $exchanging;
            $income->save();
            $idsToAmounts[$income->id] = ['amount_exchanged' => $exchanging];
        }
        return $idsToAmounts;
    }
}