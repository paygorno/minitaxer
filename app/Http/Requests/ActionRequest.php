<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
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
        switch ($this->input('type')) {
            case 'income':
                return [
                    'currencyCode' => ['required', 'exists:currencies,currencyCode'],
                    'amount' => ['required', 'numeric', 'gt:0'],
                    'date' => ['required', 'date', 'before:tomorrow']
                ];
            case 'exchange':
                return [
                    'currencyCode' => ['required', 'exists:currencies,currencyCode', 'not_regex:/^UAH$/'],
                    'amount' => ['required', 'numeric', 'gt:0'],
                    'rate' => ['required', 'numeric', 'gt:0'],
                    'date' => ['required', 'date', 'before:tomorrow']
                ];
            case 'forceExchange':
                return [
                    'currencyCode' => ['required', 'exists:currencies,currencyCode', 'not_regex:/^UAH$/'],
                    'amount' => ['required', 'numeric', 'gt:0'],
                    'rate' => ['required', 'numeric', 'gt:0'],
                    'date' => ['required', 'date', 'before:tomorrow']
                ];
            default:
                return [
                    'type' => ['required', 'in:income,exchange,forceExchange']
                ];
        }
    }
}
