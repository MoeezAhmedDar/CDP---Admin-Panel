<?php

namespace App\Http\Requests\Retailer;

use App\Models\Retailer;
use Illuminate\Foundation\Http\FormRequest;

class RetailerUpdateRequestedRequest extends FormRequest
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
            'owner_name' => 'required',
            'email' => 'required|email|string|unique:users,email,' . $this->retailer->user->id,
            'owner_phone_number' => 'required|numeric',
        ];
    }
}
