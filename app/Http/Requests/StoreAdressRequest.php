<?php

namespace App\Http\Requests;

use App\Models\Adress;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAdressRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('adress_create');
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
            ],
            'house_no' => [
                'string',
                'required',
            ],
            'street' => [
                'string',
                'required',
            ],
            'landmark' => [
                'string',
                'required',
            ],
            'locality' => [
                'string',
                'nullable',
            ],
            'city' => [
                'string',
                'nullable',
            ],
            'postal_code_id' => [
                'required',
                'integer',
            ],
            'district' => [
                'string',
                'required',
            ],
            'province_id' => [
                'required',
                'integer',
            ],
            'country_id' => [
                'required',
                'integer',
            ],
            'status' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
