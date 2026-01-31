<?php

namespace App\Http\Requests;

use App\Models\AdvertisementType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAdvertisementTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advertisement_type_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
            ],
        ];
    }
}
