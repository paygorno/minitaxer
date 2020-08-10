<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Action\Exchange;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'taxRate',
        'forceExchange',
        'forceExchangeAmount',
        'notificationPeriod'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = false;

    public function username(){
        return 'email';
    }

    public function incomes()
    {
        return $this->hasMany('App\Action\Income', 'userId', 'id');
    }

    public function forceExchanges()
    {
        return $this->hasManyThrough(
            'App\Action\ForceExchange',
            'App\Action\Income',
            'userId',
            'incomeId',
            'id',
            'id'
        );
    }
}
