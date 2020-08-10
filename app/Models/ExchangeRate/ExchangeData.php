<?php

namespace App\ExchangeRate;

use Illuminate\Database\Eloquent\Model;

class ExchangeData extends Model
{
    protected $table = 'exchange_rates';
    protected $dateFormat = 'd-m-Y';
    protected $fillable = ['rate', 'date', 'currencyId'];
    public $timestamps = false;
    

    public function incomes()
    {
        return $this->hasMany(
            'App\Action\Income',
            'exchangeDataId',
            'id'
        );
    }
    
    public function currency()
    {
        return $this->belongsTo('App\ExchangeRate\Currency', 'currencyId', 'id');
    }
}
