<?php

namespace App\Http\Requests;

use App\Models\PostalCode;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePostalCodeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('postal_code_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'locality' => [
                'string',
                'nullable',
            ],
            'code' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'sub_district' => [
                'string',
                'nullable',
            ],
            'district' => [
                'string',
                'required',
            ],
        ];
    }
}
