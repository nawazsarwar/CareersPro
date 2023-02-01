<?php

namespace App\Http\Requests;

use App\Models\MaritalStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMaritalStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('marital_status_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:marital_statuses',
            ],
        ];
    }
}
