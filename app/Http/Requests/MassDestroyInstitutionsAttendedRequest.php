<?php

namespace App\Http\Requests;

use App\Models\InstitutionsAttended;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInstitutionsAttendedRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('institutions_attended_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:institutions_attendeds,id',
        ];
    }
}
