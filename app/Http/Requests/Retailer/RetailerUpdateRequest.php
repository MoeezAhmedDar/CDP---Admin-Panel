<?php

namespace App\Http\Requests\Retailer;

use Illuminate\Foundation\Http\FormRequest;

class RetailerUpdateRequest extends FormRequest
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
            'corporate_name' => 'required',
            'DBA' => 'required',
            'owner_name' => 'required',
            'street_name.*' => 'required',
            'postal_code.*' => 'required',
            'location.*' => 'required',
            'contact_person_name_at_location.*' => 'required',
            'aggregated_data' => 'required',
        ];
    }
}
