<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'gain' => $this->gain,
            'tax' => $this->tax,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ];
    }
}
