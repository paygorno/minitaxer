<?php

namespace App\ExchangeRate;

use App\ExchangeRate\ExchangeData;

interface BankService
{
    public function fetchExchangeDataByDate(string $currencyCode, string $date): ?ExchangeData;
}