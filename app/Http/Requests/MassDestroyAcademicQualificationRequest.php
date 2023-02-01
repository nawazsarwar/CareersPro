<?php

namespace App\Http\Requests;

use App\Models\AcademicQualification;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAcademicQualificationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('academic_qualification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:academic_qualifications,id',
        ];
    }
}
