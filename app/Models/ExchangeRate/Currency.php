<?php

namespace App\ExchangeRate;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    public $timestamps = false;
    protected $fillable = ['currencyCode'];

    public function exchangeRates()
    {
        return $this->hasMany('App\ExchangeRate\ExchangeRateData', 'currencyId', 'id');
    }
}
