<?php

namespace App\Http\Requests\Lp;

use Illuminate\Foundation\Http\FormRequest;

class LpStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'DBA' => 'required',
            'primary_contact_name' => 'required',
            'primary_contact_position' => 'required',
            'street_number' => 'required|numeric',
            'street_name' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'province' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
