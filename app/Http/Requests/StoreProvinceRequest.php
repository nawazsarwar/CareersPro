<?php

namespace App\Http\Requests;

use App\Models\Province;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProvinceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('province_create');
    }

    public function rules()
    {
        return [
            'type' => [
                'string',
                'nullable',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'iso_3166_2_in' => [
                'string',
                'nullable',
            ],
            'vehicle_code' => [
                'string',
                'nullable',
            ],
            'zone' => [
                'string',
                'nullable',
            ],
            'capital' => [
                'string',
                'nullable',
            ],
            'largest_city' => [
                'string',
                'nullable',
            ],
            'statehood' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'population' => [
                'string',
                'nullable',
            ],
            'area' => [
                'string',
                'nullable',
            ],
            'official_languages' => [
                'string',
                'nullable',
            ],
            'additional_official_languages' => [
                'string',
                'nullable',
            ],
        ];
    }
}
