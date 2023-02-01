<?php

namespace App\Http\Requests;

use App\Models\Referee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRefereeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('referee_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'designation' => [
                'string',
                'required',
            ],
            'mobile' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'period_known' => [
                'string',
                'required',
            ],
        ];
    }
}
