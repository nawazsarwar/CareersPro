<?php

namespace App\Http\Requests;

use App\Models\AcademicQualification;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAcademicQualificationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('academic_qualification_create');
    }

    public function rules()
    {
        return [
            'name_id' => [
                'required',
                'integer',
            ],
            'course' => [
                'string',
                'required',
            ],
            'board_id' => [
                'required',
                'integer',
            ],
            'year' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'division' => [
                'required',
            ],
            'percentage' => [
                'numeric',
                'min:0',
                'max:100',
            ],
            'cgpa' => [
                'numeric',
                'min:0',
            ],
            'subjects' => [
                'string',
                'required',
            ],
            'title' => [
                'string',
                'required',
            ],
            'remarks' => [
                'string',
                'nullable',
            ],
            'document' => [
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
