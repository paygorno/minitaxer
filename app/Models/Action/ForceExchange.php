<?php

namespace App\Action;

use Illuminate\Database\Eloquent\Model;

class ForceExchange extends Model
{
    protected $table = 'force_exchanges';
    protected $fillable = [
        'amount',
        'rate',
        'incomeId'
    ];

    public function income()
    {
        return $this->belongsTo('App\Action\Income', 'incomeId', 'id');
    }

    public function getCurrencyCodeAttribute()
    {
        return $this->income->currencyCode;
    }

    public function getUserIdAttribute()
    {
        return $this->income->userId;
    }

    public function getDateAttribute()
    {
        return $this->income->date;
    }

    public function getTypeAttribute()
    {
        return 'forceExchange';
    }
}
