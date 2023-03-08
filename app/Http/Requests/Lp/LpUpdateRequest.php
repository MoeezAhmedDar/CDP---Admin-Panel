<?php

namespace App\Http\Requests\Lp;

use App\Models\Lp;
use Illuminate\Foundation\Http\FormRequest;

class LpUpdateRequest extends FormRequest
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
        $lp = $this->request->get('lp_id');
        $lp = Lp::find($lp);
        return [
            'name' => 'required',
            'DBA' => 'required',
            'email' => 'required|email|string|unique:users,email,' . $lp->user->id,
            'primary_contact_name' => 'required',
            'primary_contact_position' => 'required',
            'street_number' => 'required',
            'street_name' => 'required',
            'city' => 'required',
            'province' => 'required',
            'status' => 'required',
        ];
    }
}
