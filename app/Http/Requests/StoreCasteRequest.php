<?php

namespace App\Http\Requests;

use App\Models\Caste;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCasteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('caste_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:castes',
            ],
        ];
    }
}
