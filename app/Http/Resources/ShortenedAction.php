<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShortenedAction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this->type){
            case 'income':
                $incomeArray = [
                    'type' => 'income',
                    'id' => $this->id,
                    'amount' => $this->amount,
                    'currencyCode' => $this->currency_code,
                    'date' => $this->date->toDateString()
                ];
                if (isset($this->exchange_segments)){
                    $incomeArray['amount_exchanged'] = $this->exchange_segments->amount_exchanged;
                }
                return $incomeArray;
            case 'exchange':
                $exchangeArray = [
                    'type' => 'exchange',
                    'id' => $this->id,
                    'amount' => $this->amount,
                    'rate' => $this->rate,
                    'amountInUah' => $this->amount * $this->rate,
                    'currencyCode' => $this->currency_code,
                    'date' => $this->date->toDateString()
                ];
                if (isset($this->exchange_segments)){
                    $exchangeArray['amount_exchanged'] = $this->exchange_segments->amount_exchanged;
                }
                return $exchangeArray;
            case 'forceExchange':
                return [
                    'type' => 'forceExchange',
                    'id' => $this->id,
                    'amount' => $this->amount,
                    'rate' => $this->rate,
                    'amountInUah' => $this->amount * $this->rate,
                    'currencyCode' => $this->currency_code,
                    'date' => $this->date->toDateString()
                ];
        }
    }
}
