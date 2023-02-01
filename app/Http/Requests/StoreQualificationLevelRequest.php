<?php

namespace App\Http\Requests;

use App\Models\QualificationLevel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreQualificationLevelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('qualification_level_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:qualification_levels',
            ],
            'value' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'status' => [
                'string',
                'nullable',
            ],
        ];
    }
}
