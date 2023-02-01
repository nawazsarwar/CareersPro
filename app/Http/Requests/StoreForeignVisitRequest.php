<?php

namespace App\Http\Requests;

use App\Models\ForeignVisit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreForeignVisitRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('foreign_visit_create');
    }

    public function rules()
    {
        return [
            'country_id' => [
                'required',
                'integer',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'duration' => [
                'string',
                'required',
            ],
            'purpose' => [
                'string',
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
