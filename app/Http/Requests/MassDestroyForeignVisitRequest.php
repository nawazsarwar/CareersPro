<?php

namespace App\Http\Requests;

use App\Models\ForeignVisit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyForeignVisitRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('foreign_visit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:foreign_visits,id',
        ];
    }
}
