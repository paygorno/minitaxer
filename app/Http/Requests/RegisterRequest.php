<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'firstName' => ['required', 'string','min:3', 'max:16'],
            'lastName' => ['required', 'string', 'min:3', 'max:16'],
            'password' => ['required', 'confirmed', 'string', 'min:8', 'max:16']
        ];
    }
}
