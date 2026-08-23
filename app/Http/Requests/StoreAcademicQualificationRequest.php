<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicQualificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'qualification_level_id' => [
                'required',
                'integer',
            ],
            'course' => [
                'string',
                'required',
            ],
            'year' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'division' => [
                'string',
                'required',
            ],
            'percentage' => [
                'numeric',
            ],
            'cgpa' => [
                'numeric',
            ],
            'subjects' => [
                'string',
                'required',
            ],
            'is_ugc_2009_compliant' => [
                'boolean',
            ],
        ];
    }
}
