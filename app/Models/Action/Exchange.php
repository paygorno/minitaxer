<?php

namespace App\Action;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exchange extends Model
{
    protected $table = 'exchanges';
    protected $fillable = [
        'amount',
        'rate',
        'date'
    ];

    public function incomes()
    {
        return $this->belongsToMany('App\Action\Income', 'exchange_segments', 'exchangeId', 'incomeId')
            ->withPivot('amount_exchanged');
    }

    public function getUserIdAttribute()
    {
        return $this->incomes->first()->userId;
    }

    public function getCurrencyCodeAttribute()
    {
        return $this->incomes->first()->currencyCode;
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->getAttributes()['date'], 'Europe/Kiev');
    }

    public function getTypeAttribute()
    {
        return 'exchange';
    }
}