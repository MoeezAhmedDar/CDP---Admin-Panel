<?php

namespace App\Http\Requests\CleanSheet;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'retailer_name' => 'required',
            'type' => 'required',
            'province' => 'required',
            'sku' => 'required',
            'product_name' => 'required',
            'brand' => 'required',
            'sold' => 'required',
            'purchased' => 'required',
            'average_price' => 'required',
            'average_cost' => 'required',
            'barcode' => 'required',
        ];
    }
}
