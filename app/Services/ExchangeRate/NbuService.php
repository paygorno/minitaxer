<?php

namespace App\ExchangeRate;

use App\ExchangeRate\BankService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\ExchangeRate\ExchangeData;
use App\ExchangeRate\Currency;

class NbuServiceVersion1 implements BankService
{
    protected const EXCHANGE_URL = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange';

    protected const REQUEST_CURRENCY_CODE_KEY = 'valcode';
    protected const REQUEST_EXCHANGE_DATE_KEY = 'date';
    protected const REQUEST_RESPONSE_FORMAT_KEY = 'json';

    protected const REQUEST_EXCHANGE_DATE_FORMAT = 'Ymd';
    protected const REQUEST_RESPONSE_FORMAT = 'json';

    protected const RESPONSE_CURRENCY_CODE_KEY = 'cc';
    protected const RESPONSE_EXCHANGE_DATE_KEY = 'exchangedate';
    protected const RESPONSE_EXCHANGE_RATE_KEY = 'rate';

    protected const RESPONSE_EXCHANGE_DATE_FORMAT = 'd.m.Y';

    public function fetchExchangeDataByDate(string $currencyCode, string $date): ?ExchangeData
    {
        if ($currencyCode === 'UAH')
            return $this->getUahExchangeData($date);

        $carbon = new Carbon($date, 'Europe/Kiev');
        $requestDate = $carbon->format(static::REQUEST_EXCHANGE_DATE_FORMAT);
        $requestParams = [
            static::REQUEST_CURRENCY_CODE_KEY => $currencyCode,
            static::REQUEST_EXCHANGE_DATE_KEY => $requestDate,
            static::REQUEST_RESPONSE_FORMAT_KEY => static::REQUEST_RESPONSE_FORMAT
        ];
        $response = Http::get(static::EXCHANGE_URL, $requestParams);
        $responseParams = $response->json()[0];
        $responseDate = $responseParams[static::RESPONSE_EXCHANGE_DATE_KEY];
        $responseCarbon = Carbon::createFromFormat(
            static::RESPONSE_EXCHANGE_DATE_FORMAT,
            $responseDate
        );
        if (!$responseParams[static::RESPONSE_EXCHANGE_RATE_KEY])
            return null;

        $currency = Currency::where('currencyCode', $responseParams[static::RESPONSE_CURRENCY_CODE_KEY])->first();

        return (new ExchangeData)
            ->fill([
                'rate' => $responseParams[static::RESPONSE_EXCHANGE_RATE_KEY],
                'date' => $responseCarbon->toDateString(),
                'currencyId' => $currency->id
            ]);
    }

    private function getUahExchangeData(string $date)
    {
        $uah = Currency::where('currencyCode', 'UAH')->first();
        return (new ExchangeData)
            ->fill([
                'rate' => 1,
                'date' => $date,
                'currencyId' => $uah->id
            ]);
    }
}