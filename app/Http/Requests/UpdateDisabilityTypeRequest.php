<?php

namespace App\Http\Requests;

use App\Models\DisabilityType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDisabilityTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('disability_type_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:disability_types,name,' . request()->route('disability_type')->id,
            ],
        ];
    }
}
