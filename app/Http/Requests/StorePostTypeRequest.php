<?php

namespace App\Http\Requests;

use App\Models\PostType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePostTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('post_type_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'pdf_template' => [
                'string',
                'required',
            ],
            'submission_venue' => [
                'string',
                'nullable',
            ],
            'status' => [
                'string',
                'nullable',
            ],
        ];
    }
}
