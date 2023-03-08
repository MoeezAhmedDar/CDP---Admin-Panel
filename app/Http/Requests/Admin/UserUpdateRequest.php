<?php

namespace App\Http\Requests\Admin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $admin_id = $this->request->get('admin_id');
        $admin = SuperAdmin::find($admin_id);

        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->user->id,
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'role' => 'required',
        ];
    }
}
