<?php

namespace App\Http\Requests\Retailer;

use Illuminate\Foundation\Http\FormRequest;

class RetailerAddressStoreRequest extends FormRequest
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
            'street_number.*' => 'required|numeric',
            'street_name.*' => 'required|string',
            'city.*' => 'required',
            'location.*' => 'string',
            'province.*' => 'required|string',
            'contact_person_name_at_location.*' => 'required|string',
        ];
    }
}
