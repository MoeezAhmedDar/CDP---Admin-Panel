<?php

namespace App\Http\Requests\Retailer;

use App\Models\Retailer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRetailerRequest extends FormRequest
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
        $retailer_id = $this->retailer->id;
        $retailer = Retailer::find($retailer_id);

        return [
            'corporate_name' => 'required',
            'DBA' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email|string|unique:users,email,' . $retailer->user->id,
            'street_name.*' => 'required',
            'city.*' => 'required',
            'province.*' => 'required',
            'location.*' => 'required',
            'contact_person_name_at_location.*' => 'required',
            'aggregated_data' => 'required',
            'status' => 'required',
        ];
    }
}
