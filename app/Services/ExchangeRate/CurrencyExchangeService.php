<?php

namespace App\ExchangeRate;

use App\ExchangeRate\BankService;
use App\ExchangeRate\ExchangeData;
use App\ExchangeRate\Currency;
use Illuminate\Support\Facades\DB;

class CurrencyExchangeService
{
    protected BankService $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function getOrFetchExchangeDataByDate(string $currencyCode, string $date): ?ExchangeData
    {
        $exchangeData = 
            ExchangeData::where('date', $date)
                ->join('currencies', 'exchange_rates.currencyId', '=', 'currencies.id')
                ->where('currencies.currencyCode', $currencyCode)
                ->first('exchange_rates.*');
        if ($exchangeData)
            return $exchangeData;

        $fetchedExchangeData = $this->bankService->fetchExchangeDataByDate($currencyCode, $date);
        if ($fetchedExchangeData)
            $this->recordExchangeData($fetchedExchangeData);
        else 
            throw new \DomainException(
                'Exchange data fetching failed'
            );
        return $fetchedExchangeData;
    }

    public function recordExchangeData(ExchangeData $exchangeData)
    {
        $exchangeData->save();
    }
}
