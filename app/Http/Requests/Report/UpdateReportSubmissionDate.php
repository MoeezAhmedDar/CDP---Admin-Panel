<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportSubmissionDate extends FormRequest
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

            'starting_date' => 'required',
            'ending_date' => 'required',
        ];
    }
}
