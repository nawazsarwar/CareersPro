<?php

namespace App\Http\Requests;

use App\Models\QualificationLevel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateQualificationLevelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('qualification_level_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:qualification_levels,name,' . request()->route('qualification_level')->id,
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
