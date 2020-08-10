<?php

namespace App\Action;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Income extends Model
{
    protected $table = 'incomes';
    protected $fillable = [
        'amount',
        'pending',
        'userId',
        'exchangeDataId'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'userId', 'id');
    }

    public function exchangeData()
    {
        return $this->belongsTo('App\ExchangeRate\ExchangeData', 'exchangeDataId', 'id');
    }

    public function exchanges()
    {
        return $this->belongsToMany('App\Action\Exchange', 'exchange_segments', 'incomeId', 'exchangeId')
            ->withPivot('amount_exchanged');
    }

    public function forceExchange()
    {
        return $this->hasOne('App\Action\ForceExchange', 'incomeId', 'id');
    }

    public function getCurrencyCodeAttribute()
    {
        return $this->exchangeData->currency->currencyCode;
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->exchangeData->date, 'Europe/Kiev');
    }

    public function getTypeAttribute()
    {
        return 'income';
    }
}
