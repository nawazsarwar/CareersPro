<?php

namespace App\Http\Requests;

use App\Models\Traed;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTraedRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('traed_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'teaching_at_bachelors_level_in_years' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'teaching_at_masters_level_in_years' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_at_masters_level_in_years' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_at_post_doctorals_level_in_years' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'experience_in_educational_administration_in_years' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'any_other_administrative_experience_in_years' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
