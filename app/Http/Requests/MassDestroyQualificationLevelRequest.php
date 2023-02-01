<?php

namespace App\Http\Requests;

use App\Models\QualificationLevel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyQualificationLevelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('qualification_level_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:qualification_levels,id',
        ];
    }
}
