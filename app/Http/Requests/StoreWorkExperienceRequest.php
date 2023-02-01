<?php

namespace App\Http\Requests;

use App\Models\WorkExperience;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWorkExperienceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('work_experience_create');
    }

    public function rules()
    {
        return [
            'employer' => [
                'string',
                'required',
            ],
            'designation' => [
                'string',
                'required',
            ],
            'from' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'to' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'responsibilities' => [
                'string',
                'required',
            ],
            'reason_for_leaving' => [
                'string',
                'required',
            ],
            'pay_band' => [
                'string',
                'required',
            ],
            'basic_pay' => [
                'string',
                'required',
            ],
            'gross_pay' => [
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
