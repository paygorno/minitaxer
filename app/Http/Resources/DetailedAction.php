<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShortenedAction;

class DetailedAction extends JsonResource
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
                return [
                    'type' => 'income',
                    'id' => $this->id,
                    'amount' => $this->amount,
                    'currencyCode' => $this->currencyCode,
                    'date' => $this->date->toDateString(),
                    'pending' => $this->pending,
                    'exchanges' => ShortenedAction::collection($this->exchanges),
                    'forceExchange' => new ShortenedAction($this->forceExchange)
                ];
            case 'exchange':
                return [
                    'type' => 'exchange',
                    'id' => $this->id,
                    'amount' => $this->amount,
                    'rate' => $this->rate,
                    'amountInUah' => $this->amount * $this->rate,
                    'currencyCode' => $this->currencyCode,
                    'date' => $this->date->toDateString(),
                    'incomes' => ShortenedAction::collection($this->incomes)
                ];
            case 'forceExchange':
                return [
                    'type' => 'forceExchange',
                    'id' => $this->id,
                    'amount' => $this->amount,
                    'rate' => $this->rate,
                    'amountInUah' => $this->amount * $this->rate,
                    'currencyCode' => $this->currencyCode,
                    'date' => $this->date->toDateString(),
                    'income' => new ShortenedAction($this->income)
                ];
        }
    }
}
