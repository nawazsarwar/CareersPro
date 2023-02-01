<?php

namespace App\Http\Requests;

use App\Models\EligibilityTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEligibilityTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('eligibility_test_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'agency' => [
                'string',
                'required',
            ],
            'year' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'subject' => [
                'string',
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
