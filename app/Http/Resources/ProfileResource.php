<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'taxRate' => $this->taxRate,
            'forceExchange' => $this->forceExchange,
            'forceExchangeAmount' => $this->forceExchangeAmount,
            'notificationPeriod' => $this->notificationPeriod
        ];
    }
}
