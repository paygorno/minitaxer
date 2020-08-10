<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => ['string', 'min:3', 'alpha', 'max:16'],
            'lastName' => ['string', 'min:3', 'alpha', 'max:16'],
            'taxRate' => ['numeric', 'gt:0', 'lte:100'],
            'forceExchange' => ['boolean'],
            'forceExchangePrecentAmount' => ['numeric', 'gt:0', 'lte:100'],
            'notificationPeriod' => ['string', 'in:off,month,quarter']
        ];
    }
}
