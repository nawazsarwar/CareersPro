<?php

namespace App\Http\Requests;

use App\Models\AdvertisementType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAdvertisementTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advertisement_type_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
