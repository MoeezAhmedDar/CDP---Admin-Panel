<?php

namespace App\Http\Requests\Retailer;

use Illuminate\Foundation\Http\FormRequest;

class RetailerAddRequest extends FormRequest
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
            'owner_first_name' => 'required|string',
            'owner_last_name' => 'required|string',
            'owner_phone_number' => 'required|numeric',
            'email' => 'required|email|unique:users,email|string',
        ];
    }
}
