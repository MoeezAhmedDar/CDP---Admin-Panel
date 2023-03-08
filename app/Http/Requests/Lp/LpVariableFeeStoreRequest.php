<?php

namespace App\Http\Requests\Lp;

use Illuminate\Foundation\Http\FormRequest;

class LpVariableFeeStoreRequest extends FormRequest
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
            'province.*' => 'required',
            'category.*' => 'required',
            'brand.*' => 'required',
            'product_name.*' => 'required',
            'provincial.*' => 'required',
            'GTin.*' => 'required',
            'product.*' => 'required',
            'thc.*' => 'required',
            'cbd.*' => 'required',
            'case.*' => 'required',
            'unit_cost.*' => 'required',
            'offer.*' => 'required',
            'offer_end.*' => 'required',
            'data.*' => 'required',
            'comments.*' => 'required',
            'links.*' => 'required',
        ];
    }
}
